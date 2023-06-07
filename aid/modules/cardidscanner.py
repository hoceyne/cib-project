import cv2
import pytesseract
import json
from arabic_reshaper import arabic_reshaper
from bidi.algorithm import get_display

def preprocess_image(image):
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    gray = cv2.GaussianBlur(gray, (5, 5), 0)
    return gray

def extract_text(image, languages):
    config = f"--oem 3 --psm 6 -l {languages}"
    text = pytesseract.image_to_string(image, config=config)
    return text

def reshape_arabic_text(text):
    reshaped_text = arabic_reshaper.reshape(text)
    bidi_text = get_display(reshaped_text)
    return bidi_text


def capture_id_card():
    camera = cv2.VideoCapture(0)
    while True:
        ret, frame = camera.read()
        cv2.imshow('ID Card Scanner', frame)
        if cv2.waitKey(1) == 13:  # Wait for Enter key press (ASCII code 13)
            break
    camera.release()
    cv2.destroyAllWindows()
    return frame

# Main program
if __name__ == '__main__':
    pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'  # Path to Tesseract OCR executable
    image = capture_id_card()
    processed_image = preprocess_image(image)
    text = extract_text(processed_image, 'ara+fra')  # Specify 'ara+fra' for Arabic and French languages
    reshaped_text = reshape_arabic_text(text)
    print(reshaped_text)
