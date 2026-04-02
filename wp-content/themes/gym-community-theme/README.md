# Apex Athletes - Gym Community Theme

Een professioneel en responsive WordPress thema voor de Apex Athletes Gym Community, gebouwd met custom post types, shortcodes en een volledig eigen design gebaseerd op de Apex Athletes huisstijl.

## Beschrijving

Dit custom WordPress thema is ontwikkeld als onderdeel van het DevSkills WordPress project. Versie 2.0.0 bevat een volledige herwerking met de Apex Athletes huisstijl, inclusief:

- Consistente branding met CSS custom properties
- Google Fonts integratie (Oswald + Montserrat)
- WordPress Customizer opties voor dynamische kleur- en tekstwijzigingen
- Semantic HTML5 met ARIA labels voor toegankelijkheid
- Responsive design voor alle schermformaten
- Nederlandse lokalisatie

## Stijlgids - Apex Athletes

### Kleuren

| Kleur           | Hex       | CSS Variable        | Gebruik              |
|-----------------|-----------|---------------------|----------------------|
| Dark Navy       | `#2C3E50` | `--color-primary`   | Headers, tekst       |
| Teal            | `#2C6E6A` | `--color-secondary` | Accenten, hover      |
| Cyan            | `#4ECDC4` | `--color-accent`    | Knoppen, highlights  |
| Dark            | `#1A1A2E` | `--color-dark`      | Footer, hero bg      |
| Light Gray      | `#F5F7FA` | `--color-light`     | Achtergrond          |
| White           | `#FFFFFF` | `--color-white`     | Kaarten, content     |

### Fonts

- **Headings:** Oswald (Google Fonts) - uppercase, bold
- **Body:** Montserrat (Google Fonts) - regular 400, medium 500, semibold 600

## Features

- **Apex Athletes Branding:** Volledige huisstijl met CSS custom properties
- **Volledig Responsive:** Mobile-first design voor alle schermformaten
- **Custom Post Type Templates:** Archief en single templates voor `gym_activity` en `gym_review`
- **Hero Section:** Dynamische homepage hero met Customizer tekst
- **Community Features:** Feature cards met iconen en beschrijvingen
- **Stats Counter:** Statistieken sectie met tellers
- **Widget Areas:** Sidebar en 3 footer widget areas
- **Custom Menus:** Primary en footer navigatie met dropdown support
- **Featured Images:** Custom image sizes voor optimale weergave
- **Custom Logo:** Upload via WordPress Customizer
- **Customizer Opties:** Primaire kleur, accent kleur, hero tekst, footer tekst
- **SEO Friendly:** Semantic HTML5 met correcte heading hiërarchie
- **Accessibility Ready:** ARIA labels, keyboard navigatie, screen reader support
- **Plugin Integratie:** Naadloze samenwerking met Gym Community Plugin

## Installatie

1. Upload de `gym-community-theme` folder naar `wp-content/themes/`
2. Activeer het thema via **Weergave > Thema's**
3. Installeer en activeer de **Gym Community Plugin**
4. Configureer menu's via **Weergave > Menu's**
5. Pas branding aan via **Weergave > Customizer**

## Template Bestanden

### Basis Templates
- `index.php` - Fallback template
- `front-page.php` - Homepage met hero, features, stats, activiteiten, reviews en CTA
- `single.php` - Single post template
- `page.php` - Pagina template
- `archive.php` - Standaard archief template
- `search.php` - Zoekresultaten
- `404.php` - Foutpagina met Apex Athletes branding
- `header.php` - Header met sticky navigatie en mobiel menu
- `footer.php` - Footer met widget areas en branding
- `sidebar.php` - Zijbalk template

### Custom Post Type Templates
- `archive-gym_activity.php` - Activiteiten archief met grid layout, metadata, badges en beschikbaarheid
- `archive-gym_review.php` - Reviews archief met grid layout, sterrenratings en productinfo
- `single-gym_activity.php` - Activiteit detail met inschrijfformulier, trainer, locatie en boekingsstatus
- `single-gym_review.php` - Review detail met pros/cons, productlink en gerelateerde reviews

