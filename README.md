# Gym Community WordPress Website

WordPress-project voor een gym/fitness community met een custom thema, custom plugin en een lokale Docker-ontwikkelomgeving.

## Stack

- WordPress 6.9.4
- PHP 8.2 (Docker)
- MySQL 8.0
- phpMyAdmin
- Custom theme: `wp-content/themes/gym-community-theme`
- Custom plugin: `wp-content/plugins/gym-community-plugin`

## Lokale start met Docker

1. Start Docker Desktop.
2. Ga in een terminal naar `C:\laragon\www\Gym_community`.
3. Start de omgeving:

```bash
docker compose up -d --build
```

De applicatie is daarna beschikbaar op:

- WordPress: `http://localhost:8080`
- WordPress admin: `http://localhost:8080/wp-admin`
- phpMyAdmin: `http://localhost:8081`
- MySQL: `localhost:3307`

## Wat de Docker-migratie doet

- Draait exact de WordPress-code uit deze repository.
- Gebruikt een Docker-specifieke `wp-config.php`.
- Importeert automatisch `docker/mysql-init/gym_community_backup.sql` bij een lege database.
- Zet de oude Laragon-URL `http://localhost/gym_community` automatisch om naar `http://localhost:8080`.

## Belangrijke commands

```bash
docker compose up -d --build
docker compose logs -f
docker compose down
docker compose down -v
```

Gebruik `docker compose down -v` alleen als je de database opnieuw vanaf de SQL-dump wilt laten opbouwen.

## Documentatie

- Quick start: `DOCKER-README.md`
- Uitgebreide setup: `docs/DOCKER-SETUP.md`
- Projectstatus: `PROJECT-STATUS.md`

## Opmerking

De bestaande Laragon-configuratie blijft in de repository staan, maar de Docker-omgeving gebruikt eigen varianten van `wp-config.php` en `.htaccess` zodat de site op rootniveau via poort `8080` werkt.
