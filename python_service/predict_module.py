import numpy as np
import pickle
import json
import sys

# Mapeo de enfermedades a códigos numéricos
enfermedades_dummies = {
    'diabetes con': 45,
    'hipertensión con': 1,
    'asma con': 2,
    'enfermedad cardíaca': 3,
    'alergia severa': 4,
    'colesterol alto': 5,
    'artritis': 6,
    'enfermedad renal crónica': 7,
    'cáncer': 8,
    'migraña': 9,
    'depresión severa': 10,
    'ansiedad generalizada': 11,
    'hepatitis': 12,
    'alzheimer': 13,
    'fibromialgia': 14,
    'esclerosis múltiple': 15,
    'anemia severa': 16,
    'obesidad severa': 17,
    'insuficiencia respiratoria': 18,
    'epilepsia': 19,
    'osteoporosis': 20,
    'cirrosis': 21,
    'hipotiroidismo': 22,
    'parkinson': 23,
    'lupus': 24,
    'hipertension': 25,
    'diabetes': 26
}

try:
    # Definir rutas completas a los archivos
    modelo_path = 'C:\\xampp\\htdocs\\Laravel\\proyecto-app\\python_service\\kmeans.pkl'
    scaler_path = 'C:\\xampp\\htdocs\\Laravel\\proyecto-app\\python_service\\scaler.pkl'

    # Cargar el modelo y el scaler
    with open(modelo_path, 'rb') as file:
        modelo = pickle.load(file)

    with open(scaler_path, 'rb') as file:
        scaler = pickle.load(file)

    # Leer los datos desde el argumento
    if len(sys.argv) < 2:
        raise ValueError("No se proporcionaron datos JSON como argumento.")

    input_json = sys.argv[1]
    datos = json.loads(input_json)

    # Convertir el diagnóstico en un valor numérico
    diagnostico = datos.get("diagnostico", "").lower()
    if diagnostico not in enfermedades_dummies:
        raise ValueError(f"El diagnóstico '{diagnostico}' no está en el mapa de enfermedades.")

    condicion_medica = enfermedades_dummies[diagnostico]

    # Convertir el sexo a un valor numérico
    sexo_numero = 0 if datos["sexo"].lower() == "masculino" else 1

    # Crear el array con las características
    data_input = np.array([[condicion_medica, int(datos["edad"]), sexo_numero]])

    # Escalar los datos
    data_scaled = scaler.transform(data_input)

    # Predecir el cluster
    prediccion_cluster = modelo.predict(data_scaled)

    # Mapear el cluster a la prioridad
    cluster_to_prioridad = {0: 'A', 1: 'B', 2: 'C'}
    prioridad = cluster_to_prioridad[prediccion_cluster[0]]

    # Imprimir la prioridad como salida
    print(prioridad)

except ValueError as ve:
    print(f"Error de validación: {ve}")
except json.JSONDecodeError as je:
    print(f"Error de JSON: {je}")
except FileNotFoundError as fe:
    print(f"Error de archivo: {fe}")
except Exception as e:
    print(f"Error inesperado: {e}")
