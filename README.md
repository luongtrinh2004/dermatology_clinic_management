1. Cài đặt thư viện gRPC

```
pip install grpcio-tools
```

2. Tạo file thiết lập gRPC

```
python -m grpc_tools.protoc -I protos --python_out=. --grpc_python_out=. protos/ipfs.proto
```

3. Chạy local IPFS

**Yêu cầu: Đã cài đặt IPFS trên máy tính**

```
ipfs daemon
```

4. Chạy code

```
uvicorn server_ipfs:app --reload
```
