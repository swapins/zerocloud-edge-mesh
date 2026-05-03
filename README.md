# ZeroCloud-Edge-Mesh

A decentralized, serverless mesh architecture for edge-native clinical intelligence.

## Core Pillars
*   **Lead Architect**: Swapin Vidya
*   **Intellectual Property**: Indian Patent No. 202541127477 (Modular Clinical Intelligence)
*   **Edge-GNN Framework**: BioGraph models trained on STRING v12.0

## Performance Baseline
*   **Model Compression**: 74.99% (64MB to 16MB via manual INT8 weight packing)
*   **Latency**: ~313ms ± 14ms jitter

## Stack
- **GNN Core**: Python (PyTorch Geometric)
- **Gateway**: Laravel 12 (FHIR-compliant)
- **Communication**: gRPC / Protobuf
