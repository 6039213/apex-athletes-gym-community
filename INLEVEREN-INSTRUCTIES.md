# Inleveren Challenge 3a - Docker Migratie

## Deadline
**Vrijdag - 90 minuten**

## Wat inleveren in Teams

### 1. ZIP Bestand van Project

**Bestandsnaam:** `Gym_Community_Docker.zip`

**Inhoud:**
- Alle project bestanden
- Docker configuratie (docker-compose.yml, Dockerfile)
- WordPress thema en plugin
- Documentatie
- Scripts

**Wat NIET mee in ZIP:**
- `node_modules/` (indien aanwezig)
- `.git/` folder
- `wp-content/uploads/` (grote bestanden)
- Log bestanden (*.log)

**ZIP maken:**
1. Sluit alle programma's die bestanden gebruiken
2. Ga naar `C:\laragon\www\`
3. Rechtermuisknop op `Gym_community` folder
4. Kies "Send to" > "Compressed (zipped) folder"
5. Hernoem naar `Gym_Community_Docker.zip`

### 2. Database Export

**Bestandsnaam:** `Apex_Athletes_export.sql`

**Exporteren via Laragon:**
```bash
# Optie 1: Via batch script
Dubbelklik op: EXPORT-DATABASE.bat

# Optie 2: Handmatig via command line
C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe -u root Apex_Athletes > Apex_Athletes_export.sql
```

**Exporteren via Docker (als Docker draait):**
```bash
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > Apex_Athletes_export.sql
```

**Exporteren via phpMyAdmin:**
1. Open http://localhost/phpmyadmin (Laragon) of http://localhost:8081 (Docker)
2. Login (root / geen wachtwoord voor Laragon)
3. Selecteer database "Apex_Athletes"
4. Klik op "Export" tab
5. Kies "Quick" export method
6. Format: SQL
7. Klik "Go"
8. Sla op als `Apex_Athletes_export.sql`

## Checklist voor Inleveren

- [ ] Database geëxporteerd naar `Apex_Athletes_export.sql`
- [ ] Project als ZIP: `Gym_Community_Docker.zip`
- [ ] ZIP bevat docker-compose.yml
- [ ] ZIP bevat Dockerfile
- [ ] ZIP bevat wp-config-docker.php
- [ ] ZIP bevat docker/mysql-init/gym_community_backup.sql
- [ ] ZIP bevat docs/DOCKER-SETUP.md
- [ ] ZIP bevat custom thema
- [ ] ZIP bevat custom plugin
- [ ] Database export is niet leeg (controleer bestandsgrootte > 1MB)
- [ ] Beide bestanden geüpload naar Teams

## Verificatie

### Controleer ZIP Inhoud

Pak de ZIP uit in een test folder en controleer of deze bestanden aanwezig zijn:

```
Gym_community/
├── docker-compose.yml          ✓
├── Dockerfile                  ✓
├── wp-config-docker.php        ✓
├── DOCKER-README.md            ✓
├── START-DOCKER.bat            ✓
├── docker/
│   └── mysql-init/
│       └── gym_community_backup.sql  ✓
├── wp-content/
│   ├── themes/
│   │   └── gym-community-theme/      ✓
│   └── plugins/
│       └── gym-community-plugin/     ✓
└── docs/
    ├── DOCKER-SETUP.md         ✓
    ├── gebruikershandleiding.md ✓
    └── testing-checklist.md    ✓
```

### Controleer Database Export

```bash
# Bestandsgrootte moet > 1MB zijn
dir Apex_Athletes_export.sql

