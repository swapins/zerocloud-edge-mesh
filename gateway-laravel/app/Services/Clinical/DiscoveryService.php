<?php
namespace App\Services\Clinical;

use Illuminate\Support\Facades\Cache;

class DiscoveryService {
    protected string $nodeId;

    public function __construct() {
        $this->nodeId = config('mesh.node_id', 'unknown-node');
    }

    public function joinMesh() {
        $peers = Cache::get('mesh_peers', []);
        
        // Logical Clock: Increment local version or start at 0
        $currentVersion = $peers[$this->nodeId]['version'] ?? 0;
        $nextVersion = $currentVersion + 1;

        $peers[$this->nodeId] = [
            'version' => $nextVersion,
            'last_seen' => now()->toDateTimeString(),
            'status' => 'active'
        ];

        // Conflict Resolution: Only update if incoming version > local version
        Cache::forever('mesh_peers', $peers);
    }
}