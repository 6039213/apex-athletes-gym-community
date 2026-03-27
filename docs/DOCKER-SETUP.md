# Docker Setup - Gym Community WordPress

## Overzicht

Dit document beschrijft hoe je de Gym Community WordPress applicatie draait in een Docker omgeving.

## Vereisten

- Docker Desktop geïnstalleerd (Windows/Mac/Linux)
- Docker Compose (meestal inbegrepen bij Docker Desktop)
- Minimaal 4GB RAM beschikbaar voor Docker
- Poorten 8080, 8081, 3307 vrij

## Docker Architectuur

De applicatie bestaat uit 3 containers:

1. **wordpress** - WordPress applicatie (poort 8080)
2. **db** - MySQL 8.0 database (poort 3307)
3. **phpmyadmin** - Database beheer interface (poort 8081)

## Snelstart

### 1. Start de containers

```bash
cd C:\laragon\www\Gym_community
docker-compose up -d
```

### 2. Wacht tot containers klaar zijn (±2 minuten)

```bash
docker-compose logs -f
```

Druk op Ctrl+C om te stoppen met logs bekijken.

### 3. Open de website

- **WordPress:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081
- **Database:** localhost:3307

### 4. Login gegevens

**WordPress Admin:**
- URL: http://localhost:8080/wp-admin
- Gebruikersnaam: (je bestaande admin gebruiker)
- Wachtwoord: (je bestaande wachtwoord)

**Database (phpMyAdmin):**
- Server: db
- Gebruiker: wordpress
- Wachtwoord: wordpress
- Database: Apex_Athletes

**Database (externe connectie):**
- Host: localhost
- Poort: 3307
- Gebruiker: wordpress
- Wachtwoord: wordpress
- Database: Apex_Athletes

## Gedetailleerde Instructies

### Containers Starten

```bash
# Start alle containers in background
docker-compose up -d

# Start en bekijk logs
docker-compose up

# Alleen specifieke service starten
docker-compose up -d wordpress
```

### Containers Stoppen

```bash
# Stop alle containers
docker-compose down

# Stop en verwijder volumes (LET OP: verwijdert database!)
docker-compose down -v
```

### Container Status Bekijken

```bash
# Lijst van draaiende containers
docker-compose ps

# Logs bekijken
docker-compose logs

# Logs van specifieke service
docker-compose logs wordpress

# Live logs volgen
docker-compose logs -f
```

### In Container Gaan

```bash
# WordPress container
docker exec -it gym_community_wordpress bash

# Database container
docker exec -it gym_community_db bash

# MySQL CLI in database container
docker exec -it gym_community_db mysql -u wordpress -pwordpress Apex_Athletes
```

## Database Beheer

### Database Importeren

De database wordt automatisch geïmporteerd bij eerste start vanuit:
`docker/mysql-init/gym_community_backup.sql`

### Handmatige Database Import

```bash
# Via phpMyAdmin
1. Ga naar http://localhost:8081
2. Login met: wordpress / wordpress
3. Selecteer database "Apex_Athletes"
4. Klik op "Import"
5. Upload SQL bestand
6. Klik "Go"

# Via command line
docker exec -i gym_community_db mysql -u wordpress -pwordpress Apex_Athletes < docker/mysql-init/gym_community_backup.sql
```

### Database Exporteren

```bash
# Export naar bestand
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > backup_$(date +%Y%m%d).sql

# Of via phpMyAdmin
1. Ga naar http://localhost:8081
2. Selecteer database "Apex_Athletes"
3. Klik op "Export"
4. Kies "Quick" of "Custom"
5. Klik "Go"
```

## Volumes

### Data Persistentie

De volgende data wordt bewaard in Docker volumes:

