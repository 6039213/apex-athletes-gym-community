# Gym Community Plugin

Een complete WordPress plugin voor het beheren van gym activiteiten, reviews en inschrijvingen voor een fitness community website.

## Beschrijving

Deze custom WordPress plugin is ontwikkeld als onderdeel van het DevSkills WordPress project. De plugin biedt uitgebreide functionaliteit voor een gym community website, inclusief:

- **Gym Activiteiten:** Beheer lessen en trainingen met details zoals datum, tijd, trainer, capaciteit en moeilijkheidsgraad
- **Review Systeem:** Product en dienst reviews met 5-sterren rating systeem
- **Inschrijfsysteem:** Online inschrijvingen voor activiteiten met capaciteit tracking
- **Email Notificaties:** Automatische bevestigingsmails bij inschrijvingen
- **Shortcodes:** Eenvoudig content tonen op elke pagina

## Features

### Custom Post Types

#### Gym Activities
- Datum en tijd planning
- Trainer informatie
- Capaciteit beheer
- Duur van activiteit
- Locatie
- Moeilijkheidsgraad (Beginner, Intermediate, Advanced, All Levels)
- Activiteit types (taxonomie)
- Featured images
- Inschrijvingen tracking

#### Reviews
- 5-sterren rating systeem
- Product/dienst naam
- Reviewer informatie
- Externe product links
- Pros en Cons
- Verified purchase badge
- Review categorieën (taxonomie)
- Moderatie systeem

### Inschrijfsysteem
- AJAX formulier voor soepele gebruikerservaring
- Capaciteit controle (voorkomt overboeking)
- Duplicate registratie preventie
- Email bevestigingen
- Admin overzicht van alle inschrijvingen
- Status tracking (confirmed, pending, cancelled)

### Shortcodes

#### `[gym_activities]`
Toon een lijst van gym activiteiten.

**Parameters:**
- `limit` - Aantal activiteiten (default: 10)
- `type` - Filter op activiteit type slug
- `upcoming` - Alleen toekomstige activiteiten (yes/no, default: yes)

**Voorbeelden:**
```
[gym_activities]
[gym_activities limit="5" type="cardio"]
[gym_activities limit="10" upcoming="no"]
```

#### `[gym_schedule]`
Toon een weekrooster van activiteiten.

**Parameters:**
- `days` - Aantal dagen vooruit (default: 7)

**Voorbeelden:**
```
[gym_schedule]
[gym_schedule days="14"]
```

#### `[recent_reviews]`
Toon recente product/dienst reviews.

**Parameters:**
- `limit` - Aantal reviews (default: 5)
- `category` - Filter op review categorie slug

**Voorbeelden:**
```
[recent_reviews]
[recent_reviews limit="3" category="supplements"]
[recent_reviews limit="10" category="equipment"]
```

#### `[product_reviews]`
Alias voor `[recent_reviews]` - zelfde functionaliteit.

#### `[gym_registration_form]`
Toon inschrijfformulier voor een activiteit.

**Parameters:**
- `activity_id` - ID van activiteit (default: huidige post ID)

**Voorbeelden:**
```
[gym_registration_form]
[gym_registration_form activity_id="123"]
```

### Admin Interface

#### Settings Pagina
- Email notificaties aan/uit
- Admin email configuratie
- Auto-approve reviews
- Registratie limiet per gebruiker
- Plugin statistieken dashboard

#### Registraties Beheer
- Overzicht van alle inschrijvingen
- Filter op activiteit
- Export mogelijkheden
- Status beheer
- Verwijder functionaliteit

#### Custom Columns
- Activiteiten: Datum/tijd, Trainer, Capaciteit, Inschrijvingen, Type
- Reviews: Rating (sterren), Product, Reviewer, Categorie, Status

### Hooks & Filters

De plugin gebruikt WordPress hooks en filters voor uitbreidbaarheid:

**Actions:**
- `init` - Registreer post types en taxonomieën
- `admin_menu` - Admin menu items
- `wp_enqueue_scripts` - Frontend scripts en styles
- `admin_enqueue_scripts` - Admin scripts en styles
- `save_post_gym_activity` - Opslaan activiteit meta data
- `save_post_gym_review` - Opslaan review meta data

