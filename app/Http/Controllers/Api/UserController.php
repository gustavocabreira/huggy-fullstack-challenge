<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        return response()->json(auth()->user(), Response::HTTP_OK);
    }
}
