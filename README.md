# ZeroCloud-Edge-Mesh

**Lead Architect:** Swapin Vidya
**ORCID:** [https://orcid.org/0009-0009-5758-3845](https://orcid.org/0009-0009-5758-3845)
**Email:** [swapin@peachbot.in](mailto:swapin@peachbot.in)

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

---

## Overview

ZeroCloud-Edge-Mesh is a **decentralized coordination layer** designed for:

* edge-native execution
* serverless node discovery
* bandwidth-constrained environments

The system focuses on:

* **peer-to-peer discovery using a gossip protocol**
* **persistent state via SQLite-backed caching**
* **reduced payload size through INT8 quantized exchange**

---

## Problem Definition

Coordinating distributed edge nodes without a central server while maintaining:

* local execution
* minimal bandwidth usage
* deterministic state recovery

**Task:**
Decentralized node synchronization and peer discovery

**Input:**

* Node identity (`MESH_NODE_ID`)
* gRPC-based binary communication

**Output:**

* synchronized peer registry (`mesh_peers`)

**Objective:**
Enable stable coordination across heterogeneous edge nodes under constrained resources

---

## System Architecture

* **`core_gnn/`**
  Python-based execution layer handling node communication and coordination logic

* **`gateway-laravel/`**
  Laravel-based interface for triggering gossip discovery and managing node state

---

## ⚙️ Setup & Initialization

### 1. Core (Python)

```bash
cd core_gnn

python -m venv venv
# Windows:
venv\Scripts\activate
# Linux/Mac:
source venv/bin/activate

pip install grpcio grpcio-tools torch numpy pandas

python -m src.mesh_server
```

---

### 2. Gateway (Laravel)

```bash
cd gateway-laravel

composer install
touch database/database.sqlite

php artisan migrate
php artisan mesh:gossip
```

---

## Benchmark Configuration

**Hardware:**

* CPU: Intel Core i5-10210U
* RAM: 8 GB
* System: AVITA NS14A8

**Execution Settings:**

* Storage: SQLite cache
* Driver: `CACHE_STORE=database`
* Payload: 16 MB INT8 quantized handshake

---

## Performance Results

| Metric         | Value     | Observation         |
| -------------- | --------- | ------------------- |
| Model Payload  | 16.02 MB  | ~75% reduction      |
| Avg Latency    | 313.64 ms | stable execution    |
| Network Jitter | ±14.46 ms | bounded variance    |
| Accuracy Δ     | -0.9%     | minimal degradation |

---

## Key Insight

The system prioritizes **bandwidth and storage efficiency** over raw compute speed:

* only metadata is exchanged between nodes
* quantized payload reduces transfer size
* persistent cache enables offline recovery

👉 Result:
Reliable coordination on resource-limited edge hardware

---

## Quantization Strategy

* FP32 → INT8 weight conversion
* scale factors stored separately
* runtime dequantization

**Trade-offs:**

* ~75% size reduction
* minor accuracy drop (<1%)
* limited latency improvement

---

## System Integration

Current flow:

```bash
Laravel → gRPC → Python Core → Response
```

**Notes:**

* lightweight orchestration
* suitable for low-throughput edge scenarios

---

## ⚠️ Limitations

* no high-throughput validation
* latency gains are limited (memory-bound workload)
* dependent on stable peer discovery
* not evaluated for large-scale mesh networks

---

## Intellectual Property

Indian Patent Application: **202541127477**

---

## Reproducibility

* deterministic node identity via environment variable
* SQLite-backed persistent state
* controlled execution environment

---

## Technical Glossary

| Term            | Description                         |
| --------------- | ----------------------------------- |
| Gossip Protocol | decentralized peer discovery method |
| Quantization    | FP32 → INT8 conversion              |
| gRPC            | binary communication protocol       |
| Edge Node       | resource-constrained compute unit   |

---

If you want next step, I can:

* tighten this further for **investor PDF**
* or convert into a **GitHub “high-credibility” version (badges, CI, structure, visuals)**
