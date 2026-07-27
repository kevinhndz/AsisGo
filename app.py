from fastapi import FastAPI, HTTPException, status
from fastapi.

app = FastAPI()



@app.get('/')
def home():
    return {"Message": "Hello"}
