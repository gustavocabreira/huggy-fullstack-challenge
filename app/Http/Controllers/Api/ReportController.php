<?php

namespace App\Http\Controllers\Api;

use App\Actions\Report\GenerateGroupedByStateAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function groupedByState(GenerateGroupedByStateAction $action): JsonResponse
    {
        $states = $action->execute();
        return response()->json($states, Response::HTTP_OK);
    }
}