**AJAX Actions:**
- `gym_register_activity` - Verwerk inschrijvingen

## Installatie

1. Upload de `gym-community-plugin` folder naar `/wp-content/plugins/`
2. Activeer de plugin via het 'Plugins' menu in WordPress
3. Ga naar 'Gym Community' > 'Settings' om de plugin te configureren
4. Maak activiteit types en review categorieën aan
5. Begin met het toevoegen van activiteiten en reviews

## Configuratie

### Basis Setup

1. **Activiteit Types aanmaken:**
   - Ga naar Gym Activities > Activity Types
   - Voeg types toe zoals: Cardio, Strength, Yoga, Pilates, CrossFit, etc.

2. **Review Categorieën aanmaken:**
   - Ga naar Reviews > Review Categories
   - Voeg categorieën toe zoals: Equipment, Supplements, Services, etc.

3. **Plugin Settings:**
   - Ga naar Gym Community > Settings
   - Configureer email notificaties
   - Stel admin email in
   - Bepaal review moderatie instellingen

### Email Configuratie

De plugin verstuurt automatisch emails bij:
- Nieuwe inschrijvingen (naar gebruiker)
- Nieuwe inschrijvingen (naar admin, indien ingeschakeld)

Emails gebruiken de standaard WordPress `wp_mail()` functie.

## Gebruik

### Activiteiten Aanmaken

1. Ga naar Gym Activities > Add New
2. Vul de titel en beschrijving in
3. Upload een featured image
4. Vul de Activity Details in:
   - Datum en tijd
   - Trainer naam
   - Maximum capaciteit
   - Duur (minuten)
   - Locatie
   - Moeilijkheidsgraad
5. Selecteer een Activity Type
6. Publiceer de activiteit

### Reviews Aanmaken

1. Ga naar Reviews > Add New
2. Vul de review titel in
3. Schrijf de review tekst
4. Upload een product afbeelding
5. Vul de Review Details in:
   - Product/dienst naam
   - Rating (1-5 sterren)
   - Reviewer informatie
   - Externe product link
   - Pros en Cons
   - Verified purchase checkbox
6. Selecteer een Review Category
7. Publiceer of bewaar als concept (voor moderatie)

### Inschrijvingen Beheren

1. Ga naar Gym Activities > Registrations
2. Bekijk alle inschrijvingen
3. Filter op specifieke activiteit
4. Verwijder inschrijvingen indien nodig
5. Export data (via browser print/save)

### Shortcodes Gebruiken

Voeg shortcodes toe aan pagina's of posts:

**Activiteiten Pagina:**
```
<h2>Upcoming Classes</h2>
[gym_activities limit="6" upcoming="yes"]

<h2>Weekly Schedule</h2>
[gym_schedule days="7"]
```

**Reviews Pagina:**
```
<h2>Latest Reviews</h2>
[recent_reviews limit="6"]

<h2>Equipment Reviews</h2>
[product_reviews category="equipment" limit="4"]
```

**Activiteit Detail Pagina:**
```
[gym_registration_form]
```

## Database Structuur

### Custom Tables

#### wp_gym_registrations
```sql
id - bigint(20) AUTO_INCREMENT PRIMARY KEY
activity_id - bigint(20) NOT NULL
user_name - varchar(255) NOT NULL
user_email - varchar(255) NOT NULL
user_phone - varchar(50) NULL
registration_date - datetime DEFAULT CURRENT_TIMESTAMP
status - varchar(20) DEFAULT 'pending'
notes - text NULL
```

### Post Meta

#### Gym Activities
- `_gym_activity_date` - Datum (Y-m-d)
- `_gym_activity_time` - Tijd (H:i)
- `_gym_activity_trainer` - Trainer naam
- `_gym_activity_capacity` - Maximum capaciteit
- `_gym_activity_duration` - Duur in minuten
- `_gym_activity_location` - Locatie
- `_gym_activity_difficulty` - Moeilijkheidsgraad

