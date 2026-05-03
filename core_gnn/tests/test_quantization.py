import sys
import os
import torch

# Industry Standard: Add the parent directory of 'src' to sys.path
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..')))

from src.quantizer import BioGraphQuantizer

def test_quantization_integrity():
    quantizer = BioGraphQuantizer()
    # Mocking standard float32 biological weights
    original = torch.tensor([0.5, -0.5, 1.0, -1.0], dtype=torch.float32)
    
    q_weights, scale = quantizer.quantize_weights(original)
    
    # Audit Check: Ensure INT8 conversion for 74.99% compression target
    assert q_weights.dtype == torch.int8
    assert scale > 0
    print("Quantization Integrity: PASSED")

if __name__ == "__main__":
    test_quantization_integrity()
