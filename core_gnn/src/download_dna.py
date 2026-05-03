import os
import torch
from Bio import Entrez, SeqIO

def download_plasmid_data():
    """Downloads pBR322 plasmid sequence and converts to tensor."""
    Entrez.email = "researcher@example.com"  # NCBI identifies requests
    plasmid_id = "J01749.1" # pBR322 Vector Sequence
    
    print(f"Downloading Real Plasmid Data ({plasmid_id}) from NCBI...")
    handle = Entrez.efetch(db="nucleotide", id=plasmid_id, rettype="gb", retmode="text")
    record = SeqIO.read(handle, "genbank")
    handle.close()

    # Sequence encoding (One-hot or Normalized)
    mapping = {'A': 0.25, 'T': 0.50, 'C': 0.75, 'G': 1.0}
    sequence_data = [mapping.get(base, 0.0) for base in str(record.seq).upper()]
    
    # We take the first 4096 bases to match your Edge-GNN input dimension
    dna_tensor = torch.tensor(sequence_data[:4096]).float().unsqueeze(0)
    
    os.makedirs('data', exist_ok=True)
    torch.save(dna_tensor, 'data/plasmid_input.pt')
    print(f"SUCCESS: Real DNA tensor (4096-dim) saved to data/plasmid_input.pt")

if __name__ == "__main__":
    download_plasmid_data()