# Controleer of bestand SQL bevat (open in Notepad)
# Eerste regels moeten zijn:
-- MySQL dump ...
-- Host: localhost ...
```

## Docker Testen (Optioneel maar Aanbevolen)

### Vereisten
- Docker Desktop geïnstalleerd en actief

### Test Procedure

1. **Start Docker Desktop**
   - Wacht tot volledig opgestart
   - Groen lampje in systray

2. **Start containers**
   ```bash
   cd C:\laragon\www\Gym_community
   docker-compose up -d
   ```

3. **Wacht 30 seconden**
   - Containers moeten opstarten
   - Database moet initialiseren

4. **Controleer status**
   ```bash
   docker-compose ps
   ```
   Alle containers moeten "Up" status hebben

5. **Test WordPress**
   - Open http://localhost:8080
   - Moet WordPress homepage tonen
   - Thema moet zichtbaar zijn

6. **Test Admin**
   - Open http://localhost:8080/wp-admin
   - Login met je credentials
   - Controleer of thema actief is
   - Controleer of plugin actief is

7. **Test Database**
   - Open http://localhost:8081 (phpMyAdmin)
   - Login: wordpress / wordpress
   - Database "Apex_Athletes" moet zichtbaar zijn
   - Tabellen moeten data bevatten

8. **Stop containers**
   ```bash
   docker-compose down
   ```

## Troubleshooting

### Docker Desktop start niet
- Herstart computer
- Controleer of Hyper-V enabled is (Windows)
- Controleer of WSL2 geïnstalleerd is

### Poort 8080 al in gebruik
- Stop Laragon Apache
- Of wijzig poort in docker-compose.yml naar 8082

### Database import mislukt
- Controleer of backup bestand bestaat: `docker/mysql-init/gym_community_backup.sql`
- Controleer bestandsgrootte (moet > 1MB zijn)
- Bekijk logs: `docker-compose logs db`

### WordPress geeft errors
- Wacht 1-2 minuten na opstarten
- Herstart containers: `docker-compose restart`
- Check logs: `docker-compose logs wordpress`

### Kan niet inloggen
- Gebruik je Laragon admin credentials
- Reset wachtwoord via phpMyAdmin indien nodig

## Inleveren in Teams

1. **Ga naar Teams**
2. **Zoek Challenge 3a opdracht**
3. **Upload beide bestanden:**
   - `Gym_Community_Docker.zip`
   - `Apex_Athletes_export.sql`
4. **Voeg opmerking toe (optioneel):**
   ```
   Docker migratie voltooid.
   - WordPress draait op poort 8080
   - Database: Apex_Athletes
   - Thema en plugin geïncludeerd
   - Volledige documentatie in docs/
   ```
5. **Klik "Turn in" / "Inleveren"**

## Na Inleveren

### Bewaar Backup
Kopieer beide bestanden naar een veilige locatie:
- Externe harde schijf
- Cloud storage (OneDrive, Google Drive)
- USB stick

### Documenteer
Maak notities over:
- Wat werkte goed
- Wat was lastig
- Wat zou je anders doen
- Vragen voor volgende sessie

## Volgende Stap (Vrijdag)

Na inleveren gaan we verder met:
- Aanpassingen in de applicatie
- Extra features toevoegen
- Testing en optimalisatie
- Deployment naar productie

## Hulp Nodig?

### Documentatie
- `DOCKER-README.md` - Quick start
- `docs/DOCKER-SETUP.md` - Uitgebreide handleiding
- `docs/gebruikershandleiding.md` - WordPress beheer

### Veelgestelde Vragen

**Q: Moet Docker draaien voor inleveren?**
A: Nee, maar het is wel aanbevolen om te testen.

**Q: Wat als database export te groot is (>100MB)?**
A: Comprimeer naar .zip: `Apex_Athletes_export.sql.zip`

**Q: Kan ik meerdere keren inleveren?**
A: Ja, laatste versie telt.

**Q: Wat als ik geen Docker Desktop heb?**
A: Lever de bestanden in zoals ze zijn. Docker kan later getest worden.

**Q: Moet alles werken?**
A: Ja, test minimaal of WordPress opstart in Docker.

## Tijdsplanning (90 minuten)

- **0-10 min:** Database exporteren
- **10-20 min:** Project als ZIP maken
- **20-30 min:** Bestanden controleren
- **30-60 min:** Docker testen (indien mogelijk)
- **60-75 min:** Eventuele fixes
- **75-85 min:** Finale controle
- **85-90 min:** Inleveren in Teams

## Succes!

Je hebt een complete WordPress applicatie met custom thema en plugin ontwikkeld en gemigreerd naar Docker. Dat is een professionele prestatie!

---

**Challenge:** 3a - Docker Migratie  
**Datum:** 27 Maart 2026  
**Tijd:** 90 minuten  
**Inleveren:** Teams
