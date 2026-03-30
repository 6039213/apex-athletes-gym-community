# Docker Setup - Gym Community WordPress

## Overzicht

Deze Docker-omgeving is opgezet om de huidige WordPress-state uit deze repository lokaal te draaien zonder Laragon. De setup gebruikt:

- WordPress `6.9.4`
- PHP `8.2`
- MySQL `8.0`
- phpMyAdmin

Belangrijk verschil met de eerdere opzet: de container draait nu de volledige WordPress-code uit deze repository, niet alleen `wp-content`.

## Services

1. `wordpress`
2. `db`
3. `phpmyadmin`

## Poorten

- WordPress: `8080`
- phpMyAdmin: `8081`
- MySQL: `3307`

## Eerste start

### Start de omgeving

```bash
cd C:\laragon\www\Gym_community
docker compose up -d --build
```

### Controleer de status

```bash
docker compose ps
docker compose logs -f
```

### Open de applicatie

- Website: `http://localhost:8080`
- Admin: `http://localhost:8080/wp-admin`
- phpMyAdmin: `http://localhost:8081`

## Hoe de migratie werkt

Bij de eerste start van de `db` service gebeurt het volgende:

1. De database `Apex_Athletes` wordt aangemaakt.
2. `docker/mysql-init/gym_community_backup.sql` wordt geimporteerd.
3. `docker/mysql-init/zz-local-url-update.sql` zet de oude Laragon-URL om:
   - van `http://localhost/gym_community`
   - naar `http://localhost:8080`

Hierdoor sluit de dump direct aan op de Docker-omgeving.

## Welke bestanden Docker overschrijft

Om Laragon en Docker naast elkaar mogelijk te houden, gebruikt Docker eigen varianten van twee rootbestanden:

- `wp-config-docker.php` -> gemount als `wp-config.php`
- `docker/.htaccess` -> gemount als `.htaccess`

De bestaande Laragon-bestanden in de repository blijven dus intact.

## Configuratiebestanden

### `docker-compose.yml`

Regelt:

- containers
- volumes
- netwerken
- poorten
- environment variables

### `Dockerfile`

Gebaseerd op:

- `wordpress:6.9.4-php8.2-apache`

Extra configuratie:

- Apache `mod_rewrite`
- custom Apache conf voor lokale development mount

### `wp-config-docker.php`

Bevat de Docker-runtime instellingen voor:

- database host
- database gebruiker
- debug flags
- memory limit
- `WP_HOME` en `WP_SITEURL`

### `docker/apache/gym-community.conf`

Doet twee dingen:

1. laat `.htaccess` toe voor WordPress rewrites
2. blokkeert toegang tot niet-runtime bestanden zoals `docs`, `docker`, `.git` en SQL dumps

## Database reset

Als je opnieuw vanaf de SQL-dump wilt opstarten:

```bash
docker compose down -v
docker compose up -d --build
```

`down -v` verwijdert het databasevolume. Zonder die stap wordt de import niet opnieuw uitgevoerd.

## Optionele `.env`

Je kunt de standaardpoorten en credentials overschrijven met een eigen `.env` op rootniveau. Gebruik `.env.example` als startpunt.

Voorbeeld:

```env
WORDPRESS_PORT=8080
PHPMYADMIN_PORT=8081
MYSQL_PORT=3307
WP_HOME=http://localhost:8080
WP_SITEURL=http://localhost:8080
```

## Belangrijke commands

```bash
docker compose up -d --build
docker compose ps
docker compose logs -f
docker compose restart
docker compose down
docker compose down -v
```

## Troubleshooting

### 1. Poortconflict

Als `8080`, `8081` of `3307` al in gebruik zijn:

1. maak een `.env`
2. wijzig de poorten
3. start opnieuw met `docker compose up -d --build`

### 2. Oude of verkeerde data

Waarschijnlijk gebruik je nog een bestaand databasevolume.

Voer uit:

```bash
docker compose down -v
docker compose up -d --build
```

### 3. 404 op pagina's

Log in op WordPress admin en sla `Settings > Permalinks` opnieuw op.

### 4. Database connectie faalt

Controleer:

```bash
docker compose logs db
docker compose logs wordpress
```

### 5. Docker draait wel, maar site gebruikt nog Laragon URL's

Reset de database met `docker compose down -v` en start opnieuw. De URL-rewrite wordt alleen uitgevoerd bij een lege database.

## Volgende stap

Na deze migratie kan de WordPress-applicatie in Docker verder aangepast, getest en gedebugd worden. Dat is de juiste basis voor de vervolgstap in de challenge.
