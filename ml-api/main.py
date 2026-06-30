from pathlib import Path
from typing import Any

import joblib
import pandas as pd
from fastapi import FastAPI, HTTPException


BASE_DIR = Path(__file__).resolve().parent
MODEL_PATH = BASE_DIR / "models" / "xgboost_dropout_model_final.pkl"
CONFIG_PATH = BASE_DIR / "models" / "model_config.pkl"


def load_pickle_file(path: Path, label: str):
    if not path.exists():
        raise RuntimeError(f"{label} not found at: {path}")

    try:
        return joblib.load(path)
    except Exception as exc:  # pragma: no cover - startup failure path
        raise RuntimeError(f"Unable to load {label} from {path}: {exc}") from exc


model = load_pickle_file(MODEL_PATH, "Model file")
model_config = load_pickle_file(CONFIG_PATH, "Model config file")

THRESHOLD = float(model_config.get("threshold", 0.4))
MODEL_NAME = model_config.get("model_name", "XGBoost Dropout Detection")
MODEL_VERSION = model_config.get("version", "V5")
FEATURE_NAMES = list(getattr(model, "feature_names_in_", []))


app = FastAPI(title="Dropout Risk Prediction API")


def get_risk_level(probability_percent: float) -> str:
    if probability_percent >= 70:
        return "High"
    if probability_percent >= 40:
        return "Medium"
    return "Low"


@app.get("/")
def root():
    return {
        "message": "Dropout Risk Prediction API is running",
        "model_version": MODEL_VERSION,
        "threshold": THRESHOLD,
        "features": len(FEATURE_NAMES),
    }


@app.post("/predict")
def predict(student: dict[str, Any]):
    if not FEATURE_NAMES:
        raise HTTPException(
            status_code=500,
            detail="The loaded model does not expose feature_names_in_.",
        )

    missing_features = [feature for feature in FEATURE_NAMES if feature not in student]

    if missing_features:
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Missing required model features.",
                "missing_features": missing_features,
            },
        )

    try:
        input_data = pd.DataFrame([{feature: student.get(feature) for feature in FEATURE_NAMES}])

        dropout_probability = float(model.predict_proba(input_data)[0][1])
        dropout_probability = round(dropout_probability, 4)
        dropout_probability_percent = round(dropout_probability * 100, 2)
        is_dropout_risk = dropout_probability >= THRESHOLD
        prediction_label = "Dropout" if is_dropout_risk else "Not Dropout"
        risk_level = get_risk_level(dropout_probability_percent)
    except Exception as exc:
        raise HTTPException(
            status_code=500,
            detail=f"Prediction failed: {exc}",
        ) from exc

    return {
        "prediction_label": prediction_label,
        "is_dropout_risk": is_dropout_risk,
        "dropout_probability": dropout_probability,
        "dropout_probability_percent": dropout_probability_percent,
        "threshold": THRESHOLD,
        "risk_level": risk_level,
        "model_name": MODEL_NAME,
        "model_version": MODEL_VERSION,
    }
