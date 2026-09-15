# Guide Docker & Déploiement CampusFix

Conteneurisation complète du projet CampusFix avec Docker & Docker Compose :
- `docker/Dockerfile` : Image PHP 8.2-FPM + MySQL Client + Composer + Node/NPM.
- `docker/docker-compose.yml` : Multi-conteneurs (App, Nginx, MySQL 8.0, PhpMyAdmin).
- `docker/package.json` : Dépendances Tailwind / Alpine / Vite.
- `docker/nginx/default.conf` : Serveur Nginx.
- `docker/php/local.ini` : Configuration PHP.

## Lancer le projet :
```bash
docker compose up -d --build
docker compose exec app php artisan migrate:fresh --seed
```
Application disponible sur http://localhost:8000
PhpMyAdmin disponible sur http://localhost:8080
