# Deployment Guide - Gym Community WordPress

Complete handleiding voor het deployen van de Gym Community website naar staging en productie via FTP.

## Voorbereiding

### Benodigdheden
- FTP credentials van hosting provider
- FTP client (FileZilla aanbevolen)
- Database toegang op server (phpMyAdmin)
- Backup van huidige site (indien van toepassing)

### Checklist voor Deployment
- [ ] Alle code getest lokaal
- [ ] Git commits up-to-date
- [ ] Database backup gemaakt
- [ ] wp-config.php voorbereid voor server
- [ ] FTP credentials getest
- [ ] Deployment plan doorgenomen

## Stap 1: Lokale Voorbereiding

### 1.1 Database Exporteren
```bash
# Via command line (als je MySQL tools hebt)
mysqldump -u root Apex_Athletes > gym_community_backup_[DATUM].sql

# Of via phpMyAdmin:
# 1. Open phpMyAdmin (http://localhost/phpmyadmin)
# 2. Selecteer database 'Apex_Athletes'
# 3. Klik op 'Export'
# 4. Kies 'Quick' export method
# 5. Format: SQL
# 6. Klik 'Go' en download bestand
```

### 1.2 Bestanden Voorbereiden
```
Bestanden om te uploaden:
✓ wp-admin/ (WordPress core)
✓ wp-includes/ (WordPress core)
✓ wp-content/themes/gym-community-theme/ (custom thema)
✓ wp-content/plugins/gym-community-plugin/ (custom plugin)
✓ wp-content/plugins/[andere plugins]
✓ index.php
✓ .htaccess (pas aan voor server)

Bestanden om NIET te uploaden:
✗ wp-config.php (maak nieuwe op server)
✗ wp-content/uploads/ (upload apart, grote bestanden)
✗ .git/ (niet nodig op server)
✗ node_modules/
✗ *.log bestanden
```

### 1.3 wp-config.php Voorbereiden
1. Kopieer `wp-config-production.php` naar `wp-config.php`
2. Vul database credentials in van server
3. Genereer nieuwe security keys: https://api.wordpress.org/secret-key/1.1/salt/
4. Sla op (upload later naar server)

## Stap 2: FTP Verbinding Opzetten

### 2.1 FileZilla Installeren
Download van: https://filezilla-project.org/

### 2.2 FTP Credentials Invoeren
```
Host: ftp.jouwdomein.nl (of IP adres)
Username: [van hosting provider]
Password: [van hosting provider]
Port: 21 (standaard FTP) of 22 (SFTP, veiliger)
```

### 2.3 Verbinden
1. Open FileZilla
2. Vul credentials in bovenaan
3. Klik 'Quickconnect'
4. Accepteer server certificaat (eerste keer)
5. Je ziet nu lokale bestanden links, server rechts

## Stap 3: Bestanden Uploaden

### 3.1 Navigeer naar Juiste Directory
Op server, ga naar:
- `public_html/` (meest voorkomend)
- Of `www/`
- Of `httpdocs/`
- Of subdirectory voor staging: `public_html/staging/`

### 3.2 WordPress Core Uploaden
Als WordPress nog niet op server staat:
1. Selecteer alle WordPress bestanden lokaal (behalve wp-config.php)
2. Sleep naar server directory
3. Wacht tot upload compleet is (kan lang duren)

### 3.3 Custom Thema Uploaden
```
Lokaal: c:\laragon\www\Gym_community\wp-content\themes\gym-community-theme\
Server: public_html/wp-content/themes/gym-community-theme/
```
Upload complete theme folder

### 3.4 Custom Plugin Uploaden
```
Lokaal: c:\laragon\www\Gym_community\wp-content\plugins\gym-community-plugin\
Server: public_html/wp-content/plugins/gym-community-plugin/
```
Upload complete plugin folder

### 3.5 Andere Plugins
Upload alle gebruikte plugins (ACF, Contact Form 7, etc.)

### 3.6 wp-config.php Uploaden
1. Upload de aangepaste wp-config.php naar root directory
2. **CONTROLEER** dat database credentials correct zijn!

### 3.7 .htaccess Uploaden
Upload .htaccess (WordPress permalinks)

## Stap 4: Database Importeren

### 4.1 Toegang tot phpMyAdmin
1. Log in op hosting control panel (cPanel/Plesk)
2. Open phpMyAdmin
3. Of ga naar: `https://jouwdomein.nl/phpmyadmin`

