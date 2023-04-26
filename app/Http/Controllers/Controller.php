<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function respondWithJson($data, $status = 200)
    {
        return response()->json($data, $status);
    }

    public function respondWithSuccess($message, $data = null, $status = 200)
    {
        return $this->respondWithJson([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function respondWithError($message, $errors = null, $status = 400)
    {
        return $this->respondWithJson([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
