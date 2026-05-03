<?php
namespace App\Services\Clinical;

use Illuminate\Support\Facades\Cache;

class DiscoveryService {
    protected string $nodeId;

    public function __construct() {
        $this->nodeId = config('mesh.node_id', 'unknown-node');
    }

    public function joinMesh() {
        // Retrieve existing peers or initialize empty array
        $peers = Cache::get('mesh_peers', []);
        
        // Add current node with a timestamp
        $peers[$this->nodeId] = [
            'last_seen' => now()->toDateTimeString(),
            'status' => 'active'
        ];

        // Use 'forever' to ensure the state survives process exits
        Cache::forever('mesh_peers', $peers);
    }
}
