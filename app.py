from fastapi import FastAPI, HTTPException, status

app = FastAPI()



@app.get('/')
def home():
    return {"Message": "Hello"}
