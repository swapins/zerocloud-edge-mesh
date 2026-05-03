<?php
namespace Grpc;
class ChannelCredentials {
    public static function createInsecure() { return new self(); }
}
