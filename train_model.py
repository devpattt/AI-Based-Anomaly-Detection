import pandas as pd
from sklearn.ensemble import IsolationForest
import joblib

# Sample data (replace with your actual data loading method)
data = pd.DataFrame({
    'symptom_score': [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]  # Replace with real data
})

# Training the Isolation Forest model
model = IsolationForest()
model.fit(data[['symptom_score']])

# Save the trained model
joblib.dump(model, 'anomaly_model.pkl')
