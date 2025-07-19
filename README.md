### 1. Cài đặt và truy cập môi trường ảo

**a. Cài đặt môi trường ảo**

```
python -m venv venv
```

**b. Truy cập môi trường ảo**

- Đối với Windows:

```
venv/Scripts/activate
```

- Đối với Linux:

```
Source venv/bin/activate
```

### 2. Cài dặt thư viện

```
pip install -r requirements.txt
```

### 3. Chạy chương trình

```
uvicorn main:app --host 0.0.0.0 --port 8002 --reload
```
