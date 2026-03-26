# Gym Community WordPress Website

Een volledig WordPress platform voor een Gym/Fitness Community met custom thema en plugins.

## Project Informatie

- **Project:** DevSkills WordPress Thema-ontwikkeling
- **Community:** Gym/Fitness Community
- **Ontwikkelaar:** [Jouw naam]
- **Periode:** 9 weken (Februari - April 2026)

## Functionaliteit

De website biedt:
- Nieuwsberichten voor community leden
- Overzicht van gym activiteiten en lessen
- Inschrijfsysteem voor trainingen
- Product en dienst reviews
- Links naar externe webshops voor producten

## Technische Stack

- **CMS:** WordPress (latest)
- **Server (lokaal):** Laragon
- **Database:** MySQL
- **Versiebeheer:** GitHub
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+

## OTAP Omgevingen

- **O (Ontwikkelen):** Laragon lokaal - `c:\laragon\www\Gym_community`
- **T (Testen):** Lokale test database
- **A (Acceptatie):** Staging server via FTP
- **P (Productie):** Live website via FTP

## Installatie Lokaal

1. Clone deze repository
2. Importeer database: `Apex_Athletes.sql`
3. Kopieer `wp-config-sample.php` naar `wp-config.php`
4. Pas database credentials aan in `wp-config.php`
5. Start Laragon en open `http://gym_community.test`

## Project Structuur

```
Gym_community/
├── wp-content/
│   ├── themes/
│   │   └── gym-community-theme/     # Custom thema
│   └── plugins/
│       └── gym-community-plugin/    # Custom plugin
├── .gitignore
├── README.md
└── [WordPress core bestanden]
```

## Custom Ontwikkeling

### Custom Thema: gym-community-theme
- Volledig responsive design
- Gym/fitness branding
- Custom templates voor alle pagina types

### Custom Plugin: gym-community-plugin
- **Gym Activiteiten:** Custom Post Type voor lessen en trainingen
- **Reviews:** Product/dienst review systeem met ratings
- **Inschrijvingen:** Inschrijfsysteem voor activiteiten
- **Shortcodes:** `[gym_activities]`, `[gym_schedule]`, `[recent_reviews]`

## Gebruikte Plugins

- Advanced Custom Fields (ACF)
- Contact Form 7
- Yoast SEO
- [Andere plugins worden toegevoegd]

## Development Workflow

### Branch Strategie
- `main` - Productie code
- `development` - Development code
- `feature/naam` - Nieuwe features
- `bugfix/naam` - Bug fixes

### Commit Conventies
```
feat: Nieuwe feature
fix: Bug fix
docs: Documentatie
style: Styling aanpassingen
```

## Deployment

### Via FTP naar Live Server
1. Exporteer database lokaal
2. Upload bestanden via FTP
3. Importeer database op server
4. Update wp-config.php met server credentials
5. Search-replace URLs in database

## Documentatie

- [Plugin Documentatie](wp-content/plugins/gym-community-plugin/README.md)
- [Gebruikershandleiding](docs/gebruikershandleiding.md)
- [Deployment Guide](docs/deployment-guide.md)

## Beveiliging

- Security keys geconfigureerd
- WP_DEBUG enabled in development
- Bestandsrechten correct ingesteld
- Reguliere backups

## Support

Voor vragen of problemen, zie de documentatie of neem contact op met de ontwikkelaar.

## Licentie

Dit project is ontwikkeld voor educatieve doeleinden.
