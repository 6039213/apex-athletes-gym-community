# Quick Deployment Checklist - Docker naar Live

## 🎯 Snelle Samenvatting

**Doel:** WordPress site van Docker (lokaal) naar SPL Sites (live) deployen

**Tijd:** ~30 minuten

---

## ✅ Pre-Deployment Checklist

- [ ] Docker containers draaien lokaal
- [ ] Website werkt op http://localhost:8080
- [ ] Alle wijzigingen getest in Docker
- [ ] Database backup gemaakt
- [ ] FileZilla geïnstalleerd

---

## 📤 Wat WEL Uploaden (via FTP)

### 1. WordPress Config (VERPLICHT)
```
wp-config-auto.php → hernoem naar wp-config.php
wp-config-production.php
```

### 2. Custom Thema (VERPLICHT)
```
wp-content/themes/gym-community-theme/ (hele folder)
```

### 3. Custom Plugin (VERPLICHT)
```
wp-content/plugins/gym-community-plugin/ (hele folder)
```

### 4. Uploads (OPTIONEEL)
```
wp-content/uploads/ (als je afbeeldingen hebt)
```

### 5. WordPress Core (EENMALIG)
```
wp-admin/
wp-includes/
index.php
wp-*.php bestanden
(alleen als WordPress nog niet op server staat)
```

---

## ❌ Wat NIET Uploaden

**Docker Bestanden:**
- ❌ docker-compose.yml
- ❌ Dockerfile
- ❌ .dockerignore
- ❌ .docker-env
- ❌ docker/ folder

**Development Bestanden:**
- ❌ .git/ folder
- ❌ docs/ folder
- ❌ README.md
- ❌ *.sql bestanden
- ❌ *.bat scripts
- ❌ wp-config-local.php
- ❌ wp-config-docker.php
- ❌ node_modules/

---

## 🚀 Deployment Stappen

### Stap 1: Database Exporteren (2 min)

```bash
cd C:\laragon\www\Gym_community
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > deployment_backup.sql
```

### Stap 2: FTP Upload (10 min)

**FileZilla Instellingen:**
- Host: `st1738846938.splsites.nl`
- User: `st1738846938`
- Pass: `R5IFHm9dw7k6W6r`
- Port: `21`

**Upload deze bestanden:**
1. `wp-config-auto.php` → hernoem naar `wp-config.php`
2. `wp-config-production.php`
3. `wp-content/themes/gym-community-theme/`
4. `wp-content/plugins/gym-community-plugin/`

### Stap 3: Database Importeren (5 min)

1. Open: https://st1738846938.splsites.nl/phpmyadmin
2. Login: st1738846938 / R5IFHm9dw7k6W6r
3. Selecteer database: `st1738846938`
4. Import: `deployment_backup.sql`

### Stap 4: URLs Updaten (2 min)

Voer uit in phpMyAdmin (SQL tab):

```sql
UPDATE wp_options 
SET option_value = 'https://st1738846938.splsites.nl' 
WHERE option_name = 'home' OR option_name = 'siteurl';

UPDATE wp_posts 
SET post_content = REPLACE(post_content, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

UPDATE wp_posts 
SET guid = REPLACE(guid, 'http://localhost:8080', 'https://st1738846938.splsites.nl');
```

### Stap 5: WordPress Activeren (5 min)

1. Open: https://st1738846938.splsites.nl/wp-admin
2. Activeer thema: Appearance > Themes > Gym Community Theme
3. Activeer plugin: Plugins > Gym Community Plugin
4. Update permalinks: Settings > Permalinks > Save Changes

### Stap 6: Testen (5 min)

- [ ] Homepage laadt
- [ ] Geen database errors
- [ ] Thema zichtbaar
- [ ] Plugin actief
- [ ] Activiteiten pagina werkt
- [ ] Reviews pagina werkt
- [ ] Inschrijfsysteem werkt

---

## 🔄 Automatische Omgeving Detectie

**Hoe het werkt:**

`wp-config.php` (hernoemd van wp-config-auto.php) detecteert automatisch:

**Lokaal (Docker):**
- Detecteert: `localhost:8080` OF `.docker-env` bestand
- Laadt: `wp-config-local.php`
- Database: `Apex_Athletes` @ `db:3306`

**Live (Productie):**
- Detecteert: `splsites.nl` OF geen Docker markers
- Laadt: `wp-config-production.php`
- Database: `st1738846938` @ `localhost`

**Voordeel:** Geen handmatig config switchen meer!

---

## 🔧 Troubleshooting

| Probleem | Oplossing |
|----------|-----------|
| Database error | Check wp-config-production.php credentials |
| Thema niet zichtbaar | Upload gym-community-theme folder |
| Plugin niet zichtbaar | Upload gym-community-plugin folder |
| 404 errors | Settings > Permalinks > Save |
| Afbeeldingen tonen niet | Voer URL replacement queries uit |

---

## 📋 Post-Deployment Checklist

- [ ] Website bereikbaar op https://st1738846938.splsites.nl/
- [ ] Kan inloggen op wp-admin
- [ ] Thema actief en zichtbaar
- [ ] Plugin actief en werkend
- [ ] Alle pagina's laden correct
- [ ] Geen PHP errors
- [ ] Geen database errors
- [ ] URLs correct (geen localhost meer)
- [ ] Afbeeldingen tonen
- [ ] Formulieren werken

---

## 🎓 Tips

1. **Test altijd eerst lokaal** in Docker
2. **Maak backup** voor je deploy
3. **Upload alleen gewijzigde bestanden** bij updates
4. **Documenteer wijzigingen**
5. **Gebruik .ftpignore** als referentie

---

## 📞 Hulp

Zie `DEPLOYMENT-GUIDE-FTP.md` voor uitgebreide instructies.

---

**Succes!** 🚀
