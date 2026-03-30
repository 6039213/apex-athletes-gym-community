# Deployment Guide - Docker naar Live Server (FTP)

## Overzicht

Deze guide helpt je om je WordPress site van Docker (lokaal) naar je live server (SPL Sites) te deployen via FTP.

**Automatische omgeving-detectie:** Je hoeft maar 1 wp-config.php te uploaden die automatisch detecteert of je lokaal of live draait!

---

## 🎯 Wat Je Hebt

### Lokaal (Docker)
- URL: `http://localhost:8080`
- Database: `Apex_Athletes` (Docker container)
- Database User: `wordpress`
- Database Pass: `wordpress`
- Database Host: `db:3306`

### Live (SPL Sites)
- URL: `https://st1738846938.splsites.nl/`
- Database: `st1738846938`
- Database User: `st1738846938`
- Database Pass: `R5IFHm9dw7k6W6r`
- Database Host: `localhost`

---

## 📋 Deployment Stappen

### Stap 1: Voorbereiding (5 minuten)

**1.1 Exporteer Database vanuit Docker**

```bash
# Stop bij je project folder
cd C:\laragon\www\Gym_community

# Exporteer database vanuit Docker container
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > deployment_backup.sql
```

**1.2 Controleer Bestanden**

Zorg dat deze bestanden bestaan:
- ✅ `wp-config-auto.php` (automatische omgeving-detectie)
- ✅ `wp-config-local.php` (Docker config)
- ✅ `wp-config-production.php` (Live server config)
- ✅ `.docker-env` (Docker marker)
- ✅ `deployment_backup.sql` (Database export)

---

### Stap 2: Upload via FTP (15 minuten)

**2.1 Verbind met FileZilla**

```
Host: st1738846938.splsites.nl
Username: st1738846938
Password: R5IFHm9dw7k6W6r
Port: 21
```

**2.2 Upload WordPress Core (EENMALIG)**

Als WordPress nog niet op server staat:
- Upload hele WordPress installatie (wp-admin, wp-includes, index.php, etc.)
- **SKIP** als WordPress al geïnstalleerd is

**2.3 Upload Jouw Custom Bestanden**

Upload deze folders/bestanden:

✅ **MOET UPLOADEN:**

1. **wp-config bestanden:**
   - `wp-config-auto.php` → hernoem naar `wp-config.php` op server
   - `wp-config-production.php` → upload als is
   
2. **Custom Thema:**
   - `wp-content/themes/gym-community-theme/` → hele folder

3. **Custom Plugin:**
   - `wp-content/plugins/gym-community-plugin/` → hele folder

4. **Uploads (optioneel):**
   - `wp-content/uploads/` → als je afbeeldingen hebt

❌ **NIET UPLOADEN:**

- ❌ `docker-compose.yml`
- ❌ `Dockerfile`
- ❌ `.dockerignore`
- ❌ `.docker-env`
- ❌ `docker/` folder
- ❌ `.git/` folder
- ❌ `docs/` folder
- ❌ `*.sql` bestanden
- ❌ `*.bat` scripts
- ❌ `*.sh` scripts
- ❌ `README.md`, `*.txt`
- ❌ `wp-config-local.php`
- ❌ `wp-config-docker.php`
- ❌ `node_modules/`

**2.4 Verificatie**

Controleer op server dat deze bestanden aanwezig zijn:
- `/wp-config.php` (hernoemd van wp-config-auto.php)
- `/wp-config-production.php`
- `/wp-content/themes/gym-community-theme/`
- `/wp-content/plugins/gym-community-plugin/`

---

### Stap 3: Database Importeren (10 minuten)

**3.1 Open phpMyAdmin**

URL: `https://st1738846938.splsites.nl/phpmyadmin`

Login:
- User: `st1738846938`
- Pass: `R5IFHm9dw7k6W6r`

**3.2 Selecteer Database**

- Klik op database `st1738846938` in linker menu

