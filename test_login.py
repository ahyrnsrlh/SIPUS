import requests
import json

# Test login endpoint
url = "http://127.0.0.1:8000/login"

# First get CSRF token
session = requests.Session()
response = session.get("http://127.0.0.1:8000/")
print(f"GET / response: {response.status_code}")

# Try to extract CSRF token from response
if 'csrfToken' in response.text:
    print("CSRF token found in response")
else:
    print("CSRF token not found in response")

# Try login with test credentials
login_data = {
    'email': 'akhyar@example.com',
    'password': 'user123',
    'remember': False
}

headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
}

response = session.post(url, json=login_data, headers=headers)
print(f"POST /login response: {response.status_code}")
print(f"Response text: {response.text}")
