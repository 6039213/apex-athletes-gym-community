# Testing Checklist - Gym Community Website

## Pre-Deployment Testing Checklist

Gebruik deze checklist om alle functionaliteit te testen voordat je de website live zet.

---

## 1. WordPress Basis Functionaliteit

### Admin Toegang
- [ ] Inloggen op /wp-admin werkt
- [ ] Dashboard laadt correct
- [ ] Alle menu items zijn zichtbaar
- [ ] Gebruikersrollen werken correct (admin, editor, etc.)

### Content Management
- [ ] Nieuwe pagina aanmaken werkt
- [ ] Nieuwe post aanmaken werkt
- [ ] Media uploaden werkt
- [ ] Featured images toewijzen werkt
- [ ] Content editor (Gutenberg/Classic) werkt

---

## 2. Thema Functionaliteit

### Algemeen
- [ ] Thema is geactiveerd
- [ ] Homepage laadt correct
- [ ] Alle pagina's laden correct
- [ ] Geen PHP errors zichtbaar
- [ ] Geen JavaScript console errors

### Navigation
- [ ] Primary menu toont correct
- [ ] Footer menu toont correct
- [ ] Mobile menu werkt (hamburger icon)
- [ ] Submenu's werken (indien aanwezig)
- [ ] Alle menu links werken

### Templates
- [ ] Homepage (front-page.php) toont correct
- [ ] Standaard pagina (page.php) toont correct
- [ ] Single post (single.php) toont correct
- [ ] Archive pagina (archive.php) toont correct
- [ ] Search pagina (search.php) toont correct
- [ ] 404 pagina toont correct

### Responsive Design
- [ ] Desktop (1920px) - alles zichtbaar en goed uitgelijnd
- [ ] Laptop (1366px) - layout past zich aan
- [ ] Tablet (768px) - mobile menu verschijnt
- [ ] Mobile (375px) - alles stapelt verticaal
- [ ] Afbeeldingen schalen correct
- [ ] Tekst blijft leesbaar op alle formaten

### Widgets
- [ ] Sidebar widgets tonen correct
- [ ] Footer widgets tonen correct
- [ ] Widgets zijn configureerbaar in admin

### Styling
- [ ] Kleuren komen overeen met design
- [ ] Fonts laden correct
- [ ] Buttons hebben hover states
- [ ] Links hebben hover states
- [ ] Spacing en padding zijn consistent

---

## 3. Plugin Functionaliteit

### Plugin Activatie
- [ ] Gym Community Plugin is geactiveerd
- [ ] Geen errors bij activatie
- [ ] Database tabel aangemaakt (wp_gym_registrations)
- [ ] Custom Post Types geregistreerd

### Gym Activities

#### Aanmaken & Bewerken
- [ ] Nieuwe activiteit aanmaken werkt
- [ ] Alle meta fields opslaan correct:
  - [ ] Date
  - [ ] Time
  - [ ] Trainer
  - [ ] Capacity
  - [ ] Duration
  - [ ] Location
  - [ ] Difficulty
- [ ] Activity Types taxonomie werkt
- [ ] Featured image uploaden werkt
- [ ] Activiteit bewerken werkt
- [ ] Activiteit verwijderen werkt

#### Frontend Display
- [ ] Single activity pagina toont correct
- [ ] Alle details worden getoond
- [ ] Featured image toont
- [ ] Activity type toont
- [ ] Inschrijfformulier toont (als niet vol)
- [ ] "Fully Booked" melding toont (als vol)

#### Admin Columns
- [ ] Custom columns tonen in admin lijst
- [ ] Date & Time kolom toont correct
- [ ] Trainer kolom toont correct
- [ ] Capacity kolom toont correct
- [ ] Registrations kolom toont correct aantal

### Reviews

