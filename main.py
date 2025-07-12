from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.responses import JSONResponse
import tensorflow as tf
from tensorflow.keras.preprocessing.image import img_to_array, load_img
import numpy as np
from io import BytesIO
from PIL import Image

app = FastAPI()

class_names = ['Dày sừng quang hoá', 'Viêm da cơ địa', 'Dày sừng lành tính', 'U xơ da', 'Nốt ruồi sắc tố', 
              'U hắc tố ác tính', 'Ung thư biểu mô tế bào vảy', 'Nấm da/hắc lào', 'Tổn thương mạch máu']

# Tải mô hình đã huấn luyện
model = tf.keras.models.load_model('models/skin_disease_classifier.keras')

def preprocess_image(image: Image.Image):
    """Xử lý ảnh đầu vào để phù hợp với yêu cầu của mô hình."""
    image = image.resize((224, 224)) 
    image_array = img_to_array(image) / 255.0 
    image_array = np.expand_dims(image_array, axis=0) 
    return image_array

@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    try:
        # Đọc và xử lý ảnh
        contents = await file.read()
        image = Image.open(BytesIO(contents)).convert('RGB')
        image_array = preprocess_image(image)

        # Dự đoán
        predictions = model.predict(image_array)
        predicted_class = np.argmax(predictions[0])
        predicted_disease = class_names[predicted_class]

        return JSONResponse(content={"disease": predicted_disease})
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Lỗi khi xử lý ảnh: {str(e)}")

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8002)