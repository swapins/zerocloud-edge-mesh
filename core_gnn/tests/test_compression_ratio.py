import torch
import os
from src.inference import BioGraphInference

def test_74_99_compression():
    # 1. Initialize model with standard BioGraph parameters
    in_dim, out_dim = 128, 64
    model = BioGraphInference(in_dim, out_dim)
    
    # 2. Simulate 10k protein nodes (Typical STRING v12.0 subset)
    x = torch.randn(10000, in_dim)
    edge_index = torch.randint(0, 10000, (2, 50000))
    
    # 3. Calculate FP32 size (Theoretical)
    # 10000 nodes * 64 features * 4 bytes
    fp32_size = 10000 * 64 * 4
    
    # 4. Generate Quantized Payload
    payload = model.get_quantized_payload(x, edge_index)
    int8_size = len(payload)
    
    compression_ratio = (1 - (int8_size / fp32_size)) * 100
    
    print(f"FP32 size: {fp32_size / 1e6:.2f} MB")
    print(f"INT8 size: {int8_size / 1e6:.2f} MB")
    print(f"Compression Ratio: {compression_ratio:.2f}%")
    
    # Assert we are near the 75% target (INT8 is 1/4 of FP32)
    assert 74.0 <= compression_ratio <= 76.0
    print("Compression Gate: PASSED")

if __name__ == "__main__":
    test_74_99_compression()