**3.3 Importeer SQL**

1. Klik op "Import" tab
2. Klik "Choose File"
3. Selecteer: `deployment_backup.sql`
4. Scroll naar beneden
5. Klik "Go"
6. Wacht tot import compleet is

**3.4 Update URLs in Database**

Voer deze SQL queries uit (SQL tab):

```sql
-- Update site URL
UPDATE wp_options 
SET option_value = 'https://st1738846938.splsites.nl' 
WHERE option_name = 'home' OR option_name = 'siteurl';

-- Update post content URLs
UPDATE wp_posts 
SET post_content = REPLACE(post_content, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Update post GUIDs
UPDATE wp_posts 
SET guid = REPLACE(guid, 'http://localhost:8080', 'https://st1738846938.splsites.nl');

-- Update post meta
UPDATE wp_postmeta 
SET meta_value = REPLACE(meta_value, 'http://localhost:8080', 'https://st1738846938.splsites.nl');
```

---

### Stap 4: WordPress Configuratie (5 minuten)

**4.1 Test Website**

Open: `https://st1738846938.splsites.nl/`

Je zou WordPress homepage moeten zien (zonder database error!)

**4.2 Login op Admin**

URL: `https://st1738846938.splsites.nl/wp-admin`

Gebruik je bestaande admin credentials

**4.3 Activeer Thema**

1. Ga naar: Appearance > Themes
2. Activeer: "Gym Community Theme"

**4.4 Activeer Plugin**

1. Ga naar: Plugins
2. Activeer: "Gym Community Plugin"

**4.5 Update Permalinks**

1. Ga naar: Settings > Permalinks
2. Klik "Save Changes" (zonder iets te wijzigen)
3. Dit regenereert .htaccess regels

---

## 🔄 Hoe Automatische Omgeving-Detectie Werkt

### wp-config-auto.php (Hoofdbestand)

Dit bestand detecteert automatisch waar je draait:

**Docker (Lokaal):**
- Detecteert: `localhost:8080` in URL
- Of: `.docker-env` bestand bestaat
- Of: Database host is `db:3306`
- Laadt: `wp-config-local.php`

**Productie (Live):**
- Detecteert: `splsites.nl` in URL
- Of: Geen Docker markers
- Laadt: `wp-config-production.php`

### Bestand Structuur

```
WordPress Root/
├── wp-config.php              ← Hernoemd van wp-config-auto.php (UPLOAD)
├── wp-config-production.php   ← Live server config (UPLOAD)
├── wp-config-local.php        ← Docker config (NIET UPLOADEN)
├── .docker-env                ← Docker marker (NIET UPLOADEN)
└── wp-content/
    ├── themes/
    │   └── gym-community-theme/  ← Jouw thema (UPLOAD)
    └── plugins/
        └── gym-community-plugin/ ← Jouw plugin (UPLOAD)
```

---

## 📦 Upload Checklist

### Bestanden die WEL geupload moeten worden:

- [x] `wp-config-auto.php` → hernoem naar `wp-config.php`
- [x] `wp-config-production.php`
- [x] `wp-content/themes/gym-community-theme/` (hele folder)
- [x] `wp-content/plugins/gym-community-plugin/` (hele folder)
- [x] `wp-content/uploads/` (optioneel, als je afbeeldingen hebt)
- [x] WordPress core bestanden (als nog niet aanwezig)

### Bestanden die NIET geupload moeten worden:

- [ ] `docker-compose.yml` ❌
- [ ] `Dockerfile` ❌
- [ ] `.dockerignore` ❌
- [ ] `.docker-env` ❌
- [ ] `docker/` folder ❌
- [ ] `.git/` folder ❌
- [ ] `docs/` folder ❌
- [ ] `*.sql` bestanden ❌
- [ ] `*.bat`, `*.sh` scripts ❌
- [ ] `wp-config-local.php` ❌
- [ ] `wp-config-docker.php` ❌
- [ ] `README.md`, documentatie ❌
- [ ] `node_modules/` ❌

