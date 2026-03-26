# OTAP Omgevingen Configuratie

## Overzicht

OTAP staat voor Ontwikkelen, Testen, Accepteren en Productie. Deze omgevingen zorgen voor een gestructureerde workflow van development naar live deployment.

## Omgevingen

### O - Ontwikkelomgeving (Development)
- **Locatie:** Lokaal op Laragon
- **URL:** `http://gym_community.test` of `http://localhost/Gym_community`
- **Database:** `Apex_Athletes`
- **Doel:** Actieve development, nieuwe features bouwen
- **WP_DEBUG:** `true`
- **Git branch:** `development`

**Database credentials:**
```php
define('DB_NAME', 'Apex_Athletes');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
```

### T - Testomgeving (Testing)
- **Locatie:** Lokaal op Laragon (aparte database)
- **URL:** `http://gym_community_test.test`
- **Database:** `Apex_Athletes_test`
- **Doel:** Testing van nieuwe features, bug fixes testen
- **WP_DEBUG:** `true`
- **Git branch:** `feature/*` of `bugfix/*`

**Setup:**
1. Dupliceer database: `Apex_Athletes` → `Apex_Athletes_test`
2. Maak aparte wp-config voor test omgeving
3. Test alle functionaliteit voor merge naar development

### A - Acceptatieomgeving (Staging)
- **Locatie:** Server via FTP (subdomain)
- **URL:** `http://staging.jouwdomein.nl` (of subdirectory)
- **Database:** Server database (staging)
- **Doel:** Pre-productie testing, client review
- **WP_DEBUG:** `false` (maar log errors)
- **Git branch:** `main` (voor deployment)

**Deployment via FTP:**
1. Exporteer lokale database
2. Upload bestanden via FTP naar staging directory
3. Importeer database op server
4. Update wp-config.php met server credentials
5. Search-replace URLs: `http://gym_community.test` → `http://staging.jouwdomein.nl`

### P - Productieomgeving (Live)
- **Locatie:** Server via FTP (main domain)
- **URL:** `http://jouwdomein.nl`
- **Database:** Server database (productie)
- **Doel:** Live website voor eindgebruikers
- **WP_DEBUG:** `false`
- **Git branch:** `main` (tagged releases)

**Deployment via FTP:**
1. Test eerst volledig op Acceptatie
2. Maak backup van huidige live site
3. Upload bestanden via FTP
4. Importeer database
5. Update wp-config.php
6. Search-replace URLs
7. Test alle functionaliteit
8. Tag release in Git: `v1.0.0`

## Workflow

```
Development (lokaal)
    ↓ commit & push
GitHub Repository
    ↓ pull & test
Testing (lokaal)
    ↓ merge & deploy
Acceptatie (staging server)
    ↓ approve & deploy
Productie (live server)
```

## Database Migratie

### Lokaal naar Server
```bash
# 1. Exporteer database
mysqldump -u root Apex_Athletes > gym_community_backup.sql

# 2. Upload via FTP of phpMyAdmin op server

# 3. Importeer op server
mysql -u username -p database_name < gym_community_backup.sql

# 4. Search-Replace URLs (via WP-CLI of plugin)
wp search-replace 'http://gym_community.test' 'http://jouwdomein.nl'
```

### Via phpMyAdmin
1. Lokaal: Export database als .sql
2. Server: Import .sql bestand
3. Run SQL query voor URL replacement:
```sql
UPDATE wp_options SET option_value = replace(option_value, 'http://gym_community.test', 'http://jouwdomein.nl') WHERE option_name = 'home' OR option_name = 'siteurl';
UPDATE wp_posts SET guid = replace(guid, 'http://gym_community.test','http://jouwdomein.nl');
UPDATE wp_posts SET post_content = replace(post_content, 'http://gym_community.test', 'http://jouwdomein.nl');
UPDATE wp_postmeta SET meta_value = replace(meta_value,'http://gym_community.test','http://jouwdomein.nl');
```

