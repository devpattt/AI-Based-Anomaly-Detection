import json
from sklearn.ensemble import IsolationForest

# Example function to detect anomalies
def detect_anomalies(data):
    # Convert JSON to a list of symptom scores, ensuring they are numbers
    symptom_scores = [float(record['symptom_score']) for record in data]
    
    # Example: Using Isolation Forest to detect anomalies
    model = IsolationForest(contamination=0.1)
    model.fit([[score] for score in symptom_scores])
    
    predictions = model.predict([[score] for score in symptom_scores])
    
    # Annotate each record with anomaly type
    for i, record in enumerate(data):
        score = float(record['symptom_score'])  # Convert to float for comparison
        if predictions[i] == -1:
            if score > 8:
                record['anomaly'] = 'High Risk Anomaly'
            elif score < 2:
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
