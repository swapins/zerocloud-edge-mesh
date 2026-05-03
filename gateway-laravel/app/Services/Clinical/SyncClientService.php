<?php

namespace App\Services\Clinical;

use App\Grpc\EdgeMesh\EdgeMeshServiceClient;
use App\Grpc\EdgeMesh\EmbeddingStream;
use Grpc\ChannelCredentials;

/**
 * SyncClientService: The Laravel-to-Python Bridge.
 * Enforces the "ZeroCloud" constraint by only shipping INT8 payloads.
 */
class SyncClientService
{
    protected $client;

    public function __construct()
    {
        // Connect to the verified Python gRPC server (127.0.0.1:50051)
        $hostname = config('mesh.gnn_server');
        $this->client = new EdgeMeshServiceClient($hostname, [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);
    }

    /**
     * Sends the 16MB INT8 payload to the local intelligence core.
     */
    public function pushToCore(string $nodeId, string $version, string $payload): bool
    {
        $stream = new EmbeddingStream();
        $stream->setNodeId($nodeId);
        $stream->setProtocolVersion($version);
        $stream->setPayload($payload);
        $stream->setChecksum(crc32($payload));

        list($response, $status) = $this->client->SyncPeerEmbeddings($stream)->wait();

        if ($status->code !== 0) {
            return false;
        }

        // Return the clinical audit verification status
        return $response->getVerified();
    }
}
