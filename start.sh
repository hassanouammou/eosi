#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

# Garder la compatibilité des URLs existantes: /eosi/...
# En Railway, le code est dans le dossier courant.
if [ ! -e "eosi" ]; then
  ln -s . eosi
fi

if ! php -m | grep -q "pdo_pgsql"; then
  echo "Erreur: extension PHP pdo_pgsql manquante."
  exit 1
fi

exec php -S 0.0.0.0:${PORT} -t .
