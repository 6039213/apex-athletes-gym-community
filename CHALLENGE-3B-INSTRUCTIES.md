# Challenge 3b - Instructies

## 🎯 Wat Je Moet Doen

**Doel:** Zichtbare aanpassing maken in Docker én deployen naar live server met correcte URL migratie.

---

## ✅ Deel 1: Zichtbare Aanpassingen (KLAAR!)

Ik heb **2 zichtbare aanpassingen** gemaakt in je thema:

### 1. **Welkomstbanner op Homepage** 🎉

**Bestand:** `wp-content/themes/gym-community-theme/front-page.php`

**Wat toegevoegd:**
- Paarse/blauwe gradient banner
- Welkomsttekst: "🏋️ Welkom bij Gym Community! 💪"
- Beschrijving van de website
- Challenge 3b marker tekst

**Locatie:** Bovenaan de homepage, direct zichtbaar

### 2. **Header Achtergrondkleur Gewijzigd** 🎨

**Bestand:** `wp-content/themes/gym-community-theme/style.css`

**Wat gewijzigd:**
- **Oud:** Grijs gradient (`#2c3e50` → `#34495e`)
- **Nieuw:** Paars/blauw gradient (`#667eea` → `#764ba2`)
- Extra glow effect toegevoegd

**Locatie:** Hele header bovenaan elke pagina

---

## 🧪 Testen in Docker (Lokaal)

### Stap 1: Start Docker Containers

```bash
cd C:\laragon\www\Gym_community
docker-compose up -d
```

### Stap 2: Open Website

Open in browser: **http://localhost:8080**

### Stap 3: Controleer Aanpassingen

Je zou moeten zien:
- ✅ **Paarse/blauwe header** (in plaats van grijs)
- ✅ **Welkomstbanner** op homepage met gradient achtergrond
- ✅ Tekst: "Welkom bij Gym Community!"
- ✅ Challenge 3b marker

### Screenshot Maken (Voor Inlevering)

1. Open homepage: http://localhost:8080
2. Maak screenshot (Print Screen of Snipping Tool)
3. Sla op als: `challenge-3b-screenshot-lokaal.png`

---

## 📤 Deel 2: Deployen naar Live Server

### Stap 1: Database Exporteren (2 min)

```bash
cd C:\laragon\www\Gym_community
docker exec gym_community_db mysqldump -u wordpress -pwordpress Apex_Athletes > challenge-3b-database.sql
```

### Stap 2: Upload via FTP (5 min)

**FileZilla Instellingen:**
- Host: `st1738846938.splsites.nl`
- User: `st1738846938`
- Pass: `R5IFHm9dw7k6W6r`
- Port: `21`

**Upload deze bestanden:**

1. **Gewijzigde Thema Bestanden:**
   - `wp-content/themes/gym-community-theme/front-page.php` ✅
   - `wp-content/themes/gym-community-theme/style.css` ✅

2. **WordPress Config (als nog niet gedaan):**
   - `wp-config-auto.php` → hernoem naar `wp-config.php`
   - `wp-config-production.php`

3. **Hele Thema (als nog niet gedaan):**
   - `wp-content/themes/gym-community-theme/` (hele folder)

4. **Plugin (als nog niet gedaan):**
   - `wp-content/plugins/gym-community-plugin/` (hele folder)

### Stap 3: Database Importeren (5 min)

1. **Open phpMyAdmin:** https://st1738846938.splsites.nl/phpmyadmin
2. **Login:** st1738846938 / R5IFHm9dw7k6W6r
3. **Selecteer database:** `st1738846938`
4. **Klik "Import"**
5. **Choose File:** `challenge-3b-database.sql`
6. **Klik "Go"**
7. **Wacht** tot import compleet is

### Stap 4: URL Migratie (BELANGRIJK!) (3 min)

**Na database import, voer dit uit:**

1. **Blijf in phpMyAdmin**
2. **Selecteer database:** `st1738846938`
3. **Klik "SQL" tab**
4. **Open bestand:** `CHALLENGE-3B-URL-MIGRATION.sql`
5. **Kopieer ALLE queries**
6. **Plak in SQL veld**
7. **Klik "Go"**
8. **Wacht** tot alle queries uitgevoerd zijn

**Dit vervangt:**
- `http://localhost:8080` → `https://st1738846938.splsites.nl`
- In alle tabellen (wp_options, wp_posts, wp_postmeta, etc.)

### Stap 5: WordPress Configureren (2 min)

1. **Open:** https://st1738846938.splsites.nl/wp-admin
2. **Login** met je admin credentials
3. **Activeer thema:** Appearance > Themes > Gym Community Theme
4. **Update permalinks:** Settings > Permalinks > Save Changes

### Stap 6: Test Live Website (2 min)