### 4.2 Database Aanmaken (indien nodig)
1. Klik 'Databases'
2. Maak nieuwe database aan
3. Noteer database naam, username, password
4. Update wp-config.php met deze gegevens

### 4.3 Database Importeren
1. Selecteer database in phpMyAdmin
2. Klik 'Import' tab
3. Kies bestand: `gym_community_backup_[DATUM].sql`
4. Klik 'Go'
5. Wacht tot import compleet is

### 4.4 URLs Vervangen in Database
**BELANGRIJK:** Vervang lokale URLs naar live URLs

**Methode 1: Via SQL queries**
```sql
-- Vervang gym_community.test met jouwdomein.nl
UPDATE wp_options 
SET option_value = replace(option_value, 'http://gym_community.test', 'https://jouwdomein.nl') 
WHERE option_name = 'home' OR option_name = 'siteurl';

UPDATE wp_posts 
SET guid = replace(guid, 'http://gym_community.test', 'https://jouwdomein.nl');

UPDATE wp_posts 
SET post_content = replace(post_content, 'http://gym_community.test', 'https://jouwdomein.nl');

UPDATE wp_postmeta 
SET meta_value = replace(meta_value, 'http://gym_community.test', 'https://jouwdomein.nl');

-- Voor ACF fields
UPDATE wp_postmeta 
SET meta_value = replace(meta_value, 'http://gym_community.test', 'https://jouwdomein.nl');
```

**Methode 2: Via Better Search Replace plugin**
1. Installeer 'Better Search Replace' plugin
2. Ga naar Tools > Better Search Replace
3. Search for: `http://gym_community.test`
4. Replace with: `https://jouwdomein.nl`
5. Select all tables
6. Run as dry run eerst (test)
7. Run live search/replace

## Stap 5: Website Configureren

### 5.1 Inloggen op WordPress Admin
1. Ga naar: `https://jouwdomein.nl/wp-admin`
2. Log in met je credentials
3. Als login niet werkt, reset password via phpMyAdmin

### 5.2 Permalinks Opnieuw Opslaan
1. Ga naar Settings > Permalinks
2. Klik 'Save Changes' (zonder iets te wijzigen)
3. Dit regenereert .htaccess regels

### 5.3 Thema Activeren
1. Ga naar Appearance > Themes
2. Activeer 'Gym Community Theme'

### 5.4 Plugins Activeren
1. Ga naar Plugins
2. Activeer alle benodigde plugins:
   - Gym Community Plugin
   - Advanced Custom Fields
   - Contact Form 7
   - Yoast SEO
   - Andere plugins

### 5.5 Media Library Check
1. Ga naar Media
2. Check of afbeeldingen laden
3. Zo niet, upload wp-content/uploads/ via FTP

## Stap 6: Testing

### 6.1 Functionaliteit Testen
- [ ] Homepage laadt correct
- [ ] Alle menu's werken
- [ ] Nieuwspagina toont posts
- [ ] Activiteiten pagina werkt
- [ ] Reviews pagina werkt
- [ ] Contact formulier werkt (test verzending)
- [ ] Inschrijfformulier werkt
- [ ] Admin functies werken
- [ ] Custom plugin functionaliteit werkt
- [ ] Shortcodes renderen correct

### 6.2 Performance Check
- [ ] Pagina's laden snel (< 3 seconden)
- [ ] Afbeeldingen geoptimaliseerd
- [ ] Caching werkt (indien ingeschakeld)

