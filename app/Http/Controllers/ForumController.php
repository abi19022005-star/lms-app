<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        return view('forum.index');
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        // Implement store logic
        return redirect()->route('forum.index')->with('success', 'Diskusi berhasil dibuat.');
    }

    public function show($id)
    {
        return view('forum.show');
    }

    public function reply(Request $request, $discussionId)
    {
        // Implement reply logic
        return back()->with('success', 'Balasan berhasil ditambahkan.');
    }
}
