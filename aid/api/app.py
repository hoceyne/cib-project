# Flask API code
from flask import Flask, jsonify, request
from keras.models import load_model
import keras.utils as image

import numpy as np


# Load the trained model
model = load_model("../disease-detection/chest_xray_classification_model.h5")

app = Flask(__name__)


# Define the API route
@app.route("/predict", methods=["POST"])
def predict():
    # Define the image size and batch size
    img_width, img_height = 256, 256
    # Check if an image file was uploaded
    if "image" not in request.files:
        return jsonify({"error": "No image file uploaded"})

    img_file = request.files["image"]
    file_path = './uploaded/' + img_file.filename
    img_file.save(file_path)
    # Load and preprocess the image
    img = image.load_img(file_path, target_size=(img_width, img_height))
    img_array = image.img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)
    img_array /= 255.0

    # Make the prediction
    result = model.predict(img_array)
    class_indices = {"COVID19": 0, "NORMAL": 1, "PNEUMONIA": 2, "TUBERCULOSIS": 3}
    predicted_class = list(class_indices.keys())[np.argmax(result[0])]
    accuracy = round(np.max(result[0]) * 100, 2)

    # Prepare the response
    response = {"prediction": predicted_class, "accuracy": accuracy}

    return jsonify(response)


if __name__ == "__main__":
    app.run()
