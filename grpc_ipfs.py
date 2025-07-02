from concurrent import futures
import grpc
import ipfs_pb2
import ipfs_pb2_grpc
import requests
import threading

def pin_and_provide_cid(cid: str):
    try:
        requests.get(f"https://ipfs.io/ipfs/{cid}", timeout=15)
        print(f"CID {cid} kết nối thành công.")
    except:
        print(f"CID {cid} kết nối thất bại.")

    try:
        print(f"Đang pin CID: {cid} ...")
        r = requests.post(f"http://127.0.0.1:5001/api/v0/pin/add?arg={cid}", timeout=60)
        if r.status_code == 200:
            print(f"CID {cid} pinned thành công.")
        else:
            print(f"Pin CID thất bại: {r.text}")
    except Exception as e:
        print(f"Lỗi khi pin CID: {e}")

    try:
        print(f"Đang publish CID {cid} vào DHT ...")
        r = requests.post(f"http://127.0.0.1:5001/api/v0/routing/provide?arg={cid}", timeout=30)
        if r.status_code == 200:
            print(f"CID {cid} published thành công.")
        else:
            print(f"Publish CID thất bại: {r.text}")
    except Exception as e:
        print(f"Lỗi khi publish CID: {e}")

class MyIPFSServicer(ipfs_pb2_grpc.MyIPFSServicer):
    def SendIPFS(self, request, context):
        cid = request.message.strip()
        print(f"Nhận CID: {cid}")

        threading.Thread(target=pin_and_provide_cid, args=(cid,)).start()

        return ipfs_pb2.IPFSReply(message=f"Đã nhận CID {cid} và đang xử lý pin.")

def serve():
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    ipfs_pb2_grpc.add_MyIPFSServicer_to_server(MyIPFSServicer(), server)
    server.add_insecure_port('0.0.0.0:50051')
    print("🚀 gRPC Server khởi chạy tại cổng 50051")
    server.start()
    server.wait_for_termination()

if __name__ == "__main__":
    serve()
