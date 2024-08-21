import sys
import json
import pandas as pd
from sklearn.ensemble import IsolationForest
import joblib

# Load the model
model = joblib.load('anomaly_model.pkl')

# Read input data from stdin
input_data = sys.stdin.read()
data = json.loads(input_data)

# Convert JSON data to DataFrame
df = pd.DataFrame(data)

# Predict anomalies
df['anomaly'] = model.predict(df[['symptom_score']])

# Convert results to JSON and print
output = df.to_json(orient='records')
print(output)
