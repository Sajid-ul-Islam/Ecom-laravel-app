#!/usr/bin/env bash
#
# Convenience wrapper for the Deen Commerce docker compose stack.
#
# Usage:
#   ./dc.sh <command> [args...]
#
# Examples:
#   ./dc.sh up                 # build + start the whole stack
#   ./dc.sh logs               # follow app logs
#   ./dc.sh artisan key:generate
#   ./dc.sh composer require foo/bar
#   ./dc.sh npm run production
#
# A thin Makefile delegates to this script for teammates who use make.

set -euo pipefail

# On Windows the docker CLI may not be on PATH; fall back to Docker Desktop's binary.
if ! command -v docker >/dev/null 2>&1; then
  if [ -x "/c/Program Files/Docker/Docker/resources/bin/docker.exe" ]; then
    export PATH="/c/Program Files/Docker/Docker/resources/bin:$PATH"
  else
    echo "docker not found. Install Docker Desktop and try again." >&2
    exit 1
  fi
fi

COMPOSE=(docker compose)

usage() {
  sed -n '2,12p' "$0" | sed 's/^# \{0,1\}//'
  cat <<'EOF'

Commands:
  up            Build images and start the stack in the background
  down          Stop and remove containers (keeps MySQL/Redis data)
  restart       Stop and start again (down + up)
  destroy       Stop everything and DELETE all data volumes (mysql, redis, vendor)
  build         Rebuild images (use after Dockerfile/composer.json changes)
  logs [svc]    Follow logs; optionally for one service (app, nginx, mysql, redis, queue, node)
  ps            List containers and their status/health
  health        Show healthcheck status for all services
  shell         Open a bash shell in the app container
  artisan ...   Run php artisan in the app container, e.g. ./dc.sh artisan route:list
  composer ...  Run composer in the app container, e.g. ./dc.sh composer require foo/bar
  npm ...       Run npm in the node container, e.g. ./dc.sh npm run production
  migrate       Run pending database migrations
  test          Run the PHPUnit test suite in the app container
  sync          Trigger a WooCommerce sync (php artisan sync:woocommerce --type=all)
EOF
}

require_stack() {
  if ! "${COMPOSE[@]}" ps >/dev/null 2>&1; then
    echo "Stack is not running. Start it with: ./dc.sh up" >&2
    exit 1
  fi
}

case "${1:-help}" in
  up)
    "${COMPOSE[@]}" up -d --build
    ;;
  down)
    "${COMPOSE[@]}" down
    ;;
  restart)
    "${COMPOSE[@]}" down
    "${COMPOSE[@]}" up -d --build
    ;;
  destroy)
    echo "WARNING: this removes ALL docker volumes (database, redis, vendor)." >&2
    read -r -p "Type 'destroy' to confirm: " confirm
    [ "$confirm" = "destroy" ] || { echo "Aborted."; exit 1; }
    "${COMPOSE[@]}" down -v --remove-orphans
    ;;
  build)
    "${COMPOSE[@]}" build "$@"
    ;;
  logs)
    shift
    "${COMPOSE[@]}" logs -f --tail=100 "$@"
    ;;
  ps)
    "${COMPOSE[@]}" ps
    ;;
  health)
    "${COMPOSE[@]}" ps --format "table {{.Name}}\t{{.Status}}"
    ;;
  shell)
    require_stack
    "${COMPOSE[@]}" exec app bash
    ;;
  artisan)
    shift
    require_stack
    "${COMPOSE[@]}" exec -T app php artisan "$@"
    ;;
  composer)
    shift
    require_stack
    "${COMPOSE[@]}" exec -T app composer "$@"
    ;;
  npm)
    shift
    require_stack
    "${COMPOSE[@]}" exec -T node npm "$@"
    ;;
  migrate)
    require_stack
    "${COMPOSE[@]}" exec -T app php artisan migrate --force
    ;;
  test)
    require_stack
    "${COMPOSE[@]}" exec -T app ./vendor/bin/phpunit
    ;;
  sync)
    require_stack
    "${COMPOSE[@]}" exec -T app php artisan sync:woocommerce --type=all
    ;;
  help|-h|--help)
    usage
    ;;
  *)
    echo "Unknown command: $1" >&2
    usage
    exit 1
    ;;
esac
