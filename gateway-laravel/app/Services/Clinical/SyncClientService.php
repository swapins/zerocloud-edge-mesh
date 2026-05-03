<?php
namespace App\Services\Clinical;
use App\Grpc\EdgeMesh\EdgeMeshServiceClient;
use App\Grpc\EdgeMesh\EmbeddingStream;
use Grpc\ChannelCredentials;

class SyncClientService {
    protected $client;
    public function __construct() {
        $this->client = new EdgeMeshServiceClient(config('mesh.gnn_server', '127.0.0.1:50051'), [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);
    }
    public function pushToCore(string $nodeId, string $version, string $payload): bool {
        $stream = new EmbeddingStream();
        $stream->setNodeId($nodeId);
        $stream->setProtocolVersion($version);
        $stream->setPayload($payload);
        $stream->setChecksum(crc32($payload));
        list($response, $status) = $this->client->SyncEmbedding($stream);
        return $response !== null;
    }
}
