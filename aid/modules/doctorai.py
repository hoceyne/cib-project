import requests
import json

# Set your OpenAI API credentials
API_KEY = "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"

API_URL = 'https://api.openai.com/v1/engines/davinci-codex/completions'

# Set the prompt and additional information
prompt = "Generate a clinical report based on the following information:"
patient_name = "John Doe"
age = 45
scan_type = "Chest X-ray"
pathologies = ["Pneumonia", "Effusion"]
location = "Right lower lobe"

# Create the payload
payload = {
  "prompt": f"{prompt}\n\nPatient Name: {patient_name}\nAge: {age}\nScan Type: {scan_type}\nPathologies: {pathologies}\nLocation: {location}\n\n",
  "max_tokens": 200,
  "temperature": 0.7,
  "n": 1,
  "stop": None
}

# Set headers including the API key
headers = {
    "Content-Type": "application/json",
    "Authorization": f"Bearer {API_KEY}"
}

# Send the API request
response = requests.post(API_URL, headers=headers, json=payload)

# Parse the JSON response
data = json.loads(response.text)
generated_report = data['choices'][0]['text']

# Print the generated report
print(generated_report)
