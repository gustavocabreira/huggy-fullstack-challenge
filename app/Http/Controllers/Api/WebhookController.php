<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    /**
     * Processa o webhook (para fins de teste)
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['message' => 'ok'], Response::HTTP_OK);
    }
}
