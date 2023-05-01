<?php

namespace App\Traits;

trait HttpResponses
{

    protected function success($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    protected function error($message, $code)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }
}