- **db_data** - MySQL database bestanden
- **./wp-content** - WordPress content (thema's, plugins, uploads)

### Volume Beheer

```bash
# Lijst volumes
docker volume ls

# Volume details
docker volume inspect gym_community_db_data

# Volume verwijderen (LET OP: verwijdert alle data!)
docker volume rm gym_community_db_data
```

## Netwerk

De containers communiceren via een bridge netwerk genaamd `gym_network`.

```bash
# Netwerk details
docker network inspect gym_community_gym_network
```

## Troubleshooting

### Poort al in gebruik

**Probleem:** Poort 8080, 8081 of 3307 is al in gebruik

**Oplossing:** Wijzig poorten in `docker-compose.yml`:

```yaml
wordpress:
  ports:
    - "8082:80"  # Wijzig 8080 naar 8082

phpmyadmin:
  ports:
    - "8083:80"  # Wijzig 8081 naar 8083

db:
  ports:
    - "3308:3306"  # Wijzig 3307 naar 3308
```

### Database connectie mislukt

**Probleem:** WordPress kan geen verbinding maken met database

**Oplossing:**

```bash
# Check of database container draait
docker-compose ps

# Bekijk database logs
docker-compose logs db

# Herstart containers
docker-compose restart
```

### WordPress geeft 404 errors

**Probleem:** Pagina's geven 404 errors

**Oplossing:**

1. Ga naar http://localhost:8080/wp-admin
2. Ga naar Settings > Permalinks
3. Klik "Save Changes" (zonder iets te wijzigen)

### Thema of plugin niet zichtbaar

**Probleem:** Custom thema of plugin wordt niet getoond

**Oplossing:**

```bash
# Check of wp-content correct gemount is
docker-compose exec wordpress ls -la /var/www/html/wp-content/themes
docker-compose exec wordpress ls -la /var/www/html/wp-content/plugins

# Check file permissions
docker-compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content
```

### Database is leeg na herstart

**Probleem:** Database data is verdwenen

**Oorzaak:** Volume is verwijderd met `docker-compose down -v`

**Oplossing:**

```bash
# Importeer backup opnieuw
docker exec -i gym_community_db mysql -u wordpress -pwordpress Apex_Athletes < docker/mysql-init/gym_community_backup.sql
```

### Container start niet

**Probleem:** Container blijft crashen

**Oplossing:**

```bash
# Bekijk logs voor error messages
docker-compose logs

# Verwijder containers en probeer opnieuw
docker-compose down
docker-compose up -d

# Check Docker Desktop resources (RAM, CPU)
```

## URL's Aanpassen

Als je de website op een andere URL wilt draaien:

### 1. Wijzig wp-config-docker.php

```php
define( 'WP_HOME', 'http://jouw-domein.nl' );
define( 'WP_SITEURL', 'http://jouw-domein.nl' );
```

### 2. Update database URLs

```bash
# Via WP-CLI in container
docker-compose exec wordpress wp search-replace 'http://localhost:8080' 'http://jouw-domein.nl' --all-tables

# Of via phpMyAdmin SQL query
UPDATE wp_options SET option_value = replace(option_value, 'http://localhost:8080', 'http://jouw-domein.nl') WHERE option_name = 'home' OR option_name = 'siteurl';
UPDATE wp_posts SET post_content = replace(post_content, 'http://localhost:8080', 'http://jouw-domein.nl');
UPDATE wp_posts SET guid = replace(guid, 'http://localhost:8080', 'http://jouw-domein.nl');
UPDATE wp_postmeta SET meta_value = replace(meta_value, 'http://localhost:8080', 'http://jouw-domein.nl');
```

## Performance Optimalisatie

### PHP Memory Limit Verhogen

In `docker-compose.yml`:

```yaml
wordpress:
  environment:
    WORDPRESS_CONFIG_EXTRA: |
      define('WP_MEMORY_LIMIT', '512M');
```

### Upload Limit Verhogen

Maak `docker/php.ini`:

```ini
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
```

Voeg toe aan `docker-compose.yml`:

```yaml
wordpress:
  volumes:
    - ./docker/php.ini:/usr/local/etc/php/conf.d/uploads.ini
```

## Backup Strategie

### Automatische Backup Script

Maak `docker/backup.sh`:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="./backups"

mkdir -p $BACKUP_DIR

# Database backup
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > $BACKUP_DIR/db_$DATE.sql

# WordPress files backup
tar -czf $BACKUP_DIR/wp-content_$DATE.tar.gz wp-content/

echo "Backup completed: $DATE"
```

### Restore van Backup

```bash
# Database restore
docker exec -i gym_community_db mysql -u wordpress -pwordpress Apex_Athletes < backups/db_20260327_090000.sql

# Files restore
tar -xzf backups/wp-content_20260327_090000.tar.gz
```

## Productie Deployment

### Aanpassingen voor Productie

1. **Wijzig wachtwoorden** in `docker-compose.yml`
2. **Disable debugging** in `wp-config-docker.php`:
   ```php
   define( 'WP_DEBUG', false );
   define( 'WP_DEBUG_LOG', false );
   define( 'WP_DEBUG_DISPLAY', false );
   ```
3. **Gebruik HTTPS** met reverse proxy (nginx/traefik)
4. **Configureer backups** (dagelijks)
5. **Monitor resources** (CPU, RAM, disk)

### Met SSL/HTTPS (nginx reverse proxy)

Maak `docker-compose.prod.yml`:

```yaml
version: '3.8'

services:
  nginx:
    image: nginx:latest
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - wordpress
```

## Handige Commands

```bash
# Containers herstarten
docker-compose restart

# Specifieke container herstarten
docker-compose restart wordpress

# Containers rebuilden
docker-compose up -d --build

# Logs van laatste 100 regels
docker-compose logs --tail=100

# Disk usage bekijken
docker system df

# Cleanup (verwijder ongebruikte images/containers)
docker system prune -a

# WordPress CLI gebruiken
docker-compose exec wordpress wp --info
docker-compose exec wordpress wp plugin list
docker-compose exec wordpress wp theme list
```

## Development Workflow

### 1. Start ontwikkelomgeving

```bash
docker-compose up -d
```

### 2. Maak wijzigingen in code

Wijzigingen in `wp-content/` worden direct zichtbaar (volume mount).

### 3. Test wijzigingen

Open http://localhost:8080

### 4. Commit naar Git

```bash
git add .
git commit -m "feat: nieuwe feature"
git push
```

### 5. Stop containers (optioneel)

```bash
docker-compose down
```

## Migratie van Laragon naar Docker

### Stappen

1. ✅ **Database exporteren** - Gedaan via mysqldump
2. ✅ **Docker configuratie** - docker-compose.yml aangemaakt
3. ✅ **wp-config aanpassen** - wp-config-docker.php aangemaakt
4. ⏳ **Containers starten** - `docker-compose up -d`
5. ⏳ **Database importeren** - Automatisch bij eerste start
6. ⏳ **Testen** - Controleer alle functionaliteit

### Verificatie Checklist

- [ ] WordPress opent op http://localhost:8080
- [ ] Kan inloggen op wp-admin
- [ ] Custom thema is actief
- [ ] Custom plugin is actief
- [ ] Database data is aanwezig
- [ ] Activiteiten tonen correct
- [ ] Reviews tonen correct
- [ ] Inschrijfsysteem werkt
- [ ] Shortcodes werken
- [ ] Uploads folder werkt

## Support & Resources

- **Docker Documentatie:** https://docs.docker.com/
- **Docker Compose:** https://docs.docker.com/compose/
- **WordPress Docker:** https://hub.docker.com/_/wordpress
- **MySQL Docker:** https://hub.docker.com/_/mysql

## Veelgestelde Vragen

**Q: Kan ik Laragon en Docker tegelijk gebruiken?**  
A: Ja, maar zorg dat ze verschillende poorten gebruiken.

**Q: Hoe update ik WordPress in Docker?**  
A: `docker-compose pull wordpress && docker-compose up -d`

**Q: Waar staat mijn data opgeslagen?**  
A: In Docker volumes. Zie `docker volume ls`

**Q: Kan ik dit deployen naar een server?**  
A: Ja, kopieer de bestanden en draai `docker-compose up -d` op de server.

**Q: Hoe maak ik een backup?**  
A: Zie sectie "Backup Strategie" hierboven.

---

**Versie:** 1.0  
**Laatst bijgewerkt:** 27 Maart 2026  
**Auteur:** DevSkills WordPress Project
