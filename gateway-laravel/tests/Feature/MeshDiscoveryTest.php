<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MeshDiscoveryTest extends TestCase
{
    /**
     * Verifies that a node can register and be tracked in the peer list.
     */
    public function test_node_can_register_in_mesh(): void
    {
        Cache::flush();

        $response = $this->postJson('/api/v1/mesh/register', [
            'node_id' => 'test-peer-01',
            'endpoint' => 'http://192.168.1.50',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'registered']);

        // Verify the peer is in the deterministic registry
        $peers = Cache::get('mesh_peers');
        $this->assertArrayHasKey('test-peer-01', $peers);
        $this->assertEquals('http://192.168.1.50', $peers['test-peer-01']['endpoint']);
    }
}
