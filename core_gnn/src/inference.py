import torch
from torch_geometric.nn import SAGEConv
from .quantizer import BioGraphQuantizer

class BioGraphInference(torch.nn.Module):
    def __init__(self, in_channels, out_channels):
        super().__init__()
        self.conv1 = SAGEConv(in_channels, 64)
        self.conv2 = SAGEConv(64, out_channels)
        self.quantizer = BioGraphQuantizer()

    def forward(self, x, edge_index):
        x = self.conv1(x, edge_index).relu()
        x = self.conv2(x, edge_index)
        return x

    def get_quantized_payload(self, x, edge_index):
        with torch.no_grad():
            embeddings = self.forward(x, edge_index)
            # Perform manual INT8 packing for the 16MB target
            q_weights, scale = self.quantizer.quantize_weights(embeddings)
            return self.quantizer.pack_to_binary(q_weights, scale)

if __name__ == "__main__":
    model = BioGraphInference(128, 64)
    # Generate mock data
    x = torch.randn(100, 128)
    edge_index = torch.randint(0, 100, (2, 500))
    
    payload = model.get_quantized_payload(x, edge_index)
    print(f"Quantized Payload Size: {len(payload)} bytes")
