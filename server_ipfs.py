from fastapi import FastAPI, File, UploadFile, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
import requests
import os
import mysql.connector
import json
import grpc
import ipfs_pb2_grpc
import ipfs_pb2
import time
import threading
import uvicorn

app = FastAPI()

# Kết nối DB
db = mysql.connector.connect(
    host = "",
    port = ,
    user = "",
    password = "",
    database = "ipfs_data"
)
cursor = db.cursor()

IPFS_API = "http://127.0.0.1:5001/api/v0/add"

# Hàm gọi gRPC với CID mới
def send_cid(cid: str):
    try:
        with grpc.insecure_channel('192.168.1.84:50051') as channel:
            stub = ipfs_pb2_grpc.MyIPFSStub(channel)
            send_request = ipfs_pb2.SendRequest(message=cid)
            message_reply = stub.SendIPFS(send_request)
            print("Unary gRPC Response (Send CID):")
            print(message_reply)
    except Exception as e:
        print(f"Lỗi khi gọi gRPC: {e}")

def send_thread(cid: str):
    threading.Thread(target=send_cid, args=(cid,)).start()

@app.post("/upload/")
async def upload_file(file: UploadFile = File(...)):
    try:
        # Lưu file tạm
        temp_file_path = f"temp_{file.filename}"
        with open(temp_file_path, "wb") as f:
            f.write(await file.read())

        # Đọc nội dung JSON
        with open(temp_file_path, 'r', encoding='utf-8') as f:
            json_data = json.load(f)
        cccd = json_data.get("cccd")
        name = json_data.get("name")
        doctor_id = json_data.get("doctor_id")
        if doctor_id is None:
            doctor_id = "Unknown"

        # Gửi file đến IPFS
        with open(temp_file_path, 'rb') as f:
            files = {'file': (file.filename, f)}
            response = requests.post(IPFS_API, files=files)

        os.remove(temp_file_path)

        if response.status_code == 200:
            res_json = response.json()
            cid = res_json['Hash']

            # Pin CID trên node IPFS
            pin_api = "http://127.0.0.1:5001/api/v0/pin/add?arg=" + cid
            pin_response = requests.post(pin_api)
            if pin_response.status_code != 200:
                print(f"Failed to pin CID {cid}: {pin_response.text}")

            provide_api = f"http://127.0.0.1:5001/api/v0/routing/provide?arg={cid}"
            provide_response = requests.post(provide_api)
            if provide_response.status_code != 200:
                print(f"Failed to propagate CID {cid}: {provide_response.text}")
            else:
                print(f"Successfully propagated CID {cid}")

            # Ghi vào DB
            cursor.execute("INSERT INTO records (doctor_id, name, cccd, cid) VALUES (%s, %s, %s, %s)", (doctor_id, name, cccd, cid))
            db.commit()

            # Gửi CID qua gRPC
            send_cid(cid)

            return {
                "filename": file.filename,
                "cccd": cccd,
                "doctor_id": doctor_id,
                "cid": cid,
                "ipfs_url": f"https://ipfs.io/ipfs/{cid}"
            }

        else:
            raise HTTPException(status_code=500, detail=f"Lỗi IPFS API: {response.text}")

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Lỗi: {str(e)}")


@app.get("/records/")
def get_records_by_name(cccd: str = Query(..., description="CCCD cần truy xuất")):
    try:
        cursor.execute("SELECT cid FROM records WHERE cccd = %s", (cccd,))
        results = cursor.fetchall()

        # Lấy nội dung từng CID
        data_list = []
        for row in results:
            cid = row[0]
            ipfs_url = f"https://ipfs.io/ipfs/{cid}"
            try:
                r = requests.get(ipfs_url)
                if r.status_code == 200:
                    json_data = r.json()
                    json_data["cid"] = cid
                    data_list.append(json_data)
            except:
                continue

        return {"cccd": cccd, "records": data_list}

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Lỗi truy vấn DB: {str(e)}")

@app.get("/doctor_records/")
async def get_doctor_records(doctor_id: int):
    try:
        # Truy vấn records theo doctor_id
        cursor.execute("SELECT cccd, cid, name FROM records WHERE doctor_id = %s", (doctor_id,))
        records = cursor.fetchall()

        if not records:
            return {"records": []}

        result = []
        for record in records:
            cccd, cid, name = record
            result.append({"cccd": cccd, "name": name, "cid": cid})

        return {"records": result}

    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Lỗi: {str(e)}")

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
