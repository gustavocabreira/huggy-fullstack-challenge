<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TwilioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TwilioController extends Controller
{
    public function __construct(protected TwilioService $twilioService) {}

    /**
     * Processa o webhook do Twilio
     */
    public function handleTwilioWebhook(Request $request)
    {
        $response = $this->twilioService->handleTwilioWebhook($request->input('to'));

        return response($response)->header('Content-Type', 'application/xml');
    }

    /**
     * Gera um token para autenticação do Twilio
     */
    public function generateToken(): JsonResponse
    {
        $token = $this->twilioService->generateToken();

        return response()->json(['token' => $token], Response::HTTP_OK);
    }
}
