import torch
import time
import numpy as np
from src.quantizer import load_model # Assumes your existing model loader

def run_stress_test(model, data, runs=100):
    latencies = []
    model.eval()
    with torch.no_grad():
        for _ in range(runs):
            start = time.perf_counter()
            _ = model(data)
            latencies.append((time.perf_counter() - start) * 1000)
    
    return {
        "mean": np.mean(latencies),
        "p95": np.percentile(latencies, 95),
        "std": np.std(latencies)
    }

# Logic to run both FP32 and INT8 and output a CSV/JSON report
