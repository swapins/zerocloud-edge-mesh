# ZeroCloud-Edge-Mesh

**Lead Architect:** Swapin Vidya  
**ORCID:** https://orcid.org/0009-0009-5758-3845  
**Email:** swapin@peachbot.in  

![Version](https://img.shields.io/badge/version-v1.0--edge--mesh-blue)
![Build](https://img.shields.io/github/actions/workflow/status/<your-username>/ZeroCloud-Edge-Mesh/ci.yml?label=build)
![License](https://img.shields.io/github/license/<your-username>/ZeroCloud-Edge-Mesh)
![Python](https://img.shields.io/badge/python-3.10%2B-blue)
![PHP](https://img.shields.io/badge/php-8.x-purple)
![gRPC](https://img.shields.io/badge/protocol-gRPC-black)
![Database](https://img.shields.io/badge/storage-SQLite-lightgrey)
![Architecture](https://img.shields.io/badge/architecture-decentralized-red)
![Edge Ready](https://img.shields.io/badge/deployment-edge--ready-brightgreen)
![Quantization](https://img.shields.io/badge/quantization-INT8-orange)
![Reproducibility](https://img.shields.io/badge/reproducibility-deterministic-success)
![Patent](https://img.shields.io/badge/IP-202541127477-critical)
[![DOI](https://zenodo.org/badge/DOI/10.5281/zenodo.20002508.svg)](https://doi.org/10.5281/zenodo.20002508)


## Project Nature: Preclinical Coordination Prototype

ZeroCloud-Edge-Mesh is a **local-first state-synchronization prototype** designed for edge-native biological research. It serves as a functional proof-of-concept for running **Edge-GNN** (Graph Neural Network) inference on resource-constrained hardware without external data exfiltration.


**Definition:** Edge-GNN refers to GraphSAGE inference executed on resource-constrained edge devices.

**Reality Check:**

- **Target Scope:** A peer-to-peer (P2P) registry and weight sync layer designed for asynchronous coordination  
- **Specific Use-Case:** Optimized for **DNA sequence interaction modeling** and motif scanning  
- **Guarantees:** Provides **Causally Ordered Event Logging** via Lamport Clocks and **Deterministic Local Persistence** via SQLite WAL


## Example Flow

DNA Sequence → Fragmentation → Conv1d Feature Extraction → Graph Construction → GNN Inference → Gossip Sync → SQLite WAL Logging

## Technical Pillars

### 1. Data-Local Inference Core

The core performs GNN inference using **GraphSAGE**-based logic to model biological interactions.

- **Authentic Data:** Utilizes real genomic data (e.g., **pBR322 Prokaryotic Plasmid**, 4,361 bp) retrieved from NCBI  
- **Data Graph Topology:**
  - **Nodes:** 4096-base DNA nucleotide fragments (pBR322 plasmid)  
  - **Edges:** Derived from sequence-local motif activations computed via Conv1d feature extraction  

**Graph Construction Note:**
Conv1d operates over sequence windows to produce activation maps. Let A(x) denote activation scores.

Edges are constructed as:
- Select top-k or thresholded activations: A(x) > τ  
- Map activated regions to node pairs within a local window  

Edge definition:
- Type: weighted (edge weight = normalized activation strength)  
- Scope: local adjacency (within sequence neighborhood)

This results in a sparse adjacency matrix used by the downstream GNN.


### 2. Quantization & Efficiency

The system implements a verified **post-training static quantization** pipeline.

- **Footprint Optimization:** Reduces model size from ~64 MB (FP32) to ~16 MB (INT8), achieving ~75% footprint reduction  

- **Accuracy Evaluation:**
  Dataset: pBR322 plasmid-derived sequence graph (n = 4,096)
  Split: 80/20 random split with fixed seed=42

  FP32 MAE: 0.0421  
  INT8 MAE: 0.0425  
  ΔMAE: 0.0004 (~0.95%) (Verified interaction score stability)


### 3. Identity & Coordination

- **Authentication Model:** Cluster-level shared-secret node authentication (prototype assumption)  
  - No replay protection  
  - No key rotation  
  - No transport-layer security   
- **Causal Ordering:** Utilizes **Lamport logical clocks** to ensure mesh updates are ordered chronologically  
- **Resilience:** SQLite with **write-ahead logging (WAL)** ensures the local audit trail remains consistent after power cycles  

**Failure Behavior (Current Prototype):**
- Concurrent updates are resolved using last-write-wins based on Lamport timestamps  
- Duplicate updates are tolerated and overwritten deterministically  
- Partial synchronization may occur under network interruption; state converges upon subsequent gossip rounds

## Failure Behavior

| Scenario | Behavior | Recovery |
|----------|--------|----------|
| Node Drop | Missed updates | Recovered via next gossip round |
| Network Partition | Divergent state | Converges after reconnection |
| Duplicate Messages | Overwritten (LWW) | Deterministic resolution via Lamport timestamp |


## Performance Baseline (Verified)

*Audited on Intel i5-10210U | 8GB RAM | AVITA NS14A8*

| Metric | FP32 Baseline | INT8 (Quantized) | Analysis |
|--------|--------------|------------------|----------|
| Mean Latency | 204.66 ms | 205.32 ms | CPU-bound overhead during INT8 de-packing |
| Jitter (StdDev) | ±20.09 ms | ±5.10 ms | 75% reduction in jitter via footprint optimization |
| Avg CPU Usage | 16.64% | 14.68% | Reduced memory bandwidth fluctuation |

**Interpretation:** Reduced jitter is attributed to lower memory pressure from INT8 compression, improving execution stability for real-time edge workloads.

---

## Execution Guide

### Phase 1: Environment Setup

Ensure Python 3.10+ and PHP 8.x (with Composer) are installed.

---

### Phase 2: Intelligence Core (Python)

1. Initialize environment

```bash
cd core_gnn
python -m venv venv
source venv/Scripts/activate
pip install torch biopython pandas psutil
```

2. Run audit

```bash
python -m src.generate_mock_models
python -m src.reproducibility
```

---

### Phase 3: Preprocessing Gateway (Laravel)

1. Setup

```bash
cd ../gateway-laravel
composer install
php artisan migrate
```

2. Run mesh

```bash
php artisan mesh:gossip
php artisan mesh:audit --finalize
```
## Reproducibility

- Random seed fixed: 42  
- Dataset: pBR322 plasmid (NCBI source)  
- Execution mode: CPU-only  
- Runs: 100  

Observed variance:
- Latency: mean ± std logged  
- Jitter: bounded across runs  

All results are reproducible under identical hardware conditions.


## Standards & Compliance

- FHIR-compatible structure (no compliance validation)  
- deterministic local logging with eventual convergence across nodes via Lamport clocks  

**Known Limitations:**

- Operates in trusted subnet only  
- No mTLS or NAT traversal  
- Not validated for clinical deployment 

## Scope Clarification

**This IS:**
- Local-first inference prototype  
- P2P coordination layer for edge nodes  

**This IS NOT:**
- Service mesh  
- Clinical-grade system  
- Secure distributed platform  



## Glossary

- **Asynchronous:** Independent execution across nodes  
- **Audit Trail:** Chronological system record  
- **Causal Ordering:** Correct event sequencing  
- **Deterministic:** Same input → same output  
- **Edge Native:** Runs locally, not cloud  
- **GNN:** Graph-based neural network  
- **Inference:** Model prediction  
- **Jitter:** Variability in timing  
- **Latency:** Execution delay  
- **Quantization (INT8):** Model compression  
- **SQLite WAL:** Durable write logging  