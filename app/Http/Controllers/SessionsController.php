<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionStoreRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Models\Question;
use App\Models\Session;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SessionsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(Session::all());
    }

    /**
     * Top scorers method.
     */
    public function top()
    {
        $sessions = Session::whereNotNull('ended')->orderBy('correct', 'desc')->get();

        $sessions = $sessions->map(function ($session) {
            $session->diff = $session->ended->diffInSeconds($session->created_at);
            return $session;
        });

        $sorted = $sessions->sortByDesc('correct')->sortBy('diff');

        return $this->success($sorted);
    }

    /**
     * Create the specified resource.
     */
    public function store(SessionStoreRequest $request)
    {
        $request->validated($request->all());

        $questions = Question::where('type', $request->type)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->pluck('id')
            ->toArray();

        $session = array_merge(
            ['session_id' => Str::random(32)],
            $request->only('type', 'first_name', 'last_name', 'email'),
            ['questions' => $questions]
        );

        $session = Session::create($session);

        return $this->success(
            ['id' => $session->session_id]
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(SessionUpdateRequest $request, string $sessionId) {

        $session = Session::where('session_id', $sessionId)->firstOrFail();

        $request->validated($request->all());

        if ($session->created_at <= Carbon::now()->subMinute(5)) {
            return $this->success($this->stats($session), 208);
        }

        $question_key = array_search($request->question, $session->questions);
        if (false === $question_key) {
            return $this->error('Question not found for this session', 404);
        }

        $answered = empty($session->answered) ? 0 : $session->answered - 1;
        if (0 !== $answered && $question_key <= $answered) {
            return $this->error('Question is already answered', 404);
        }

        // Retreive actual question.
        $question = Question::findOrFail($session->questions[$question_key]);

        $session->increment('answered');

        if ($question->correct == $request->answer) {
            $session->increment('correct');
        }

        if ($session->answered == count($session->questions)) {
            return $this->success($this->stats($session, Carbon::now()), 208);
        }

        return $this->success([
            'correct' => $question->correct
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $sessionId)
    {
        $session = Session::where('session_id', $sessionId)->firstOrFail();

        if ($session->created_at <= Carbon::now()->subMinute(5)) {
            return $this->success($this->stats($session), 208);
        }

        if ($session->answered === count($session->questions)) {
            return $this->success($this->stats($session, Carbon::now()), 208);
        }

        $question = Question::findOrFail($session->questions[$session->answered])->toArray();
        unset($question['correct']);

        return $this->success([
            'question' => $question,
            'seconds' => Carbon::now()->diffInSeconds($session->created_at)
        ]);
    }

    /**
     * End the session
     */
    public function end(string $sessionId) {
        $session = Session::where('session_id', $sessionId)->firstOrFail();
        return $this->success($this->stats($session, Carbon::now()), 208);
    }

    /**
     * Retreive stats for the session.
     */
    private function stats(Session $session, $end = null)
    {
        $session->update([
            'ended' => $end ? $end : $session->created_at->addMinute(5)
        ]);

        return [
            'stats' => [
                'total' => count($session->questions),
                'answered' => $session->answered,
                'correct' => $session->correct
            ]
        ];
    }
}
