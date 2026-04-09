<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function attempt(Quiz $quiz)
    {
        $this->authorize('attempt', $quiz);

        $user = Auth::user();

        // Cek apakah sudah pernah attempt
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingAttempt && $existingAttempt->submitted_at) {
            return redirect()->route('courses.show', $quiz->course)
                ->with('error', 'Anda sudah mengerjakan kuis ini dan tidak dapat mengulang.');
        }

        // Buat attempt baru jika belum ada
        if (!$existingAttempt) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => $user->id,
                'started_at' => now(),
            ]);
        } else {
            $attempt = $existingAttempt;
        }

        $questions = $quiz->questions()->get();
        $totalQuestions = $questions->count();
        $mcqCount = $questions->where('tipe', 'multiple_choice')->count();
        $essayCount = $questions->where('tipe', 'essay')->count();

        return view('quizzes.attempt', compact('quiz', 'attempt', 'questions', 'totalQuestions', 'mcqCount', 'essayCount'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        if ($attempt->submitted_at) {
            return redirect()->route('courses.show', $quiz->course)
                ->with('error', 'Kuis sudah disubmit sebelumnya.');
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        // Simpan jawaban
        foreach ($request->answers as $questionId => $jawaban) {
            $question = $quiz->questions()->findOrFail($questionId);
            $isCorrect = false;
            $score = null;

            if ($question->tipe == 'multiple_choice') {
                $isCorrect = ($jawaban == $question->jawaban_benar);
                $score = $isCorrect ? 1 : 0;
            }

            Answer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'jawaban_text' => $jawaban,
                'is_correct' => $isCorrect,
                'score' => $score,
            ]);
        }

        $attempt->submitted_at = now();
        $attempt->save();

        // Hitung ulang total score
        $totalScore = $attempt->recalculateTotalScore();

        $message = $totalScore >= $quiz->passing_score
            ? 'Selamat! Anda lulus kuis dengan nilai ' . round($totalScore, 2) . '. Sertifikat akan segera diproses.'
            : 'Kuis telah disubmit. Nilai Anda: ' . round($totalScore, 2) . '. Nilai minimal lulus adalah ' . $quiz->passing_score . '.';

        logActivity([
            'action' => 'submit_quiz',
            'action_type' => 'CREATE',
            'module' => 'quiz',
            'model_type' => \App\Models\Quiz::class,
            'model_id' => $quiz->id,
            'model_name' => $quiz->judul,
            'description' => 'User submit quiz',
        ]);
        return redirect()->route('courses.show', $quiz->course)
            ->with('success', $message);
    }

    public function result(QuizAttempt $attempt)
    {
        $this->authorize('view', $attempt);

        $quiz = $attempt->quiz;
        $questions = $quiz->questions()->with(['answers' => function($query) use ($attempt) {
            $query->where('attempt_id', $attempt->id);
        }])->get();

        $mcqAnswers = $attempt->answers()->whereHas('question', function($q) {
            $q->where('tipe', 'multiple_choice');
        })->get();

        $essayAnswers = $attempt->answers()->whereHas('question', function($q) {
            $q->where('tipe', 'essay');
        })->get();

        $mcqScore = $mcqAnswers->sum('score');
        $maxMcqScore = $mcqAnswers->count();
        $essayScore = $essayAnswers->sum('score');
        $maxEssayScore = $essayAnswers->count() * 100;

        return view('quizzes.result', compact('attempt', 'quiz', 'questions', 'mcqScore', 'maxMcqScore', 'essayScore', 'maxEssayScore'));
    }

    /**
     * Review a quiz attempt
     */
    public function review(Quiz $quiz)
    {
        $user = Auth::user();

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $questions = $quiz->questions()->with(['answers' => function($query) use ($attempt) {
            $query->where('attempt_id', $attempt->id);
        }])->get();

        return view('quizzes.review', compact('quiz', 'attempt', 'questions'));
    }
}
