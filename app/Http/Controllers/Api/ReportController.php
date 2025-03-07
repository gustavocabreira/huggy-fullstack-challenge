<?php

namespace App\Http\Controllers\Api;

use App\Actions\Report\GenerateGroupedByStateAction;
use App\Actions\Report\GroupedByCityAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Relatório agrupado por estado
     */
    public function groupedByState(GenerateGroupedByStateAction $action): JsonResponse
    {
        $states = $action->execute();

        return response()->json($states, Response::HTTP_OK);
    }

    /**
     * Relatório agrupado por cidade
     */
    public function groupedByCity(GroupedByCityAction $action): JsonResponse
    {
        $cities = $action->execute();

        return response()->json($cities, Response::HTTP_OK);
    }
}
