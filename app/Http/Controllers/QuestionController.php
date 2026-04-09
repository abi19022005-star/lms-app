<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        // Cek authorization: hanya guru pemilik kursus
        $user = Auth::user();

        // Perbaikan: gunakan helper dari User model
        if (!$user || ($user->role !== 'admin' && $user->role !== 'guru')) {
            abort(403);
        }

        if ($user->role === 'guru' && $quiz->course->guru_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'tipe' => 'required|in:multiple_choice,essay',
            'pertanyaan' => 'required|string',
            'opsi' => 'nullable|array',
            'jawaban_benar' => 'nullable|string',
        ]);

        $validated['quiz_id'] = $quiz->id;

        if ($request->tipe == 'multiple_choice' && isset($request->opsi)) {
            $validated['opsi'] = json_encode($request->opsi);
        }

        Question::create($validated);

        return redirect()->route('quizzes.edit', $quiz)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function update(Request $request, Question $question)
    {
        $user = Auth::user();
        $quiz = $question->quiz;

        if (!$user || ($user->role !== 'admin' && $user->role !== 'guru')) {
            abort(403);
        }

        if ($user->role === 'guru' && $quiz->course->guru_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'pertanyaan' => 'required|string',
            'opsi' => 'nullable|array',
            'jawaban_benar' => 'nullable|string',
        ]);

        if ($question->tipe == 'multiple_choice' && isset($request->opsi)) {
            $validated['opsi'] = json_encode($request->opsi);
        }

        $question->update($validated);

        return redirect()->route('quizzes.edit', $quiz)
            ->with('success', 'Soal berhasil diupdate.');
    }

    public function destroy(Question $question)
    {
        $user = Auth::user();
        $quiz = $question->quiz;

        if (!$user || ($user->role !== 'admin' && $user->role !== 'guru')) {
            abort(403);
        }

        if ($user->role === 'guru' && $quiz->course->guru_id !== $user->id) {
            abort(403);
        }

        $question->delete();

        return redirect()->route('quizzes.edit', $quiz)
            ->with('success', 'Soal berhasil dihapus.');
    }
}