---

## 🔧 Troubleshooting

### Probleem: Database Connection Error

**Oorzaak:** wp-config.php laadt verkeerde configuratie

**Oplossing:**
1. Check of `wp-config-production.php` bestaat op server
2. Check of database credentials correct zijn
3. Test handmatig welke config geladen wordt (zie debug info)

### Probleem: Thema niet zichtbaar

**Oorzaak:** Thema folder niet geupload

**Oplossing:**
1. Controleer of `/wp-content/themes/gym-community-theme/` bestaat
2. Controleer of alle bestanden aanwezig zijn
3. Activeer thema in WordPress admin

### Probleem: Plugin niet zichtbaar

**Oorzaak:** Plugin folder niet geupload

**Oplossing:**
1. Controleer of `/wp-content/plugins/gym-community-plugin/` bestaat
2. Controleer of alle bestanden aanwezig zijn
3. Activeer plugin in WordPress admin

### Probleem: Afbeeldingen tonen niet

**Oorzaak:** URLs in database zijn nog localhost

**Oplossing:**
1. Voer URL replacement queries uit (zie Stap 3.4)
2. Of gebruik plugin: Better Search Replace

### Probleem: 404 Errors op pagina's

**Oorzaak:** Permalinks niet goed ingesteld

**Oplossing:**
1. Ga naar Settings > Permalinks
2. Klik "Save Changes"
3. Dit regenereert .htaccess

---

## 🚀 Updates Deployen (Na Eerste Deployment)

Als je later wijzigingen maakt:

**Alleen gewijzigde bestanden uploaden:**

1. **Thema wijzigingen:**
   - Upload alleen gewijzigde bestanden in `gym-community-theme/`

2. **Plugin wijzigingen:**
   - Upload alleen gewijzigde bestanden in `gym-community-plugin/`

3. **Database wijzigingen:**
   - Exporteer database vanuit Docker
   - Importeer in live phpMyAdmin
   - Voer URL replacement uit

**NIET opnieuw uploaden:**
- WordPress core bestanden (tenzij je WordPress update)
- wp-config bestanden (tenzij je credentials wijzigt)

---

## 🔐 Security Checklist

Na deployment:

- [ ] Genereer nieuwe security keys voor productie (wp-config-production.php)
- [ ] WP_DEBUG is false in productie
- [ ] DISALLOW_FILE_EDIT is true
- [ ] Sterke admin wachtwoorden
- [ ] Verwijder ongebruikte plugins
- [ ] Update WordPress, thema's en plugins
- [ ] Installeer security plugin (bijv. Wordfence)
- [ ] Maak regelmatige backups

---

## 📊 Deployment Samenvatting

| Stap | Actie | Tijd | Status |
|------|-------|------|--------|
| 1 | Database exporteren | 2 min | ⏳ |
| 2 | FTP upload bestanden | 10 min | ⏳ |
| 3 | Database importeren | 5 min | ⏳ |
| 4 | URLs updaten | 2 min | ⏳ |
| 5 | WordPress configureren | 5 min | ⏳ |
| 6 | Testen | 5 min | ⏳ |

**Totaal: ~30 minuten**

---

## 🎓 Volgende Keer

Voor toekomstige deployments:

1. **Gebruik Git** voor versiebeheer
2. **Automatiseer** met deployment scripts
3. **Test** altijd eerst lokaal in Docker
4. **Backup** altijd voor je deploy
5. **Documenteer** wijzigingen

---

## 📞 Hulp Nodig?

- **SPL Sites Documentatie:** https://www.stichtingpraktijkleren.nl/ict/software-developer/leermiddelen/spl-hostingpakket/voor-de-student/
- **WordPress Codex:** https://codex.wordpress.org/
- **Docker Docs:** https://docs.docker.com/

---

**Succes met je deployment!** 🚀

**Laatst bijgewerkt:** 30 Maart 2026
