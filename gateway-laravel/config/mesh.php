<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ZeroCloud-Edge-Mesh Node Identity
    |--------------------------------------------------------------------------
    | Unique identifier for this edge node. Essential for the gossip protocol
    | and clinical audit trails under Patent No. 202541127477.
    */
    'node_id' => env('MESH_NODE_ID', 'edge-node-01'),

    /*
    |--------------------------------------------------------------------------
    | GNN Core Connection
    |--------------------------------------------------------------------------
    | The gRPC endpoint for the BioGraph-Edge-Quantizer.
    */
    'gnn_server' => env('MESH_GNN_SERVER', '127.0.0.1:50051'),
];
