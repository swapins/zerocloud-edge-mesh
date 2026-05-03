import torch
import numpy as np

class BioGraphQuantizer:
    """
    Manual INT8 Quantizer for GraphSAGE weights.
    Aligns with Indian Patent No. 202541127477 for on-device intelligence.
    """
    def __init__(self, bits=8):
        self.bits = bits
        self.q_min = -128
        self.q_max = 127

    def quantize_weights(self, tensor: torch.Tensor):
        """
        Performs symmetric linear quantization.
        """
        scale = tensor.abs().max() / self.q_max
        quantized = torch.clamp(torch.round(tensor / scale), self.q_min, self.q_max)
        return quantized.to(torch.int8), scale

    def pack_to_binary(self, quantized_tensor: torch.Tensor, scale: torch.Tensor) -> bytes:
        """
        Packs weights into a deterministic byte stream for gRPC transmission.
        """
        # Convert to numpy for stable binary serialization
        weight_data = quantized_tensor.numpy().tobytes()
        scale_data = scale.numpy().astype(np.float32).tobytes()
        
        # Structure: [Scale(4 bytes)][Weights(...)]
        return scale_data + weight_data

# Architectural Baseline for BioGraph-Edge-Quantizer
if __name__ == "__main__":
    # Mocking a 64MB FP32 layer
    mock_weights = torch.randn(4096, 4096) 
    quantizer = BioGraphQuantizer()
    
    q_weights, scale = quantizer.quantize_weights(mock_weights)
    packed_payload = quantizer.pack_to_binary(q_weights, scale)
    
    print(f"Original Size: {mock_weights.element_size() * mock_weights.nelement() / 1e6:.2f} MB")
    print(f"Quantized Size: {len(packed_payload) / 1e6:.2f} MB")
