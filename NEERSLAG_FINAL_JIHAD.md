# EINDPROJECT: TICKETHUB
**Opleiding:** Full Stack Web Developer
**Instelling:** Syntra West
**Cursist:** Jihad Alghoul
**Academiejaar:** 2025-2026

---

## Eigen Verklaring van Originaliteit
Ik, Jihad Alghoul, verklaar hierbij dat dit eindproject "Tickethub" mijn eigen originele werk is. Alle bronnen die zijn geraadpleegd en alle hulpmiddelen die zijn gebruikt (inclusief AI-assistentie voor code-optimalisatie en documentatie-ondersteuning), zijn op een eerlijke en transparante wijze vermeld in deze neerslag.

---

## 1. Projectomschrijving - Doelgroep
Tickethub richt zich op een tweeledige doelgroep. Aan de **B2B-zijde** bedienen we evenementenorganisatoren die behoefte hebben aan een robuust, intuïtief en betrouwbaar platform voor het beheren van hun kaartverkoop en real-time toegangscontrole. Aan de **B2C-zijde** focust het platform op de eindgebruiker (de bezoeker), voor wie een vlotte, veilige en frictieloze checkout-ervaring essentieel is om met vertrouwen tickets aan te kopen.

[Voeg hier screenshot 1: Homepage in]

## 2. Scope - Wat bewust NIET werd gebouwd
Om de integriteit van de database en de betrouwbaarheid van de kernfunctionaliteiten te waarborgen, zijn bepaalde features bewust buiten de huidige scope gehouden. Een interne chatfunctie of een native mobiele app voor kopers zijn niet geïmplementeerd om de focus te behouden op het perfectioneren van de **atomische ticketreserveringen** en de Stripe-integratie. De prioriteit lag bij een stabiele webshop boven een overvloed aan secundaire features.

## 3. Analyse - Gebruikersrollen en User Stories
*   **Admin:** 
    *   *Als Admin wil ik uitbetalingsverzoeken goedkeuren zodat ik controle houd over de geldstroom.*
    *   *Als Admin wil ik platformstatistieken inzien zodat ik de groei van Tickethub kan monitoren.*
*   **Organizer:** 
    *   *Als Organisator wil ik een real-time dashboard met verkoopcijfers zodat ik mijn marketingstrategie kan aanpassen.*
    *   *Als Organisator wil ik tickets kunnen scannen via de browser zodat ik een snelle check-in kan garanderen.*
*   **Customer:** 
    *   *Als Klant wil ik tickets reserveren voor 10 minuten zodat ik rustig mijn betaling kan afronden.*
    *   *Als Klant wil ik mijn tickets downloaden als PDF zodat ik ze offline kan tonen aan de ingang.*

## 4. Technische Uitwerking - Databank en Architectuur
Het project hanteert een **Service Layer architectuur**. Logica is uit de controllers onttrokken en ondergebracht in services zoals de `TicketReservationService` en `OrganizerStatsService`. Dit voorkomt "Fat Controllers" en verhoogt de testbaarheid van de business logica.

**Belangrijkste tabellen:**
*   `Users`: Beheer van accounts en rollen via Middleware.
*   `Events`: Hoofdentiteit (One-to-Many met TicketTypes).
*   `Reservations`: Tijdelijke locks om overbooking te voorkomen.
*   `Tickets`: Unieke bewijzen met UUID-identificatie.

[Voeg hier screenshot 2: Database Schema in]

## 5. Ontwerp en UX - Motivatie en Toegankelijkheid
Gekozen is voor **Flux UI** in combinatie met een modern **Dark Mode** thema en **Glassmorphism** effecten. Dit creëert een premium uitstraling. Wat betreft toegankelijkheid (A11y), is er strikt gelet op kleurcontrasten en is de interface volledig responsief volgens een **mobile-first** benadering.

[Voeg hier screenshot 3: Dashboard interface in]

## 6. Testing en Kwaliteitscontrole
Met **Pest PHP** zijn 129 tests uitgevoerd (100% pass rate). Een kritieke bug die werd opgelost was een 'race condition' waarbij twee gebruikers gelijktijdig het laatste ticket konden kopen. Dit werd verholpen door atomische database locks te introduceren.

[Voeg hier screenshot 4: Pest testresultaten (groen) in]

## 7. Reflectie
De grootste uitdaging was de integratie van Stripe Webhooks in een lokale omgeving. Het correct mappen van asynchrone betaalsignalen naar de database-status vereiste een steile leercurve, maar resulteerde in een zeer robuust betaalsysteem.
