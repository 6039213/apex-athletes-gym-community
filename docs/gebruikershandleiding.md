# Gym Community Website - Gebruikershandleiding

**Voor niet-technische beheerders**

## Inhoudsopgave

1. [Inloggen](#inloggen)
2. [Dashboard Overzicht](#dashboard-overzicht)
3. [Gym Activiteiten Beheren](#gym-activiteiten-beheren)
4. [Reviews Beheren](#reviews-beheren)
5. [Inschrijvingen Bekijken](#inschrijvingen-bekijken)
6. [Pagina's en Nieuws](#paginas-en-nieuws)
7. [Menu's Aanpassen](#menus-aanpassen)
8. [Thema Instellingen](#thema-instellingen)
9. [Plugin Instellingen](#plugin-instellingen)
10. [Veelgestelde Vragen](#veelgestelde-vragen)

---

## Inloggen

1. Ga naar: `https://jouwdomein.nl/wp-admin`
2. Vul je gebruikersnaam en wachtwoord in
3. Klik op "Log In"

**Tip:** Gebruik een wachtwoordmanager om je wachtwoord veilig op te slaan.

---

## Dashboard Overzicht

Na het inloggen zie je het WordPress Dashboard. Hier vind je:

- **Linkermenu:** Alle beheerfuncties
- **Dashboard widgets:** Snelle statistieken en updates
- **Bovenaan:** Snelkoppelingen naar belangrijke pagina's

### Belangrijkste menu items:

- **Posts:** Nieuwsberichten
- **Pages:** Vaste pagina's (Over Ons, Contact, etc.)
- **Gym Activities:** Gym lessen en trainingen
- **Reviews:** Product en dienst reviews
- **Appearance:** Uiterlijk van de website
- **Gym Community:** Plugin instellingen

---

## Gym Activiteiten Beheren

### Nieuwe Activiteit Toevoegen

1. Klik in het linkermenu op **Gym Activities > Add New**
2. Vul de gegevens in:

#### Basis Informatie
- **Titel:** Naam van de activiteit (bijv. "Yoga voor Beginners")
- **Beschrijving:** Uitgebreide beschrijving van de activiteit (gebruik de editor)
- **Featured Image:** Klik "Set featured image" en upload een foto

#### Activity Details (rechts in de pagina)
- **Date:** Datum van de activiteit
- **Time:** Starttijd (bijv. 19:00)
- **Trainer:** Naam van de trainer
- **Max Capacity:** Maximum aantal deelnemers (bijv. 20)
- **Duration:** Duur in minuten (bijv. 60)
- **Location:** Locatie (bijv. "Studio 1")
- **Difficulty Level:** Kies het niveau:
  - Beginner
  - Intermediate
  - Advanced
  - All Levels

#### Activity Type
- Rechts onder "Activity Types"
- Vink het juiste type aan (Cardio, Strength, Yoga, etc.)
- Geen type? Klik "+ Add New Activity Type" om er een toe te voegen

3. Klik op **Publish** om de activiteit live te zetten

### Activiteit Bewerken

1. Ga naar **Gym Activities > All Activities**
2. Zoek de activiteit die je wilt bewerken
3. Klik op de titel of "Edit"
4. Pas de gegevens aan
5. Klik op **Update**

### Activiteit Verwijderen

1. Ga naar **Gym Activities > All Activities**
2. Hover over de activiteit
3. Klik op "Trash"

**Let op:** Verwijderde activiteiten gaan naar de prullenbak en kunnen binnen 30 dagen worden hersteld.

### Inschrijvingen Bekijken per Activiteit

1. Ga naar **Gym Activities > All Activities**
2. In de kolom "Registrations" zie je hoeveel mensen ingeschreven zijn
3. Voor details: **Gym Activities > Registrations**

---

## Reviews Beheren

### Nieuwe Review Toevoegen

1. Klik op **Reviews > Add New**
2. Vul de gegevens in:

#### Basis Informatie
- **Titel:** Korte samenvatting van de review (bijv. "Geweldige proteïne shake")
- **Review Tekst:** Volledige review (gebruik de editor)
- **Featured Image:** Upload een foto van het product

#### Review Details
- **Product/Service Name:** Naam van het product (bijv. "Whey Protein Gold")
- **Rating:** Kies 1-5 sterren
  - 1 = Poor
  - 2 = Fair
  - 3 = Good
  - 4 = Very Good
  - 5 = Excellent
- **Reviewer Name:** Naam van de reviewer (optioneel)
- **Reviewer Email:** Email van de reviewer (optioneel)
- **Product Link:** Link naar webshop waar product te koop is
- **Pros:** Voordelen van het product (elk op nieuwe regel)
- **Cons:** Nadelen van het product (elk op nieuwe regel)
- **Verified Purchase:** Vink aan als dit een geverifieerde aankoop is

#### Review Category
- Kies de juiste categorie (Equipment, Supplements, Services, etc.)

3. Klik op **Publish** of **Save Draft**

**Tip:** Gebruik "Save Draft" om reviews eerst te controleren voordat je ze publiceert.

### Review Modereren

Nieuwe reviews kunnen als "Draft" worden opgeslagen:

1. Ga naar **Reviews > All Reviews**
2. Reviews met status "Draft" zijn nog niet zichtbaar
3. Open de review, controleer de inhoud
4. Klik op **Publish** om de review live te zetten

### Review Bewerken of Verwijderen

Werkt hetzelfde als bij activiteiten (zie hierboven).

---

## Inschrijvingen Bekijken

### Alle Inschrijvingen

1. Ga naar **Gym Activities > Registrations**
2. Hier zie je een overzicht van alle inschrijvingen met:
   - Naam van de persoon
   - Email adres
   - Telefoonnummer
   - Voor welke activiteit
   - Datum van inschrijving
   - Status

### Filteren op Activiteit

1. Gebruik het dropdown menu bovenaan
2. Selecteer een specifieke activiteit
3. De lijst wordt automatisch gefilterd

### Inschrijving Verwijderen

1. Klik op "Delete" bij de inschrijving
2. Bevestig de actie

**Let op:** Dit kan niet ongedaan worden gemaakt!

### Inschrijvingen Exporteren

1. Gebruik de print functie van je browser (Ctrl+P / Cmd+P)
2. Kies "Save as PDF"
3. Of selecteer en kopieer de tabel naar Excel

---

## Pagina's en Nieuws

### Nieuwe Pagina Maken

1. Ga naar **Pages > Add New**
2. Vul titel en inhoud in
3. Gebruik de editor voor opmaak:
   - **B** = Bold (vet)
   - *I* = Italic (cursief)
   - Knoppen voor lijsten, links, afbeeldingen
4. Klik op **Publish**

### Nieuwsbericht Plaatsen

1. Ga naar **Posts > Add New**
2. Vul titel en inhoud in
3. Kies een categorie (rechts)
4. Upload een featured image
5. Klik op **Publish**

### Shortcodes Gebruiken

Shortcodes zijn speciale codes om content te tonen. Plak deze in de editor:

#### Activiteiten Tonen
```
[gym_activities limit="6"]
```
Toont 6 activiteiten

#### Weekrooster Tonen
```
[gym_schedule days="7"]
```
Toont rooster voor komende 7 dagen

#### Reviews Tonen
```
[recent_reviews limit="5"]
```
Toont 5 laatste reviews

#### Inschrijfformulier
```
[gym_registration_form]
```
Toont inschrijfformulier (alleen op activiteit pagina's)

**Voorbeeld pagina opbouw:**

```
Welkom bij onze gym!

Hier is ons weekrooster:
[gym_schedule]

Bekijk onze activiteiten:
[gym_activities limit="6" upcoming="yes"]
```

---

## Menu's Aanpassen

### Menu Bewerken

1. Ga naar **Appearance > Menus**
2. Selecteer "Primary Menu" of "Footer Menu"
3. Voeg items toe:
   - Vink pagina's aan in de linkerkolom
   - Klik "Add to Menu"
4. Sleep items om volgorde te wijzigen
5. Klik **Save Menu**

### Submenu Maken

1. Sleep een menu item iets naar rechts onder een ander item
2. Dit maakt het een submenu item
3. Klik **Save Menu**

### Custom Link Toevoegen

1. Open "Custom Links" in de linkerkolom
2. Vul URL en Link Text in
3. Klik "Add to Menu"

---

## Thema Instellingen

### Logo Uploaden

1. Ga naar **Appearance > Customize**
2. Klik op "Site Identity"
3. Klik "Select Logo"
4. Upload je logo (aanbevolen: 400x100 pixels)
5. Klik "Publish"

### Kleuren Aanpassen

1. Ga naar **Appearance > Customize**
2. Klik op "Colors"
3. Kies "Primary Color" (hoofdkleur van de website)
4. Klik op de kleur en kies een nieuwe
5. Klik "Publish"

### Widgets Toevoegen

Widgets zijn kleine blokken in de sidebar en footer.

1. Ga naar **Appearance > Widgets**
2. Sleep widgets naar de gewenste area:
   - **Sidebar:** Rechterkolom op pagina's
   - **Footer Widget Area 1, 2, 3:** Footer kolommen
3. Configureer de widget
4. Klik "Save"

**Handige widgets:**
- **Recent Posts:** Laatste nieuwsberichten
- **Categories:** Categorieën overzicht
- **Text:** Eigen tekst of HTML

---

## Plugin Instellingen

### Gym Community Instellingen

1. Ga naar **Gym Community > Settings**

#### Email Notifications
- Vink aan om emails te ontvangen bij nieuwe inschrijvingen

#### Admin Email
- Email adres waar notificaties naartoe gaan

#### Auto-approve Reviews
- Vink aan om reviews automatisch te publiceren
- Laat uit voor handmatige moderatie

#### Registration Limit per User
- Maximum aantal activiteiten per gebruiker

2. Klik **Save Settings**

### Documentatie Bekijken

1. Ga naar **Gym Community > Documentation**
2. Hier vind je uitleg over alle shortcodes en features

---

## Veelgestelde Vragen

### Hoe maak ik een activiteit die elke week terugkomt?

Momenteel moet je elke activiteit apart aanmaken. Kopieer een bestaande activiteit:
1. Open de activiteit
2. Kopieer alle informatie
3. Maak een nieuwe activiteit aan
4. Plak de informatie en pas de datum aan

### Hoe kan ik zien wie zich heeft ingeschreven?

Ga naar **Gym Activities > Registrations** voor een volledig overzicht.

### Wat als een activiteit vol is?

Het inschrijfformulier wordt automatisch verborgen als de capaciteit bereikt is. Er verschijnt een melding "Fully Booked".

### Kan ik inschrijvingen exporteren?

Ja, gebruik de print functie van je browser en sla op als PDF, of kopieer de tabel naar Excel.

### Hoe verwijder ik een inschrijving?

Ga naar **Gym Activities > Registrations** en klik op "Delete" bij de betreffende inschrijving.

### Hoe modereer ik reviews?

Stel in **Gym Community > Settings** de "Auto-approve Reviews" uit. Nieuwe reviews worden dan als "Draft" opgeslagen en moet je handmatig publiceren.

### Hoe voeg ik een nieuwe Activity Type toe?

Bij het bewerken van een activiteit, klik op "+ Add New Activity Type" onder de Activity Types box.

### Hoe verander ik de volgorde van menu items?

Ga naar **Appearance > Menus**, sleep items naar de gewenste positie en klik "Save Menu".

### Wat als ik per ongeluk iets verwijder?

Verwijderde items gaan naar de Trash en kunnen binnen 30 dagen worden hersteld:
1. Ga naar het betreffende menu item (Posts, Pages, Activities, etc.)
2. Klik op "Trash" bovenaan
3. Zoek het item en klik "Restore"

### Hoe maak ik een backup?

Vraag je hosting provider om regelmatige backups in te stellen, of gebruik een backup plugin zoals UpdraftPlus.

### Hoe update ik WordPress?

WordPress geeft een melding als er updates zijn:
1. Ga naar **Dashboard > Updates**
2. Klik "Update Now"
3. Wacht tot de update klaar is

**Let op:** Maak altijd eerst een backup!

### Waar vind ik hulp?

- Check deze handleiding
- Ga naar **Gym Community > Documentation** in WordPress
- Neem contact op met de website ontwikkelaar

---

## Handige Tips

### Content Schrijven

- **Gebruik duidelijke titels:** Beschrijvend en kort
- **Voeg afbeeldingen toe:** Visueel aantrekkelijker
- **Gebruik koppen:** H2, H3 voor structuur
- **Houd het scanbaar:** Korte paragrafen, bullet points
- **Call-to-action:** Moedig bezoekers aan om te registreren

### SEO (Zoekmachine Optimalisatie)

Als Yoast SEO geïnstalleerd is:
- Vul de "SEO title" in (onder de editor)
- Schrijf een "Meta description" (korte samenvatting)
- Gebruik relevante keywords in je tekst

### Afbeeldingen

- **Formaat:** JPG voor foto's, PNG voor logo's
- **Grootte:** Max 1200px breed voor snelle laadtijd
- **Bestandsnaam:** Beschrijvend (yoga-les-studio-1.jpg)
- **Alt text:** Beschrijving voor toegankelijkheid

### Regelmatig Onderhoud

- **Wekelijks:** Check nieuwe inschrijvingen
- **Wekelijks:** Voeg nieuwe activiteiten toe
- **Maandelijks:** Update nieuws/blog posts
- **Maandelijks:** Check en modereer reviews
- **Per kwartaal:** Verwijder oude activiteiten uit Trash
- **Per kwartaal:** Update plugin en WordPress

---

## Contact

Bij vragen of problemen, neem contact op met:

**Website Ontwikkelaar:** [Jouw Naam]  
**Email:** [jouw@email.nl]  
**Telefoon:** [Telefoonnummer]

---

**Versie:** 1.0  
**Laatst bijgewerkt:** Maart 2026
