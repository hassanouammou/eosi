#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

sed "s/__PORT__/${PORT}/g" /etc/apache2/sites-available/000-default.template.conf > /etc/apache2/sites-available/000-default.conf
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf

exec "$@"