### 6.3 Security Check
- [ ] SSL certificaat actief (https://)
- [ ] wp-config.php niet toegankelijk via browser
- [ ] .htaccess beveiligingsregels actief
- [ ] Admin login werkt
- [ ] File permissions correct (755/644)

### 6.4 SEO Check
- [ ] Yoast SEO geconfigureerd
- [ ] Meta descriptions ingesteld
- [ ] XML sitemap gegenereerd
- [ ] Robots.txt correct

## Stap 7: Post-Deployment

### 7.1 DNS Configuratie (voor nieuwe domeinen)
Als je een nieuw domein gebruikt:
1. Update DNS records bij domain registrar
2. Wacht 24-48 uur voor DNS propagatie
3. Test met: `nslookup jouwdomein.nl`

### 7.2 SSL Certificaat Installeren
1. Via hosting control panel
2. Of gebruik Let's Encrypt (gratis)
3. Force HTTPS in wp-config.php:
```php
define('FORCE_SSL_ADMIN', true);
```

### 7.3 Backup Instellen
1. Installeer backup plugin (UpdraftPlus)
2. Configureer automatische backups
3. Test restore functionaliteit

### 7.4 Monitoring Instellen
1. Google Analytics toevoegen (optioneel)
2. Uptime monitoring (optioneel)
3. Error monitoring

### 7.5 Git Tag Aanmaken
```bash
git tag -a v1.0.0 -m "Initial production release"
git push origin v1.0.0
```

## Troubleshooting

### Probleem: Witte pagina (White Screen of Death)
**Oplossing:**
1. Check error logs via FTP: `wp-content/debug.log`
2. Enable WP_DEBUG tijdelijk in wp-config.php
3. Check PHP error logs in hosting control panel
4. Verify bestandsrechten: 755 voor directories, 644 voor files

### Probleem: Database connection error
**Oplossing:**
1. Verify database credentials in wp-config.php
2. Check of database bestaat in phpMyAdmin
3. Test database connectie via hosting control panel
4. Check of database user juiste permissions heeft

### Probleem: 404 errors op alle pagina's
**Oplossing:**
1. Check of .htaccess bestaat en correct is
2. Permalinks opnieuw opslaan (Settings > Permalinks)
3. Check Apache mod_rewrite is enabled op server

### Probleem: Afbeeldingen laden niet
**Oplossing:**
1. Upload wp-content/uploads/ via FTP
2. Check bestandsrechten (755 voor directories)
3. Run search-replace opnieuw voor image URLs
4. Regenerate thumbnails (plugin)

### Probleem: Plugins werken niet
**Oplossing:**
1. Deactiveer alle plugins
2. Activeer één voor één
3. Check PHP versie compatibility
4. Check error logs

### Probleem: CSS/JS niet geladen
**Oplossing:**
1. Clear browser cache
2. Clear WordPress cache (plugin)
3. Check of bestanden geüpload zijn via FTP
4. Check bestandspaden in browser inspector

## Bestandsrechten (File Permissions)

Correcte permissions voor WordPress:
```
Directories: 755
Files: 644
wp-config.php: 600 (extra veilig)
```

Instellen via FTP (FileZilla):
1. Rechtermuisklik op bestand/folder
2. File permissions
3. Vul numerieke waarde in
4. Voor directories: check "Recurse into subdirectories"

## Handige Commands

### Via SSH (als je SSH toegang hebt)
```bash
# Bestandsrechten instellen
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# Database backup
mysqldump -u username -p database_name > backup.sql

# Database import
mysql -u username -p database_name < backup.sql

# Search-replace URLs (WP-CLI)
wp search-replace 'http://gym_community.test' 'https://jouwdomein.nl' --all-tables
```

## Staging vs Productie

### Staging Deployment (Acceptatie)
- Subdomain: `staging.jouwdomein.nl`
- Disable search engine indexing
- WP_DEBUG_LOG enabled
- Test alle functionaliteit
- Client review

### Productie Deployment (Live)
- Main domain: `jouwdomein.nl`
- Enable search engine indexing
- WP_DEBUG disabled
- SSL certificaat actief
- Backups actief
- Monitoring actief

## Deployment Checklist

### Pre-Deployment
- [ ] Code volledig getest lokaal
- [ ] Database backup gemaakt
- [ ] Git commits up-to-date
- [ ] wp-config.php voorbereid
- [ ] FTP credentials getest
- [ ] Deployment plan doorgenomen
- [ ] Backup van huidige live site (indien van toepassing)

### During Deployment
- [ ] Bestanden geüpload via FTP
- [ ] Database geïmporteerd
- [ ] URLs vervangen in database
- [ ] wp-config.php geconfigureerd
- [ ] Permalinks opnieuw opgeslagen
- [ ] Thema geactiveerd
- [ ] Plugins geactiveerd

### Post-Deployment
- [ ] Alle pagina's getest
- [ ] Formulieren getest
- [ ] Admin functies getest
- [ ] SSL certificaat actief
- [ ] Backups ingesteld
- [ ] Git tag aangemaakt
- [ ] Documentatie bijgewerkt
- [ ] Client/docent geïnformeerd

## Support

Bij problemen:
1. Check error logs
2. Raadpleeg WordPress Codex
3. Contact hosting support
4. Check WordPress forums

## Handige Links

- WordPress Codex: https://codex.wordpress.org/
- FileZilla: https://filezilla-project.org/
- Security Keys Generator: https://api.wordpress.org/secret-key/1.1/salt/
- Better Search Replace: https://wordpress.org/plugins/better-search-replace/
- UpdraftPlus Backup: https://wordpress.org/plugins/updraftplus/
