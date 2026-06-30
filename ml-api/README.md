# Dropout Risk Prediction API

Cette API FastAPI charge un modele XGBoost pour predire le risque d'abandon scolaire d'un etudiant.

Le modele retourne une classification binaire :
- `Not Dropout`
- `Dropout`

L'API applique le `threshold` configure dans `models/model_config.pkl` avec une valeur par defaut de `0.4`.

## Installation

```bash
cd ml-api
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

## Lancer l'API

```bash
uvicorn main:app --reload --port 8001
```

## URLs utiles

- `http://127.0.0.1:8001`
- `http://127.0.0.1:8001/docs`

## Exemple de payload JSON pour POST /predict

```json
{
  "marital_status": 1,
  "application_mode": 17,
  "application_order": 1,
  "course": 9254,
  "daytime_evening_attendance": 1,
  "previous_qualification": 1,
  "previous_qualification_grade": 130.0,
  "nacionality": 1,
  "mothers_qualification": 19,
  "fathers_qualification": 12,
  "mothers_occupation": 5,
  "fathers_occupation": 9,
  "admission_grade": 142.5,
  "displaced": 1,
  "educational_special_needs": 0,
  "debtor": 0,
  "tuition_fees_up_to_date": 1,
  "gender": 1,
  "scholarship_holder": 0,
  "age_at_enrollment": 19,
  "international": 0
}
```

## Reponse attendue

```json
{
  "prediction_label": "Dropout",
  "is_dropout_risk": true,
  "dropout_probability": 0.6734,
  "dropout_probability_percent": 67.34,
  "threshold": 0.4,
  "risk_level": "High",
  "model_name": "XGBoost Dropout Detection",
  "model_version": "V5"
}
```

## Notes

- Les fichiers `.pkl` dans `models/` ne doivent pas etre modifies.
- Laravel doit uniquement appeler cette API via HTTP.
- Le Machine Learning reste entierement cote Python/FastAPI.
- Sur macOS, XGBoost peut demander OpenMP. Si le chargement du modele echoue avec `libomp.dylib`, installez-le avec `brew install libomp`.
