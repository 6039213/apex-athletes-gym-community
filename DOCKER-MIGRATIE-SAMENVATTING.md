# Docker Migratie Samenvatting

## Opgeleverd

- Een Docker Compose-omgeving met:
  - `wordpress`
  - `db`
  - `phpmyadmin`
- Een vaste WordPress runtime op `6.9.4`, gelijk aan de code in deze repository.
- Een Docker-specifieke `wp-config.php`.
- Een Docker-specifieke `.htaccess` voor root-routing op `http://localhost:8080`.
- Automatische database-import vanaf `docker/mysql-init/gym_community_backup.sql`.
- Automatische omzetting van de oude Laragon-URL naar `http://localhost:8080`.

## Waarom deze versie correcter is

- De vorige setup mountte alleen `wp-content` en gebruikte een losse `wordpress:latest` runtime.
- Daardoor draaide Docker niet gegarandeerd dezelfde WordPress-core en root-configuratie als Laragon.
- De nieuwe setup mount de volledige applicatiestaat en gebruikt dezelfde core-versie als in `wp-includes/version.php`.

## Gebruik

```bash
docker compose up -d --build
docker compose down
docker compose down -v
```

## Volgende stap

Na deze migratiestap kan de applicatie in Docker verder aangepast en getest worden zonder afhankelijkheid van Laragon.
