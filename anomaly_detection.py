import json
from sklearn.ensemble import IsolationForest

# Example function to detect anomalies
def detect_anomalies(data):
    # Convert JSON to a DataFrame or NumPy array
    symptom_scores = [record['symptom_score'] for record in data]
    
    # Example: Using Isolation Forest to detect anomalies
    model = IsolationForest(contamination=0.1)
    model.fit([[score] for score in symptom_scores])
    
    predictions = model.predict([[score] for score in symptom_scores])
    
    # Annotate each record with anomaly type
    for i, record in enumerate(data):
        if predictions[i] == -1:
            # Customize this part to detect different anomalies
            if record['symptom_score'] > 8:
                record['anomaly'] = 'High Risk Anomaly'
            elif record['symptom_score'] < 2:
                record['anomaly'] = 'Low Symptom Anomaly'
            else:
                record['anomaly'] = 'Unusual Pattern'
        else:
            record['anomaly'] = 'Normal'
    
    return data

if __name__ == '__main__':
    # Read input data from stdin
    input_data = input()
    data = json.loads(input_data)
    
    # Detect anomalies
    result = detect_anomalies(data)
    
    # Output the result as JSON
    print(json.dumps(result))