#### Aanmaken & Bewerken
- [ ] Nieuwe review aanmaken werkt
- [ ] Alle meta fields opslaan correct:
  - [ ] Product name
  - [ ] Rating (1-5)
  - [ ] Reviewer name
  - [ ] Reviewer email
  - [ ] Product link
  - [ ] Pros
  - [ ] Cons
  - [ ] Verified checkbox
- [ ] Review Categories taxonomie werkt
- [ ] Featured image uploaden werkt
- [ ] Review bewerken werkt
- [ ] Review verwijderen werkt

#### Frontend Display
- [ ] Single review pagina toont correct
- [ ] Rating sterren tonen correct
- [ ] Product naam toont
- [ ] Verified badge toont (indien aangevinkt)
- [ ] Pros/Cons sectie toont
- [ ] Product link button werkt
- [ ] Related reviews tonen

#### Admin Columns
- [ ] Rating kolom toont sterren
- [ ] Product kolom toont correct
- [ ] Reviewer kolom toont correct
- [ ] Status kolom toont correct

### Inschrijfsysteem

#### Frontend Formulier
- [ ] Formulier toont op activity pagina
- [ ] Alle velden zijn aanwezig (name, email, phone)
- [ ] Required fields valideren
- [ ] Email validatie werkt
- [ ] AJAX submission werkt (geen page reload)
- [ ] Success message toont na inschrijving
- [ ] Error messages tonen bij problemen
- [ ] Formulier reset na success

#### Capaciteit Controle
- [ ] Kan niet inschrijven als activiteit vol is
- [ ] "Fully Booked" melding toont
- [ ] Inschrijfformulier verdwijnt bij vol
- [ ] Capaciteit teller update correct

#### Duplicate Prevention
- [ ] Kan niet 2x inschrijven met zelfde email
- [ ] Error message toont bij duplicate

#### Email Notificaties
- [ ] Bevestigingsmail naar gebruiker
- [ ] Email bevat correcte activiteit details
- [ ] Email bevat datum, tijd, trainer, locatie
- [ ] Admin notificatie (indien ingeschakeld)

#### Admin Registrations Page
- [ ] Registrations pagina toont
- [ ] Alle inschrijvingen zichtbaar
- [ ] Filter op activiteit werkt
- [ ] Alle kolommen tonen correct data
- [ ] Delete functie werkt
- [ ] Bevestiging bij delete

### Shortcodes

#### [gym_activities]
- [ ] Shortcode toont activiteiten lijst
- [ ] `limit` parameter werkt
- [ ] `type` parameter filtert correct
- [ ] `upcoming` parameter werkt
- [ ] Featured images tonen
- [ ] Alle meta data toont
- [ ] "View Details" button werkt
- [ ] "Register Now" button werkt
- [ ] Styling is correct

#### [gym_schedule]
- [ ] Shortcode toont weekrooster
- [ ] `days` parameter werkt
- [ ] Activiteiten gegroepeerd per dag
- [ ] Datum headers tonen correct
- [ ] Tijd, titel, trainer tonen
- [ ] Links naar activiteiten werken

#### [recent_reviews]
- [ ] Shortcode toont reviews lijst
- [ ] `limit` parameter werkt
- [ ] `category` parameter filtert correct
- [ ] Rating sterren tonen
- [ ] Product naam toont
- [ ] Verified badge toont
- [ ] "Read Full Review" button werkt
- [ ] "Buy Now" button werkt (externe link)

#### [gym_registration_form]
- [ ] Shortcode toont formulier
- [ ] `activity_id` parameter werkt
- [ ] Formulier werkt (zie Inschrijfsysteem tests)

### Admin Settings

#### Settings Page
- [ ] Settings pagina toegankelijk
- [ ] Email notifications checkbox werkt
- [ ] Admin email field opslaat
- [ ] Auto-approve reviews checkbox werkt
- [ ] Registration limit field opslaat
- [ ] Save Settings button werkt
- [ ] Success message toont na save
- [ ] Statistics dashboard toont correct

