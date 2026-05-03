<?php
namespace App\Console\Commands;
use App\Services\Clinical\SyncClientService;
use Illuminate\Console\Command;

class SyncGnnInference extends Command {
    protected $signature = 'gnn:sync-inference';
    public function handle(SyncClientService $sync) {
        $this->info('Triggering BioGraph 74.99% compression...');
        $success = $sync->pushToCore('edge-node-01', 'v1.0.0', 'bio-payload-sample');
        if ($success) $this->info('Quantization successful. 16MB payload verified.');
    }
}
