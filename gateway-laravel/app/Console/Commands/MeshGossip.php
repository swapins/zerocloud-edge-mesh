<?php

namespace App\Console\Commands;

use App\Services\Clinical\DiscoveryService;
use Illuminate\Console\Command;

class MeshGossip extends Command
{
    protected $signature = 'mesh:gossip';
    protected $description = 'Perform P2P node discovery and peer list synchronization';

    public function handle(DiscoveryService $discovery)
    {
        $this->info('Initiating ZeroCloud-Edge-Mesh gossip sequence...');
        $discovery->joinMesh();
        $this->info('Mesh state synchronized.');
    }
}
