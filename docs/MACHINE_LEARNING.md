# Módulo de Machine Learning — Medipac

## Descripción

El sistema utiliza un modelo de **K-Means Clustering** para clasificar automáticamente las recetas médicas en tres módulos de dispensación (A, B, C) según las características del paciente. El objetivo es distribuir la carga de trabajo entre los módulos de farmacia de manera optimizada.

---

## Archivos del servicio

| Archivo | Descripción |
|---|---|
| `python_service/predict_module.py` | Script principal de predicción |
| `python_service/kmeans.pkl` | Modelo K-Means preentrenado (scikit-learn 1.5.2) |
| `python_service/scaler.pkl` | StandardScaler para normalización de datos |
| `storage/app/python_service/receta_datos.json` | Archivo de entrada generado en tiempo de ejecución |

---

## Variables de entrada

El modelo recibe **3 características** para cada predicción:

| Variable | Tipo | Descripción | Valores |
|---|---|---|---|
| `diagnostico` | int | Diagnóstico codificado numéricamente | 1–45 |
| `edad` | int | Edad del paciente en años | > 0 |
| `sexo` | int | Sexo del paciente codificado | `1` = M, `2` = F |

### Mapeo de diagnósticos

El texto del diagnóstico en la receta se convierte a un número entero en `PacienteController`:

| Diagnóstico (texto exacto) | Código |
|---|---|
| `diabetes con` | 45 |
| `hipertensión con` | 1 |
| `asma con` | 2 |
| `enfermedad cardíaca` | 3 |
| `alergia severa` | 4 |
| `colesterol alto` | 5 |
| `artritis` | 6 |
| `enfermedad renal crónica` | 7 |
| `cáncer` | 8 |
| `migraña` | 9 |
| `depresión severa` | 10 |
| `ansiedad generalizada` | 11 |
| `hepatitis` | 12 |
| `alzheimer` | 13 |
| `fibromialgia` | 14 |
| `esclerosis múltiple` | 15 |
| `anemia severa` | 16 |
| `obesidad severa` | 17 |
| `insuficiencia respiratoria` | 18 |
| `epilepsia` | 19 |
| `osteoporosis` | 20 |
| `cirrosis` | 21 |
| `hipotiroidismo` | 22 |
| `parkinson` | 23 |
| `lupus` | 24 |
| `hipertension` | 25 |
| `diabetes` | 26 |

> **Importante:** La comparación es exacta y en minúsculas (`strtolower()`). Si el diagnóstico ingresado por el médico no coincide con ninguno de estos textos, el sistema retorna un error y no asigna módulo.

---

## Flujo de ejecución

```
PacienteController::seleccionarReceta()
        │
        ▼
1. Mapear diagnóstico texto → número
   $enfermedades_dummies['diabetes'] → 26

        │
        ▼
2. Preparar JSON
   {
     "diagnostico": 26,
     "edad": 45,
     "sexo": 1
   }

        │
        ▼
3. Guardar en storage/app/python_service/receta_datos.json

        │
        ▼
4. exec("python.exe -W ignore predict_module.py")

        │
        ▼
5. Python: cargar kmeans.pkl + scaler.pkl
   → leer receta_datos.json
   → np.array([[diagnostico, edad, sexo]])
   → scaler.transform(data_input)
   → modelo.predict(data_scaled)  → cluster: 0, 1 o 2
   → cluster_to_prioridad[cluster] → "A", "B" o "C"
   → print("A")

        │
        ▼
6. PHP captura stdout → $modulo = "A"

        │
        ▼
7. INSERT INTO registro_receta_pendiente
   (id_receta, fecha_registro, estado_receta, modulo)
   VALUES (X, NOW(), 'Pendiente', 'A')
```

---

## Código del script Python

```python
import json, sys, os, numpy as np, pickle
from sklearn.preprocessing import StandardScaler

script_dir = os.path.dirname(os.path.abspath(__file__))
project_dir = os.path.dirname(script_dir)

# Cargar modelo y scaler (rutas relativas al script)
with open(os.path.join(script_dir, 'kmeans.pkl'), 'rb') as f:
    modelo = pickle.load(f)

with open(os.path.join(script_dir, 'scaler.pkl'), 'rb') as f:
    scaler = pickle.load(f)

# Leer datos de entrada
json_path = os.path.join(project_dir, 'storage', 'app', 'python_service', 'receta_datos.json')
with open(json_path, 'r') as f:
    datos = json.load(f)

# Preparar y escalar datos
data_input = np.array([[datos['diagnostico'], datos['edad'], datos['sexo']]])
data_scaled = scaler.transform(data_input)

# Predecir y mapear resultado
prediccion_cluster = modelo.predict(data_scaled)
cluster_to_prioridad = {0: 'A', 1: 'B', 2: 'C'}
print(cluster_to_prioridad[prediccion_cluster[0]])
```

---

## Dependencias Python

```bash
pip install numpy scikit-learn
```

| Librería | Versión probada | Uso |
|---|---|---|
| `numpy` | 2.4.4 | Manejo de arrays numéricos |
| `scikit-learn` | 1.8.0 | Carga y predicción con K-Means |

> El modelo fue entrenado con scikit-learn 1.5.2. Al usar versión 1.8.0 aparecen advertencias de compatibilidad que se suprimen con el flag `-W ignore` en el comando de ejecución. El modelo sigue funcionando correctamente.

---

## Salida esperada

El script imprime exactamente una línea en `stdout`:

```
A
```
```
B
```
```
C
```

Si el script falla (returnCode != 0), el controlador PHP retorna un error al usuario y no registra la receta como pendiente.

---

## Limitaciones conocidas

1. **Diagnósticos fijos:** Solo se reconocen los 26 diagnósticos del mapeo. Cualquier otro texto retorna error.
2. **Ejecución síncrona:** El proceso PHP espera la respuesta de Python, lo que puede agregar latencia en servidores lentos.
3. **Archivo compartido:** El archivo `receta_datos.json` es único; en un entorno con múltiples usuarios simultáneos podrían ocurrir condiciones de carrera. Para producción se recomienda usar un nombre de archivo único por solicitud (ej: `receta_{id}.json`).
4. **Ruta Python hardcodeada:** La ruta al ejecutable de Python está fija en `PacienteController.php`. Debe actualizarse si Python se instala en una ruta distinta.
