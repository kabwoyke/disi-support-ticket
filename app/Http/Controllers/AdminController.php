<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    //

    public function store_question_answer(Request $request){

            $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:ibml,softtrac,omniscan',
            'answer_text' => 'required|string',
            'question_attachment' => 'nullable|image|max:5120',
            'answer_attachment' => 'nullable|image|max:5120',
        ]);

        DB::connection('mysql_solves')->transaction(function () use ($request, $validated) {
            $questionPath = null;
            $answerPath = null;

            if ($request->hasFile('question_attachment')) {
                $questionPath = $request->file('question_attachment')->store('attachments/questions', 'public');
            }

            if ($request->hasFile('answer_attachment')) {
                $answerPath = $request->file('answer_attachment')->store('attachments/answers', 'public');
            }

            // Create Question Record
            $questionId = DB::connection('mysql_solves')->table('questions')->insertGetId([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'priority' => 'medium',
                'created_by' => Auth::id(),
                'status' => 'approved',
                'is_final' => 1,
                'attachment' => $questionPath,

            ]);

            // Create Answer Record
            DB::connection('mysql_solves')->table('answers')->insert([
                'question_id' => $questionId,
                'answer_text' => $validated['answer_text'],
                'created_by' => Auth::id(),
                'status' => 'approved',
                'attachment' => $answerPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Question and answer published successfully.');

    }


  public function approve_question(Request $request, $id)
{
    // 1. Authorize: Ensure only admins can approve questions
    if (auth('solves')->user()?->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    // 2. Find and update the question record
    $question = Question::findOrFail($id);
    $question->update([
        'status' => 'approved'
    ]);

    // 3. Return back to refresh Inertia props cleanly
    return redirect()->back()->with('success', 'Question approved successfully.');
}


public function reject_question(Request $request, $id)
{
    // Authorize: Ensure only admins can reject questions
    if (auth('solves')->user()?->role !== 'admin') {
        abort(403, 'Unauthorized action.');
    }

    $question = Question::findOrFail($id);
    $question->update([
        'status' => 'rejected'
    ]);

    return redirect()->back()->with('success', 'Question rejected.');
}

public function detail_page(Request $request, $id)
{
   $questionDetail = Question::with([
        'author',
        // Include 'author_id' (or 'user_id') so Eloquent can resolve the relation
        'answer'
    ])->findOrFail($id);

    // dd($questionDetail);
    // Increment view count
    $questionDetail->increment('views');

    return Inertia::render('QuestionDetail', [
        'question' => $questionDetail,
    ]);
}
}


