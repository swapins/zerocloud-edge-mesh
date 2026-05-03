import grpc
from concurrent import futures
import time

# Use relative imports to stay within the src package
from . import mesh_v1_pb2
from . import mesh_v1_pb2_grpc

class EdgeMeshServicer(mesh_v1_pb2_grpc.EdgeMeshServiceServicer):
    def SyncPeerEmbeddings(self, request, context):
        print(f"[AUDIT] Incoming sync from Node: {request.node_id}")
        payload_size = len(request.payload) / (1024 * 1024)
        success = payload_size <= 17.0 
        
        return mesh_v1_pb2.AuditAcknowledgment(
            receipt_id=f"rec-{int(time.time())}",
            verified=success,
            timestamp=int(time.time())
        )

def serve():
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    mesh_v1_pb2_grpc.add_EdgeMeshServiceServicer_to_server(EdgeMeshServicer(), server)
    server.add_insecure_port('[::]:50051')
    print("ZeroCloud-Edge-Mesh Sync Server started on port 50051")
    server.start()
    server.wait_for_termination()

if __name__ == "__main__":
    serve()