**Open:** https://st1738846938.splsites.nl/

**Controleer:**
- ✅ Paarse/blauwe header zichtbaar
- ✅ Welkomstbanner op homepage
- ✅ Geen database errors
- ✅ Afbeeldingen laden correct
- ✅ Links werken (geen localhost meer)

### Screenshot Maken (Voor Inlevering)

1. Open live homepage: https://st1738846938.splsites.nl/
2. Maak screenshot
3. Sla op als: `challenge-3b-screenshot-live.png`

---

## 📋 Inleveren Checklist

Voor Challenge 3b moet je inleveren:

- [ ] Screenshot van lokale Docker website (met aanpassingen)
- [ ] Screenshot van live website (met aanpassingen)
- [ ] Beschrijving van aanpassingen:
  - Welkomstbanner toegevoegd op homepage
  - Header kleur gewijzigd naar paars/blauw gradient
- [ ] Bevestiging dat URL migratie succesvol was
- [ ] Database export: `challenge-3b-database.sql`

---

## 🔧 Troubleshooting

### Probleem: Aanpassingen niet zichtbaar in Docker

**Oplossing:**
1. Hard refresh browser: Ctrl+F5 (Windows) of Cmd+Shift+R (Mac)
2. Clear browser cache
3. Herstart Docker containers: `docker-compose restart`

### Probleem: Aanpassingen niet zichtbaar op live server

**Oplossing:**
1. Controleer of bestanden correct geüpload zijn via FTP
2. Check bestandsdatum/tijd in FileZilla
3. Hard refresh browser: Ctrl+F5
4. Controleer of juiste thema actief is in WordPress admin

### Probleem: Afbeeldingen tonen niet op live server

**Oplossing:**
1. Controleer of URL migratie queries uitgevoerd zijn
2. Voer verificatie queries uit (zie CHALLENGE-3B-URL-MIGRATION.sql)
3. Upload `wp-content/uploads/` folder via FTP (als je afbeeldingen hebt)

### Probleem: Database import mislukt

**Oplossing:**
1. Check bestandsgrootte (max 50MB meestal)
2. Probeer via command line (SSH) als te groot
3. Of gebruik phpMyAdmin "Partial import" optie

### Probleem: 404 Errors op pagina's

**Oplossing:**
1. Ga naar Settings > Permalinks
2. Klik "Save Changes"
3. Dit regenereert .htaccess regels

---

## 📊 Tijdsplanning

| Stap | Actie | Tijd |
|------|-------|------|
| 1 | Testen in Docker | 5 min |
| 2 | Screenshot lokaal | 1 min |
| 3 | Database exporteren | 2 min |
| 4 | FTP upload | 5 min |
| 5 | Database importeren | 5 min |
| 6 | URL migratie | 3 min |
| 7 | WordPress configureren | 2 min |
| 8 | Testen live | 2 min |
| 9 | Screenshot live | 1 min |

**Totaal: ~25 minuten**

---

## 📝 Wat Ik Heb Gewijzigd

### Bestand 1: front-page.php

**Locatie:** `wp-content/themes/gym-community-theme/front-page.php`

**Regel 12-23:** Welkomstbanner toegevoegd met:
- Gradient achtergrond (paars/blauw)
- Welkomsttekst met emoji's
- Beschrijving
- Challenge 3b marker

### Bestand 2: style.css

**Locatie:** `wp-content/themes/gym-community-theme/style.css`

**Regel 102-110:** Header styling gewijzigd:
- Achtergrond: `linear-gradient(135deg, #667eea 0%, #764ba2 100%)`
- Box shadow: Paarse glow effect

---

## 🎓 Wat Je Leert

Met deze challenge leer je:

1. ✅ **Thema aanpassingen** maken in WordPress
2. ✅ **CSS styling** wijzigen voor visuele effecten
3. ✅ **Docker** gebruiken voor lokale development
4. ✅ **FTP deployment** naar live server
5. ✅ **Database migratie** met URL replacement
6. ✅ **SQL queries** uitvoeren in phpMyAdmin
7. ✅ **Troubleshooting** deployment problemen

---

## 🚀 Volgende Stappen

Na Challenge 3b:

1. **Test grondig** alle functionaliteit op live server
2. **Maak backup** van werkende versie
3. **Documenteer** wat je geleerd hebt
4. **Bereid voor** voor volgende challenges

---

## 📞 Hulp Nodig?

- **SQL Queries:** Zie `CHALLENGE-3B-URL-MIGRATION.sql`
- **Deployment:** Zie `DEPLOYMENT-GUIDE-FTP.md`
- **Quick Reference:** Zie `QUICK-DEPLOYMENT-CHECKLIST.md`

---

**Succes met Challenge 3b!** 🎉

**Laatst bijgewerkt:** 30 Maart 2026
