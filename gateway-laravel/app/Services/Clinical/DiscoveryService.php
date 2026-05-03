<?php

namespace App\Services\Clinical;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Handles decentralized peer discovery for ZeroCloud-Edge-Mesh.
 * Aligns with Patent No. 202541127477 for decentralized intelligence.
 */
class DiscoveryService
{
    protected string $nodeId;
    protected array $seeds;

    public function __construct()
    {
        $this->nodeId = config('app.node_id', 'edge-node-' . uniqid());
        $this->seeds = config('mesh.seeds', []);
    }

    /**
     * Broadcasts presence to seed nodes to join the mesh.
     */
    public function announce(): void
    {
        foreach ($this->seeds as $seed) {
            Http::post("$seed/api/v1/mesh/register", [
                'node_id' => $this->nodeId,
                'endpoint' => config('app.url'),
                'timestamp' => now()->timestamp,
            ]);
        }
    }

    /**
     * Retrieves active peers for embedding synchronization.
     */
    public function getActivePeers(): array
    {
        return Cache::get('mesh_peers', []);
    }
}
