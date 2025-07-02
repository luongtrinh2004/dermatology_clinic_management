1. Cài thư viện IPFS và Database
pip install fastapi uvicorn python-multipart ipfshttpclient
pip install mysql-connector-python

2. Chạy local IPFS
ipfs daemon

3. Chạy code
uvicorn server_ipfs:app --reload

4. Cài thư viện API cho Laravel
composer require guzzlehttp/guzzle

5. Cấu hình filesystem trong .env
FILESYSTEM_DISK=public
php artisan storage:link

6. Cài thư viện DomPDF cho Laravel
composer require barryvdh/laravel-dompdf