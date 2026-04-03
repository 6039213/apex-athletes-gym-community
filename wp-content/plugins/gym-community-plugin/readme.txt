=== Gym Community Plugin ===
Contributors: apexathletes
Tags: gym, fitness, activities, reviews, registrations, community, sports
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Een complete WordPress plugin voor het beheren van gym activiteiten, reviews en inschrijvingen.

== Description ==

Gym Community Plugin is een complete oplossing voor fitnesscentra en sportscholen om hun activiteiten, reviews en ledeninschrijvingen te beheren. Ontwikkeld als custom plugin voor het Apex Athletes Gym Community project.

**Belangrijkste functies:**

* **Gym Activiteiten** - Beheer lessen en trainingen met datum, tijd, trainer, capaciteit en moeilijkheidsgraad
* **Review Systeem** - Product en dienst reviews met 5-sterren rating
* **Inschrijfsysteem** - Online inschrijvingen met AJAX en capaciteit tracking
* **Email Notificaties** - Automatische bevestigingsmails
* **Shortcodes** - Eenvoudig content tonen op elke pagina
* **Admin Dashboard** - Statistieken, CSV export en instellingen
* **Hooks & Filters** - Uitbreidbaar via WordPress acties en filters

**Shortcodes:**

* `[gym_activities]` - Toon activiteiten lijst
* `[gym_schedule]` - Toon weekrooster
* `[recent_reviews]` - Toon recente reviews
* `[gym_registration_form]` - Inschrijfformulier

== Installation ==

1. Upload de plugin naar `/wp-content/plugins/gym-community-plugin/`
2. Activeer de plugin via het 'Plugins' menu in WordPress
3. Configureer instellingen via Gym Community > Settings
4. Maak activiteiten aan via Gym Activities > Nieuwe toevoegen
5. Gebruik shortcodes om content op pagina's te tonen

== Frequently Asked Questions ==

= Hoe schrijf ik me in voor een activiteit? =

Ga naar een activiteit pagina en vul het inschrijfformulier in. Je ontvangt automatisch een bevestigingsmail.

= Kan ik het aantal inschrijvingen limiteren? =

Ja, in de plugin instellingen kun je een maximum aantal inschrijvingen per gebruiker instellen.

= Werkt de plugin zonder het Apex Athletes thema? =

Ja, de plugin heeft eigen CSS en werkt met elk thema. Voor optimale styling wordt het Apex Athletes thema aanbevolen.

= Hoe exporteer ik inschrijvingen? =

Ga naar Gym Community > Settings en klik op 'Exporteer Registraties' voor een CSV bestand.

== Screenshots ==

1. Admin settings pagina met statistieken
2. Activiteit details met meta velden
3. Inschrijf formulier op frontend
4. Reviews overzicht met ratings

== Changelog ==

= 2.0.0 =
* Major release met Apex Athletes branding
* CSS custom properties voor consistente styling
* Uitgebreide admin dashboard met statistieken
* CSV export functionaliteit voor registraties
* Custom hooks en filters voor extensibiliteit
* Verbeterde email notificaties
* Nederlandse lokalisatie

= 1.0.0 =
* Eerste release
* Custom post types voor activiteiten en reviews
* Basic inschrijfsysteem met database tabel
* Shortcodes voor frontend weergave

== Upgrade Notice ==

= 2.0.0 =
Deze versie bevat belangrijke styling wijzigingen. Controleer je pagina's na updaten.

== Arbitrary section ==

**Ontwikkeld door:** Apex Athletes Development Team
**Project:** DevSkills WordPress Thema-ontwikkeling
**Versie:** 2.0.0

== Hooks and Filters ==

**Acties:**
* `gym_community_before_registration` - Voor registratie verwerking
* `gym_community_after_registration` - Na succesvolle registratie
* `gym_community_admin_loaded` - Wanneer admin is geladen

**Filters:**
* `gym_community_confirmation_email` - Pas email content aan
* `gym_community_registration_limit` - Wijzig registratie limiet per gebruiker
