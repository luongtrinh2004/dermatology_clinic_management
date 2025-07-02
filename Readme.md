1. Cài đặt thư viện IPFS và Database

```
pip install fastapi uvicorn python-multipart ipfshttpclient
pip install mysql-connector-python
```

2. Cài đặt thư viện gRPC

```
pip install grpcio-tools
```

3. Tạo các file thiết lập gRPC

```
python3 -m grpc_tools.protoc -I protos --python_out=. --grpc_python_out=. protos/ipfs.proto
```

4. Chạy local IPFS


**Lưu ý: Cài đặt trước IPFS về máy tính**

```
ipfs daemon
```

5. Chạy code

```
uvicorn server_ipfs:app --reload
```