#### Documentation Page
- [ ] Documentation pagina toegankelijk
- [ ] Alle shortcodes gedocumenteerd
- [ ] Voorbeelden tonen correct
- [ ] Uitleg is duidelijk

---

## 4. Database & Performance

### Database
- [ ] wp_gym_registrations tabel bestaat
- [ ] Tabel heeft correcte kolommen
- [ ] Data wordt correct opgeslagen
- [ ] Queries zijn geoptimaliseerd (geen N+1 queries)

### Performance
- [ ] Homepage laadt in < 3 seconden
- [ ] Activiteiten pagina laadt snel
- [ ] Reviews pagina laadt snel
- [ ] Geen memory errors in debug.log
- [ ] Afbeeldingen zijn geoptimaliseerd

---

## 5. Security

### Input Validation
- [ ] Alle formulieren valideren input
- [ ] XSS preventie (data escaping)
- [ ] SQL injection preventie (prepared statements)
- [ ] Nonce verificatie op alle forms
- [ ] Capability checks in admin

### File Security
- [ ] wp-config.php niet toegankelijk via browser
- [ ] .htaccess beveiligingsregels actief
- [ ] File permissions correct (755/644)
- [ ] Geen debug info zichtbaar op frontend

---

## 6. Browser Compatibility

Test in de volgende browsers:

### Desktop
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

### Mobile
- [ ] Chrome Mobile (Android)
- [ ] Safari Mobile (iOS)

### Specifieke Tests per Browser
- [ ] Layout ziet er correct uit
- [ ] JavaScript werkt (AJAX forms)
- [ ] CSS animaties werken
- [ ] Fonts laden correct
- [ ] Geen console errors

---

## 7. SEO & Accessibility

### SEO
- [ ] Page titles zijn uniek en beschrijvend
- [ ] Meta descriptions zijn ingevuld (Yoast SEO)
- [ ] H1 tags zijn aanwezig op elke pagina
- [ ] Heading hierarchy is logisch (H1 > H2 > H3)
- [ ] Alt text op alle afbeeldingen
- [ ] URLs zijn SEO-friendly (permalinks)
- [ ] XML sitemap gegenereerd

### Accessibility
- [ ] Keyboard navigatie werkt
- [ ] Focus states zijn zichtbaar
- [ ] ARIA labels aanwezig waar nodig
- [ ] Color contrast is voldoende
- [ ] Formulieren hebben labels
- [ ] Error messages zijn duidelijk

---

## 8. Content Testing

### Pagina's
- [ ] Homepage heeft content
- [ ] Over Ons pagina bestaat
- [ ] Contact pagina bestaat
- [ ] Activiteiten overzicht pagina
- [ ] Reviews overzicht pagina
- [ ] Privacy/Disclaimer pagina's (indien nodig)

### Test Data
- [ ] Minimaal 5 activiteiten aangemaakt
- [ ] Minimaal 5 reviews aangemaakt
- [ ] Minimaal 3 nieuwsberichten
- [ ] Activity Types aangemaakt (Cardio, Strength, Yoga, etc.)
- [ ] Review Categories aangemaakt (Equipment, Supplements, etc.)
- [ ] Test inschrijvingen gedaan

---

## 9. Email Testing

### Inschrijving Emails
- [ ] Email wordt verzonden
- [ ] Email komt aan in inbox (niet spam)
- [ ] Subject line is correct
- [ ] Email body bevat alle info
- [ ] Opmaak ziet er goed uit
- [ ] Links werken (indien aanwezig)

### Test met verschillende email providers
- [ ] Gmail
- [ ] Outlook
- [ ] Yahoo (optioneel)

---

## 10. Deployment Testing

### Pre-Deployment
- [ ] Alle code gecommit naar Git
- [ ] Database backup gemaakt
- [ ] Bestanden backup gemaakt
- [ ] wp-config.php voorbereid voor productie
- [ ] WP_DEBUG uitgeschakeld voor productie