### Ondersteunende Bestanden
- `functions.php` - Theme setup, Google Fonts, Customizer, widgets, image sizes
- `style.css` - Volledige stylesheet met CSS custom properties

## Customizer Opties

| Optie                     | Sectie               | Beschrijving                              |
|---------------------------|----------------------|-------------------------------------------|
| Primary Color             | Apex Athletes Kleuren | Hoofdkleur voor headers en tekst          |
| Accent Color              | Apex Athletes Kleuren | Accentkleur voor knoppen en highlights    |
| Hero Subtitle             | Apex Athletes Teksten | Ondertitel op de homepage hero sectie     |
| Footer Text               | Apex Athletes Teksten | Extra tekst in de footer                  |

## Widget Areas

1. **Sidebar** - Zijbalk voor posts en pagina's
2. **Footer Widget Area 1** - Eerste footer kolom
3. **Footer Widget Area 2** - Tweede footer kolom
4. **Footer Widget Area 3** - Derde footer kolom

## Menu Locaties

1. **Primary Menu** - Hoofdnavigatie in de header (met dropdown support)
2. **Footer Menu** - Navigatie links in de footer

## Custom Image Sizes

- `gym-community-featured` - 800x400px (hero en featured images)
- `gym-community-thumbnail` - 300x200px (kaarten en thumbnails)

## front-page.php Secties

De homepage bevat de volgende secties:

1. **Hero Section** - Gradient achtergrond, titel, ondertitel (Customizer), CTA knoppen
2. **Community Features** - 4 feature cards met iconen (Activiteiten, Reviews, Community, Ondersteuning)
3. **Stats Counter** - 4 statistieken (leden, activiteiten, reviews, trainers)
4. **Aankomende Activiteiten** - 3 meest recente activiteiten met metadata
5. **Laatste Reviews** - 3 meest recente reviews met sterrenratings
6. **Laatste Nieuws** - 3 recente blog posts
7. **CTA Sectie** - Call-to-action met gradient achtergrond

## Plugin Integratie

Het thema is ontworpen voor naadloze samenwerking met de **Gym Community Plugin** (v2.0.0+):

- Archief templates tonen plugin metadata (datum, trainer, capaciteit, rating, etc.)
- Single templates gebruiken plugin shortcodes voor inschrijfformulieren
- Homepage haalt dynamisch activiteiten en reviews op
- CSS custom properties worden gedeeld tussen thema en plugin
- Plugin CSS gebruikt `var()` fallbacks voor standalone gebruik

## Browser Support

- Chrome (laatste 2 versies)
- Firefox (laatste 2 versies)
- Safari (laatste 2 versies)
- Edge (laatste 2 versies)
- iOS Safari en Android Chrome

## Changelog

### Version 2.0.0 - Apex Athletes Branding
- Volledige herwerking met Apex Athletes huisstijl
- CSS custom properties voor alle kleuren, fonts, spacing en shadows
- Google Fonts integratie (Oswald + Montserrat)
- WordPress Customizer opties (kleuren, teksten)
- Dynamische CSS output via `wp_head`
- Nieuwe `front-page.php` met 7 secties
- `archive-gym_activity.php` en `archive-gym_review.php` templates
- Bijgewerkte `single-gym_activity.php` met branding en inschrijfformulier
- Bijgewerkte `single-gym_review.php` met pros/cons en gerelateerde reviews
- `404.php` met Apex Athletes branding en navigatie
- `header.php` met sticky navigatie, mobiel menu en ARIA labels
- `footer.php` met widget areas, branding en dynamische footer tekst
- Responsive design met breakpoints op 1024px, 768px en 480px
- Utility classes voor margins, padding en tekst

### Version 1.0.0
- Initial release
- Responsive design
- Custom templates
- Widget areas
- Navigation menus
- Customizer options

## Credits

- **Ontwikkeld door:** Apex Athletes Development
- **Project:** DevSkills WordPress Thema-ontwikkeling
- **Jaar:** 2025
- **Fonts:** Google Fonts (Oswald, Montserrat)
- **Icons:** Emoji-based iconen (geen externe icon library vereist)

## Licentie

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

Dit project is ontwikkeld voor educatieve doeleinden als onderdeel van een software development opleiding.
