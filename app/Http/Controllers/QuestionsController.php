<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionStoreRequest;
use App\Models\Question;
use App\Traits\HttpResponses;

class QuestionsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(Question::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionStoreRequest $request)
    {
        $request->validated($request->all());

        return $this->success(
            Question::create($request->only('type', 'question', 'answers', 'correct')),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return $this->success($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionStoreRequest $request, Question $question)
    {
        $request->validated($request->all());
        $question->update($request->only('type', 'question', 'answers', 'correct'));
        return $this->success($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return $this->success([]);
    }
}