### FTP Upload
- [ ] Alle bestanden geüpload
- [ ] wp-config.php correct geconfigureerd
- [ ] Database geïmporteerd
- [ ] URLs vervangen in database

### Post-Deployment
- [ ] Website bereikbaar op live URL
- [ ] SSL certificaat actief (https://)
- [ ] Alle pagina's laden
- [ ] Inloggen werkt
- [ ] Thema geactiveerd
- [ ] Plugins geactiveerd
- [ ] Permalinks opnieuw opgeslagen
- [ ] Test inschrijving op live site

---

## 11. User Acceptance Testing

### Niet-technische Beheerder Test
- [ ] Kan inloggen
- [ ] Kan nieuwe activiteit aanmaken
- [ ] Kan activiteit bewerken
- [ ] Kan review aanmaken
- [ ] Kan inschrijvingen bekijken
- [ ] Kan menu's aanpassen
- [ ] Begrijpt de interface
- [ ] Gebruikershandleiding is duidelijk

### Eindgebruiker Test
- [ ] Kan activiteiten bekijken
- [ ] Kan zich inschrijven voor activiteit
- [ ] Ontvangt bevestigingsmail
- [ ] Kan reviews lezen
- [ ] Kan navigeren door website
- [ ] Formulieren werken intuïtief
- [ ] Website laadt snel genoeg

---

## 12. Documentation Check

### Code Documentatie
- [ ] PHPDoc comments aanwezig
- [ ] Inline comments voor complexe code
- [ ] README.md compleet
- [ ] Changelog bijgewerkt

### Gebruikersdocumentatie
- [ ] Gebruikershandleiding compleet
- [ ] Screenshots toegevoegd (optioneel)
- [ ] FAQ sectie ingevuld
- [ ] Contact informatie correct

### Technische Documentatie
- [ ] OTAP configuratie gedocumenteerd
- [ ] Deployment guide compleet
- [ ] Database schema gedocumenteerd
- [ ] Plugin documentatie compleet

---

## 13. Final Checks

### Algemeen
- [ ] Geen "Lorem Ipsum" placeholder tekst
- [ ] Geen test/dummy content zichtbaar
- [ ] Alle links werken (geen 404's)
- [ ] Alle afbeeldingen laden
- [ ] Geen broken images
- [ ] Footer copyright jaar is correct
- [ ] Contact informatie is correct

### Performance
- [ ] Google PageSpeed Insights score > 70
- [ ] GTmetrix score > B
- [ ] Geen grote afbeeldingen (> 500KB)
- [ ] Caching werkt (indien ingeschakeld)

### Backup & Recovery
- [ ] Backup systeem ingesteld
- [ ] Restore getest (optioneel)
- [ ] Backup locatie veilig

---

## Bug Tracking

Gebruik deze tabel om bugs te tracken tijdens testing:

| Bug # | Pagina/Feature | Beschrijving | Prioriteit | Status | Opgelost door |
|-------|----------------|--------------|------------|--------|---------------|
| 1     |                |              | High/Med/Low | Open/Fixed |               |
| 2     |                |              |            |        |               |

### Prioriteit Levels
- **High:** Blokkeert deployment, moet direct opgelost
- **Medium:** Belangrijk maar niet kritiek
- **Low:** Nice to have, kan later

---

## Sign-off

### Testing Voltooid door:
- **Naam:** ___________________
- **Datum:** ___________________
- **Handtekening:** ___________________

### Goedgekeurd voor Deployment:
- **Naam:** ___________________
- **Datum:** ___________________
- **Handtekening:** ___________________

---

## Notes

Gebruik deze ruimte voor extra opmerkingen of bevindingen tijdens testing:

```
[Notities hier]
```

---

**Versie:** 1.0  
**Laatst bijgewerkt:** Maart 2026