## FTP Configuratie

### FTP Credentials (krijg je van hosting provider)
```
Host: ftp.jouwdomein.nl
Username: [jouw_username]
Password: [jouw_password]
Port: 21 (of 22 voor SFTP)
```

### FTP Client (FileZilla aanbevolen)
1. Download FileZilla
2. Voer credentials in
3. Verbind met server
4. Upload naar juiste directory (meestal `public_html` of `www`)

### Bestanden om NIET te uploaden via FTP
- `wp-config.php` (maak nieuwe op server)
- `wp-content/uploads/` (te groot, upload apart of sync)
- `.git/` (niet nodig op server)
- `node_modules/` (niet nodig)

### Bestanden om WEL te uploaden
- WordPress core (tenzij al geïnstalleerd)
- `wp-content/themes/gym-community-theme/`
- `wp-content/plugins/gym-community-plugin/`
- Andere custom plugins
- `.htaccess` (pas aan voor server)

## Beveiliging per Omgeving

### Development (lokaal)
- WP_DEBUG: `true`
- WP_DEBUG_LOG: `true`
- WP_DEBUG_DISPLAY: `true`
- SCRIPT_DEBUG: `true`

### Testing (lokaal)
- WP_DEBUG: `true`
- WP_DEBUG_LOG: `true`
- WP_DEBUG_DISPLAY: `false`

### Acceptatie (staging)
- WP_DEBUG: `false`
- WP_DEBUG_LOG: `true`
- WP_DEBUG_DISPLAY: `false`
- Disable search engine indexing

### Productie (live)
- WP_DEBUG: `false`
- WP_DEBUG_LOG: `false` (of alleen critical errors)
- WP_DEBUG_DISPLAY: `false`
- SSL certificaat actief
- Security plugins actief
- Reguliere backups

## Backup Strategie

### Lokaal (Development)
- Git commits (code)
- Database export wekelijks
- Opslag: lokale backup folder

### Server (Acceptatie & Productie)
- Dagelijkse automatische backups (via hosting)
- Wekelijkse handmatige backups voor deployment
- Database + bestanden
- Opslag: server + lokale kopie

## Checklist voor Deployment

### Voor elke deployment:
- [ ] Alle code gecommit naar Git
- [ ] Lokaal volledig getest
- [ ] Database backup gemaakt
- [ ] wp-config.php voorbereid voor server
- [ ] URLs search-replace plan klaar
- [ ] FTP credentials getest
- [ ] Backup van huidige live site (bij productie)

### Na deployment:
- [ ] Website bereikbaar op nieuwe URL
- [ ] Alle pagina's werken
- [ ] Formulieren testen
- [ ] Admin login werkt
- [ ] Plugins actief
- [ ] Thema correct geladen
- [ ] Images en media laden
- [ ] SSL certificaat actief (productie)
- [ ] Git tag aangemaakt (productie)

## Troubleshooting

### Witte pagina na deployment
- Check error logs
- Verify wp-config.php database credentials
- Check bestandsrechten (755 voor directories, 644 voor files)

### URLs niet correct
- Run search-replace opnieuw
- Check wp_options table: `siteurl` en `home`
- Clear cache

### Database connectie error
- Verify database credentials in wp-config.php
- Check of database bestaat op server
- Test database connectie via phpMyAdmin

### Plugins niet werkend
- Reactiveer plugins in admin
- Check plugin compatibility met PHP versie
- Check error logs

## Handige Tools

- **FileZilla:** FTP client
- **phpMyAdmin:** Database management
- **WP-CLI:** Command line WordPress management
- **Better Search Replace:** Plugin voor URL replacement
- **UpdraftPlus:** Backup plugin
- **All-in-One WP Migration:** Complete site migratie

## Contactgegevens Hosting

**Provider:** [Naam hosting provider]
**Support:** [Support email/telefoon]
**Control Panel:** [cPanel/Plesk URL]
**phpMyAdmin:** [phpMyAdmin URL]
