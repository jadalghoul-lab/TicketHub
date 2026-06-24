# 🎫 TicketHub - Premium SaaS Ticketing Platform

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-626CD9?style=for-the-badge&logo=Stripe&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-F1E852?style=for-the-badge&logo=php&logoColor=black)
![Redis](https://img.shields.io/badge/redis-%23DD0031.svg?style=for-the-badge&logo=redis&logoColor=white)

**TicketHub** is een geavanceerd, multi-tenant SaaS (Software as a Service) platform voor de verkoop en het beheer van evenemententickets. Dit project is ontwikkeld als eindwerk voor het **Traject B (Full Stack Web Developer)** aan Syntra West.

Het platform verbindt evenementenorganisatoren (B2B) met eindgebruikers (B2C) via een veilig, snel en robuust ecosysteem.

---

## ✨ Kernfunctionaliteiten

### 👔 Voor de Organisator (Organizer Module)
- **Uitgebreid Dashboard:** Real-time inzicht in verkoopcijfers, omzet en bezoekersstatistieken.
- **Evenementenbeheer:** Volledige CRUD-operaties voor evenementen, locaties (venues) en prijscategorieën.
- **Ticket Validatie (Scanner):** Een ingebouwde QR-scanner, rechtstreeks in de browser, voor veilige check-ins aan de deur (voorkomt fraude of dubbel gebruik).
- **Financieel Beheer:** Volledige integratie voor terugbetalingsaanvragen (Refunds), kortingscodes (Coupons) en uitbetalingsverzoeken (Payouts).

### 👥 Voor de Klant (Customer Portal)
- **Frictieloze Checkout:** Een vlotte en veilige aankoopervaring, rechtstreeks aangedreven door **Stripe**.
- **Veilig Ticket Systeem:** Tickets bevatten cryptografisch unieke UUID's en dynamische QR-codes. Ze worden na aankoop automatisch per e-mail (als PDF) verstuurd via asynchrone queue-workers.
- **Geavanceerd Zoeken:** Real-time filteren op categorie, stad of datum dankzij de reactieve kracht van Livewire.

### 🛡️ Voor de Beheerder (Admin)
- Globaal inzicht in alle organisatoren en de totale platformomzet.
- Goedkeuring (of weigering) van nieuwe organisatoren en uitbetalingen om de kwaliteit en veiligheid van het platform te waarborgen.

---

## 🛠️ Technologische Stack

- **Backend:** Laravel 11/10 (PHP 8.3+)
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS, Flux UI (Inclusief native Dark Mode en Skeleton Loading patronen).
- **Database:** MySQL
- **Betalingen:** Stripe API & **Stripe Webhooks** (Asynchrone betalingsverificatie).
- **Testing:** Pest PHP
- **Achtergrondprocessen:** Laravel Horizon & Redis voor high-performance queue-management (e-mails en PDF-generatie).

---

## 🏗️ Software Architectuur & Best Practices

Om te voldoen aan de hoge eisen van een Traject B eindproject, is er strikt vastgehouden aan moderne en robuuste ontwerppatronen:

1. **Service Layer Pattern:** 'Fat controllers' zijn actief vermeden. Complexe bedrijfslogica (zoals het verwerken van bestellingen, het genereren van QR-codes en het berekenen van statistieken) is geabstraheerd naar herbruikbare Services (bijv. `TicketReservationService`).
2. **Atomic Locks & Race Conditions:** Om "overbooking" te voorkomen bij zeer populaire evenementen, wordt er op databaseniveau gebruik gemaakt van atomic locks. Dit garandeert dat twee gebruikers nooit gelijktijdig hetzelfde laatste ticket kunnen afrekenen.
3. **Multi-Tenancy:** Strikte data-isolatie via Eloquent Global Scopes en Policies zorgt ervoor dat organisatoren uitsluitend toegang hebben tot hun eigen data.
4. **Stripe Webhooks:** Betalingen worden 100% asynchroon afgehandeld. De orderstatus in de database wordt pas definitief gemaakt na ontvangst van een veilige en geverifieerde `checkout.session.completed` webhook vanuit de Stripe servers.
5. **High-Performance Queues & Monitoring:** **Redis** functioneert als de in-memory message broker. **Laravel Horizon** is geïmplementeerd om background jobs (zoals PDF-generatie en Stripe webhooks) visueel te monitoren, doorvoer (throughput) te analyseren en workers dynamisch te balanceren.

---

## 🚦 Test Driven (QA)

Kwaliteitsgarantie en stabiliteit staan centraal in de oplevering van dit project. Er is een uitgebreide en rigoureuze test suite geschreven met **Pest PHP**.

- **129 Tests geschreven**
- **307 Assertions gecontroleerd**
- **100% Pass rate**

Er is diepgaand getest op policy-restricties, Stripe webhook-flows, rol-gebaseerde toegang, en dataintegriteit (waaronder de "scan once only" logica).

---

## 🚀 Installatiegids voor Evaluatoren (Jury)

Volg deze stappen om het project lokaal uit te voeren ter evaluatie:

### Vereisten
- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL of vergelijkbare database
- Stripe Account (Test Mode)
- Redis Server (Actief op de achtergrond)

### Stappen
1. **Kloon de repository:**
   ```bash
   git clone https://github.com/jadalghoul-lab/TicketHub.git
   cd TicketHub
   ```
2. **Installeer afhankelijkheden:**
   ```bash
   composer install
   npm install && npm run build
   ```
3. **Omgevingsvariabelen instellen:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Vul de `.env` aan met uw lokale databasegegevens en Stripe API sleutels (`STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`).*

4. **Database & Seeding:**
   ```bash
   php artisan migrate --seed
   ```
   *De seeder (die ontworpen is tijdens Sprint 9) genereert automatisch realistische testaccounts en evenementdata.*

5. **Start de applicatie en workers:**
   ```bash
   php artisan serve
   ```
   *Tip: Start Laravel Horizon (`php artisan horizon`) in een aparte terminal om de krachtige Redis-gebaseerde queue workers te activeren voor asynchrone e-mails en webhooks!*

---

## 🎓 Over dit Project

Dit project is met trots ontwikkeld door **Jihad Alghoul** als bewijs van vaardigheid in Full-Stack webontwikkeling gedurende het academiejaar 2025-2026. Alle code, gemaakte keuzes rondom architectuur, en documentatie vertegenwoordigen een origineel en zelfstandig uitgewerkt eindproject.
