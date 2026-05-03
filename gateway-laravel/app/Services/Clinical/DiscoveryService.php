<?php

namespace App\Services\Clinical;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscoveryService
{
    protected string $nodeId;
    protected array $seeds;

    public function __construct()
    {
        $this->nodeId = config('mesh.node_id');
        $this->seeds = config('mesh.seeds', []);
    }

    /**
     * Join the mesh by announcing to seed nodes.
     * Aligns with the P2P embedding synchronization goal.
     */
    public function joinMesh(): void
    {
        foreach ($this->seeds as $seed) {
            try {
                $response = Http::timeout(3)->post("$seed/api/v1/mesh/register", [
                    'node_id' => $this->nodeId,
                    'endpoint' => config('app.url'),
                ]);

                if ($response->successful()) {
                    $this->updatePeerList($response->json('peers', []));
                }
            } catch (\Exception $e) {
                Log::warning("Seed node unreachable: {$seed}");
            }
        }
    }

    /**
     * Updates the deterministic peer registry in the local cache.
     */
    protected function updatePeerList(array $newPeers): void
    {
        $peers = Cache::get('mesh_peers', []);
        
        foreach ($newPeers as $id => $endpoint) {
            if ($id !== $this->nodeId) {
                $peers[$id] = [
                    'endpoint' => $endpoint,
                    'last_seen' => now()->timestamp
                ];
            }
        }

        Cache::put('mesh_peers', $peers, now()->addMinutes(30));
    }
}
