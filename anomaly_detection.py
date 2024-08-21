from sklearn.ensemble import IsolationForest
import pandas as pd
import joblib
import json
import sys

# Load the trained model
model = joblib.load('anomaly_model.pkl')

def predict_anomalies(data):
    # Assuming 'data' is a pandas DataFrame
    predictions = model.predict(data)
    return predictions

# Main entry point
if __name__ == "__main__":
    # Read data from standard input
    input_data = json.loads(sys.stdin.read())
    df = pd.DataFrame(input_data)
    anomalies = predict_anomalies(df)
    
    # Print results as JSON
    print(json.dumps(anomalies.tolist()))
