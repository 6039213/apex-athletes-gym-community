# Gym Community WordPress - Docker Deployment

## Quick Start

### Vereisten
- Docker Desktop geïnstalleerd
- Minimaal 4GB RAM beschikbaar
- Poorten 8080, 8081, 3307 vrij

### Start de applicatie

```bash
# Navigeer naar project folder
cd C:\laragon\www\Gym_community

# Start alle containers
docker-compose up -d

# Bekijk logs
docker-compose logs -f
```

### Toegang tot de applicatie

- **WordPress Website:** http://localhost:8080
- **WordPress Admin:** http://localhost:8080/wp-admin
- **phpMyAdmin:** http://localhost:8081
- **MySQL Database:** localhost:3307

### Login Gegevens

**Database (phpMyAdmin):**
- Gebruiker: `wordpress`
- Wachtwoord: `wordpress`
- Database: `Apex_Athletes`

**WordPress Admin:**
- Gebruik je bestaande admin credentials

## Containers

De applicatie bestaat uit 3 containers:

1. **gym_community_wordpress** - WordPress applicatie
2. **gym_community_db** - MySQL 8.0 database
3. **gym_community_phpmyadmin** - Database beheer

## Handige Commands

```bash
# Stop containers
docker-compose down

# Herstart containers
docker-compose restart

# Bekijk status
docker-compose ps

# Bekijk logs
docker-compose logs wordpress
docker-compose logs db

# Database backup
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > backup.sql

# Database restore
docker exec -i gym_community_db mysql -u wordpress -pwordpress Apex_Athletes < backup.sql
```

## Troubleshooting

### Poort al in gebruik
Wijzig poorten in `docker-compose.yml`:
- WordPress: `8080` → `8082`
- phpMyAdmin: `8081` → `8083`
- MySQL: `3307` → `3308`

### WordPress geeft 404 errors
1. Ga naar http://localhost:8080/wp-admin
2. Ga naar Settings > Permalinks
3. Klik "Save Changes"

### Database is leeg
```bash
# Importeer backup opnieuw
docker exec -i gym_community_db mysql -u wordpress -pwordpress Apex_Athletes < docker/mysql-init/gym_community_backup.sql
```

## Volledige Documentatie

Zie `docs/DOCKER-SETUP.md` voor uitgebreide documentatie.

## Project Structuur

```
Gym_community/
├── docker-compose.yml          # Docker configuratie
├── Dockerfile                  # WordPress container configuratie
├── wp-config-docker.php        # WordPress config voor Docker
├── docker/
│   └── mysql-init/
│       └── gym_community_backup.sql  # Database backup
├── wp-content/                 # WordPress content (gemount als volume)
│   ├── themes/
│   │   └── gym-community-theme/
│   └── plugins/
│       └── gym-community-plugin/
└── docs/
    └── DOCKER-SETUP.md         # Uitgebreide documentatie
```

## Inleveren (Challenge 3a)

### Wat inleveren:

1. **ZIP bestand** van hele project:
   ```bash
   # Maak ZIP (zonder node_modules, .git, etc.)
   # Gebruik Windows Explorer: Rechtermuisknop > Send to > Compressed folder
   ```

2. **Database export**:
   ```bash
   # Exporteer database
   docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > Apex_Athletes_export.sql
   ```

### Inleveren in Teams:
- `Gym_Community.zip` (hele project)
- `Apex_Athletes_export.sql` (database)

## Support

Voor vragen of problemen, zie de uitgebreide documentatie in `docs/DOCKER-SETUP.md`.

---

**Project:** DevSkills WordPress - Gym Community  
**Datum:** 27 Maart 2026  
**Challenge:** 3a - Docker Migratie
