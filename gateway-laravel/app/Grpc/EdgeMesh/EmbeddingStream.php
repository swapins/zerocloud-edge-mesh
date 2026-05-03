<?php
namespace App\Grpc\EdgeMesh;
class EmbeddingStream {
    protected $nodeId, $version, $payload, $checksum;
    public function setNodeId($v) { $this->nodeId = $v; }
    public function setProtocolVersion($v) { $this->version = $v; }
    public function setPayload($v) { $this->payload = $v; }
    public function setChecksum($v) { $this->checksum = $v; }
}
