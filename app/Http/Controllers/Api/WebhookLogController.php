<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class WebhookLogController extends Controller
{
    public function index(Request $request)
    {
        $webhooks = WebhookLog::query()
            ->where('user_id', auth()->user()->id)
            ->orderBy($request->input('order_by') ?? 'created_at', $request->input('direction') ?? 'asc')
            ->paginate($request->input('per_page') ?? 10);

        return response()->json($webhooks);
    }
}
