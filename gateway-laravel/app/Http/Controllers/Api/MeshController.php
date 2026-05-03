<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Clinical\SyncClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeshController extends Controller
{
    public function sync(Request $request, SyncClientService $syncService)
    {
        $request->validate([
            'payload' => 'required|string',
            'version' => 'required|string',
        ]);

        $success = $syncService->pushToCore(
            config('mesh.node_id'),
            $request->version,
            base64_decode($request->payload)
        );

        if ($success) {
            Log::info("Clinical Audit: Embedding Sync Successful", ['node' => config('mesh.node_id')]);
            return response()->json(['status' => 'verified']);
        }

        return response()->json(['status' => 'rejected'], 400);
    }
}