#### Reviews
- `_gym_review_product` - Product/dienst naam
- `_gym_review_rating` - Rating (1-5)
- `_gym_review_reviewer_name` - Reviewer naam
- `_gym_review_reviewer_email` - Reviewer email
- `_gym_review_product_link` - Externe link
- `_gym_review_pros` - Voordelen
- `_gym_review_cons` - Nadelen
- `_gym_review_verified` - Verified purchase (0/1)

## Beveiliging

De plugin implementeert WordPress security best practices:

- **Nonce verificatie** voor alle formulieren
- **Capability checks** voor admin functies
- **Data sanitization** bij input
- **Data escaping** bij output
- **Prepared statements** voor database queries
- **AJAX nonce** voor AJAX requests

## Compatibiliteit

- **WordPress:** 5.0 of hoger
- **PHP:** 7.4 of hoger
- **MySQL:** 5.6 of hoger
- **Browsers:** Chrome, Firefox, Safari, Edge (laatste 2 versies)

## Aanbevolen Plugins

- **Advanced Custom Fields (ACF)** - Voor extra custom fields (optioneel)
- **Contact Form 7** - Voor contactformulieren
- **Yoast SEO** - Voor SEO optimalisatie

## Ontwikkeling

### Bestandsstructuur

```
gym-community-plugin/
├── gym-community-plugin.php (Main plugin file)
├── README.md
├── includes/
│   ├── class-gym-activities.php
│   ├── class-gym-reviews.php
│   ├── class-gym-registrations.php
│   └── class-gym-shortcodes.php
├── admin/
│   └── class-gym-admin.php
└── assets/
    ├── css/
    │   ├── gym-community.css
    │   └── admin.css
    └── js/
        ├── gym-community.js
        └── admin.js
```

### Code Standards

De plugin volgt:
- WordPress Coding Standards
- PHP PSR-12 (waar mogelijk binnen WordPress context)
- JavaScript ES5+ (voor browser compatibiliteit)
- CSS3 met vendor prefixes waar nodig

### Debugging

Enable debugging in wp-config.php:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs in: `wp-content/debug.log`

## Veelgestelde Vragen

### Hoe kan ik de capaciteit van een activiteit aanpassen?
Bewerk de activiteit en wijzig het "Max Capacity" veld in de Activity Details meta box.

### Kunnen gebruikers zich uitschrijven?
Momenteel niet via de frontend. Uitschrijvingen moeten via de admin worden verwerkt.

### Hoe modereer ik reviews?
Reviews kunnen als "Draft" worden opgeslagen. Alleen "Published" reviews zijn zichtbaar op de frontend.

### Kan ik de email templates aanpassen?
Ja, via code. Gebruik de `gym_registration_email_content` filter (custom implementatie mogelijk).

### Werkt de plugin met page builders?
Ja, de shortcodes werken met alle page builders die WordPress shortcodes ondersteunen.

## Changelog

### Version 1.0.0
- Initial release
- Gym Activities Custom Post Type
- Reviews Custom Post Type
- Registration system met database table
- Email notificaties
- 5 Shortcodes
- Admin settings pagina
- Admin registrations overzicht
- Responsive frontend styling
- AJAX formulier handling

## Support

Voor vragen, bugs of feature requests:
- Check de documentatie in WordPress admin: Gym Community > Documentation
- Bekijk de code comments voor developer documentatie
- Contact de ontwikkelaar

## Credits

- **Ontwikkeld door:** [Jouw Naam]
- **Project:** DevSkills WordPress Thema-ontwikkeling
- **Jaar:** 2026
- **Icons:** WordPress Dashicons
- **Framework:** WordPress Plugin API

## Licentie

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

Dit project is ontwikkeld voor educatieve doeleinden als onderdeel van een software development opleiding.

## Toekomstige Features (Roadmap)

- [ ] Frontend uitschrijf functionaliteit
- [ ] User dashboard voor eigen inschrijvingen
- [ ] Wachtlijst voor volle activiteiten
- [ ] Recurring activiteiten (wekelijks/maandelijks)
- [ ] Email template customization via admin
- [ ] Export registraties naar CSV
- [ ] Google Calendar integratie
- [ ] SMS notificaties (met externe service)
- [ ] Review submission formulier (frontend)
- [ ] Rating aggregatie per product
- [ ] Social sharing voor activiteiten en reviews
