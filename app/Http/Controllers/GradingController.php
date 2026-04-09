<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GradingController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil semua attempt yang perlu dinilai
        $query = QuizAttempt::where('is_graded', false)
            ->whereNotNull('submitted_at')
            ->with(['user', 'quiz.course']);

        if ($user->isGuru()) {
            // Guru hanya melihat attempt dari kursus miliknya
            $query->whereHas('quiz.course', function($q) use ($user) {
                $q->where('guru_id', $user->id);
            });
        }

        $attempts = $query->orderBy('submitted_at', 'asc')->get();

        // Filter hanya attempt yang memiliki essay questions
        $pendingAttempts = [];
        foreach ($attempts as $attempt) {
            $hasUnscoredEssay = $attempt->answers()
                ->whereHas('question', fn($q) => $q->where('tipe', 'essay'))
                ->whereNull('score')
                ->exists();

            if ($hasUnscoredEssay) {
                $pendingAttempts[] = $attempt;
            }
        }

        $stats = [
            'total_pending' => count($pendingAttempts),
            'total_attempts' => $attempts->count(),
        ];

        return view('grading.index', compact('pendingAttempts', 'stats'));
    }

    public function show(QuizAttempt $attempt)
    {
        $this->authorize('grade', $attempt);

        $essayAnswers = $attempt->answers()
            ->whereHas('question', fn($q) => $q->where('tipe', 'essay'))
            ->with('question')
            ->get();

        $mcqAnswers = $attempt->answers()
            ->whereHas('question', fn($q) => $q->where('tipe', 'multiple_choice'))
            ->with('question')
            ->get();

        $mcqScore = $mcqAnswers->sum('score');
        $maxMcqScore = $mcqAnswers->count();

        return view('grading.show', compact('attempt', 'essayAnswers', 'mcqAnswers', 'mcqScore', 'maxMcqScore'));
    }

    public function grade(Request $request, QuizAttempt $attempt)
    {
        $this->authorize('grade', $attempt);

        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|array',
            'feedback.*' => 'nullable|string',
        ]);

        foreach ($request->scores as $answerId => $score) {
            $answer = Answer::findOrFail($answerId);

            // Validasi bahwa answer milik attempt ini dan tipe essay
            if ($answer->attempt_id == $attempt->id && $answer->question->tipe == 'essay') {
                $answer->score = $score;
                $answer->save();

                // Simpan feedback jika ada (perlu tambahan kolom feedback di tabel answers)
                if (isset($request->feedback[$answerId])) {
                    $answer->feedback = $request->feedback[$answerId];
                    $answer->save();
                }
            }
        }

        // Hitung ulang total score
        $totalScore = $attempt->recalculateTotalScore();

        $message = "Nilai berhasil disimpan. Total nilai: " . round($totalScore, 2);

        if ($totalScore >= $attempt->quiz->passing_score) {
            $message .= " - Selamat! Siswa lulus kuis dan akan mendapatkan sertifikat.";
        } else {
            $message .= " - Maaf, siswa belum mencapai passing score.";
        }

        return redirect()->route('grading.index')
            ->with('success', $message);
    }

    public function bulkGrade(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.answer_id' => 'required|exists:answers,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
        ]);

        $updatedAttempts = [];

        foreach ($request->grades as $grade) {
            $answer = Answer::find($grade['answer_id']);
            if ($answer && $answer->question->tipe == 'essay') {
                $answer->score = $grade['score'];
                $answer->save();
                $updatedAttempts[$answer->attempt_id] = $answer->attempt_id;
            }
        }

        // Recalculate scores untuk semua attempt yang diupdate
        foreach ($updatedAttempts as $attemptId) {
            $attempt = QuizAttempt::find($attemptId);
            if ($attempt) {
                $attempt->recalculateTotalScore();
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($updatedAttempts) . ' attempts telah dinilai.'
        ]);
    }
}
