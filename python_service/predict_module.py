import json
import sys
import os
import numpy as np
import pickle
from sklearn.preprocessing import StandardScaler

# Rutas relativas al script
script_dir = os.path.dirname(os.path.abspath(__file__))
project_dir = os.path.dirname(script_dir)

# Cargar el modelo y el scaler
with open(os.path.join(script_dir, 'kmeans.pkl'), 'rb') as file:
    modelo = pickle.load(file)

with open(os.path.join(script_dir, 'scaler.pkl'), 'rb') as file:
    scaler = pickle.load(file)

# Leer los datos del archivo JSON
json_path = os.path.join(project_dir, 'storage', 'app', 'python_service', 'receta_datos.json')
with open(json_path, 'r') as file:
    datos = json.load(file)

# Extraer los datos
edad = datos['edad']
sexo = datos['sexo']
diagnostico = datos['diagnostico']

# Crear el array con las características
data_input = np.array([[diagnostico, edad, sexo]])

# Escalar los datos utilizando el scaler entrenado
data_scaled = scaler.transform(data_input)

# Predecir el cluster
prediccion_cluster = modelo.predict(data_scaled)

# Mapear el cluster a la prioridad
cluster_to_prioridad = {0: 'A', 1: 'B', 2: 'C'}
prioridad = cluster_to_prioridad[prediccion_cluster[0]]

# Mostrar la prioridad
print(f"{prioridad}")
