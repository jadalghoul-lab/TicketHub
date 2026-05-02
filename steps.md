# steps.md

# ROADMAP — Premium Multi-Tenant Ticketing Platform (Traject B)

## Project Overview

Build a professional SaaS Ticketing Platform using Laravel + Livewire + Flux where multiple organizers can manage events, sell tickets, validate entries, track revenue, and manage customers.

This project is designed for **Traject B** and must demonstrate:

* Strong architecture
* Multi-tenancy
* Professional Laravel practices
* Payment integration
* Queues
* Reports
* Security
* Real business logic

---

# PHASE 1 — Project Setup

## Install Stack

* Laravel 13
* PHP 8.3+
* MySQL
* Node.js latest
* Composer

## Install Starter Kit

* Laravel Starter Kit with authentication

## Install Packages

* Livewire 3
* Flux UI
* stripe/stripe-php
* simplesoftwareio/simple-qrcode
* intervention/image
* spatie/laravel-permission (optional)

## Configure

* .env database
* mail settings
* queue driver
* storage link

## Result

Working authentication system with dashboard.

---

# PHASE 2 — Folder Architecture

Create clean structure:

app/

* Services
* Actions
* DTOs
* Jobs
* Mail
* Policies
* Enums
* Helpers
* Notifications

Goal:

Controllers stay thin.
Business logic lives in Services.

---

# PHASE 3 — Authentication & Roles

## Roles

* Admin
* Organizer
* Customer

## Tasks

* Seeder roles
* Middleware protection
* Policies
* Dashboard redirects per role

## Result

Secure role-based access.

---

# PHASE 4 — Multi-Tenant System

## Goal

Multiple organizers use same platform.

Each organizer sees only:

* their events
* their orders
* their tickets
* their analytics

Admin sees everything.

## Implementation

Use:

* organizer_id foreign keys
* policies
* scopes
* middleware

---

# PHASE 5 — Database Design

## Main Tables

users
organizers
events
venues
ticket_types
orders
order_items
tickets
payments
coupons
coupon_usages
refund_requests
scans
activity_logs

## Required

* foreign keys
* indexes
* soft deletes
* timestamps
* unique constraints

---

# PHASE 6 — Organizer Module

## Organizer Features

* create profile
* company name
* logo
* contact info
* payout email
* status active/inactive

Admin can approve organizers.

---

# PHASE 7 — Event Management

## CRUD Events

Fields:

* title
* slug
* description
* image
* category
* start_date
* end_date
* time
* venue
* city
* country
* capacity
* status

## Status

* draft
* published
* sold_out
* finished
* cancelled

## Features

* upload image
* publish/unpublish
* duplicate event

---

# PHASE 8 — Venue System

Optional but professional.

Fields:

* name
* address
* city
* country
* max_capacity

Reuse venue for many events.

---

# PHASE 9 — Ticket Types

Each event has many ticket types.

Examples:

* General
* VIP
* Student
* Early Bird

Fields:

* name
* price
* quantity
* sales_start
* sales_end
* max_per_order
* description

---

# PHASE 10 — Seating Categories (Premium)

Instead of complex seat map:

Create zones:

* Front Row
* Balcony
* VIP Zone
* Standing

Each zone has separate stock + price.

---

# PHASE 11 — Public Website

## Pages

* Home
* Upcoming events
* Categories
* Event details
* Search results
* Organizer page
* Contact page

## Event Page

Show:

* image
* date
* location
* available tickets
* organizer
* description
* buy now button

---

# PHASE 12 — Search & Filters

Allow filtering by:

* category
* city
* date
* price
* free / paid
* available only

---

# PHASE 13 — Checkout Flow

## Steps

1. Select ticket type
2. Select quantity
3. Apply coupon
4. Enter customer info
5. Review order
6. Pay

## Validate

* stock exists
* sales active
* quantity allowed

---

# PHASE 14 — Stripe Payment

## Required

Create Stripe Checkout Session.

Pages:

* success
* cancel

Store payment reference.

---

# PHASE 15 — Stripe Webhooks (Traject B)

Handle:

* checkout.session.completed
* payment_intent.succeeded
* payment_intent.payment_failed
* charge.refunded

## Result

Reliable payment flow.

---

# PHASE 16 — Orders System

After success:

Create:

* order
* order_items
* payment
* tickets

Use DB transaction.

## Order Status

* pending
* paid
* failed
* refunded
* cancelled

---

# PHASE 17 — Unique Ticket Generation

Each ticket has:

* UUID
* QR code
* ticket number
* owner name
* event relation
* order relation

## Ticket Status

* valid
* used
* cancelled
* refunded

---

# PHASE 18 — Email Tickets

Send automatically via Queue.

Email includes:

* event info
* ticket codes
* QR image
* receipt

Use:

* Mailables
* Queue jobs

---

# PHASE 19 — QR Validation Scanner

Organizer opens scanner page.

Can:

* scan QR
* enter code manually

## Rules

If valid:

* mark used
* save scan time
* save scanner user

If already used:

* deny access

If invalid:

* deny access

Ticket usable only once.

---

# PHASE 20 — Coupons

Support:

* percentage discount
* fixed discount
* event-specific
* expiration date
* max usage
* one per customer

---

# PHASE 21 — Refund Requests

Customer requests refund.

Conditions:

* before event start
* before refund deadline

Organizer can:

* approve
* reject

If approved:

* Stripe refund
* ticket cancelled

---

# PHASE 22 — Organizer Dashboard

Widgets:

* total revenue
* tickets sold
* upcoming events
* recent orders
* attendance %
* top events

Filters:

* today
* week
* month
* custom dates

---

# PHASE 23 — Admin Dashboard

Global metrics:

* total organizers
* total events
* total sales
* platform revenue
* failed payments
* active customers

Admin can manage all data.

---

# PHASE 24 — Reports

Generate:

* revenue per event
* revenue per month
* sales by city
* ticket type distribution
* coupon usage
* refunds
* attendance ratio

Use charts.

---

# PHASE 25 — Notifications

Notify:

* new order
* payment success
* refund approved
* sold out event
* organizer approved

---

# PHASE 26 — Logging

Log important actions:

* payments
* refunds
* event edits
* scans
* login attempts
* coupon changes

---

# PHASE 27 — Error Handling

Use:

* try/catch
* user friendly messages
* webhook logs
* fallback pages

---

# PHASE 28 — Performance

Optimize:

* eager loading
* indexes
* pagination
* cache statistics
* queue emails
* lazy loading prevention

---

# PHASE 29 — Security

Required:

* CSRF
* validation requests
* policies
* rate limiting
* secure uploads
* webhook signature verification

---

# PHASE 30 — Testing

Test:

* role permissions
* order creation
* payment success
* scan once only
* coupon limits
* refund flow

---

# PHASE 31 — Seeders

Create demo data:

* 1 admin
* 3 organizers
* 20 events
* 100 orders
* 300 tickets
* sample scans

---

# PHASE 32 — README

Include:

* install steps
* env config
* stripe setup
* queue commands
* demo accounts
* features list

---

# PHASE 33 — Jury Presentation

Show:

1. Admin dashboard
2. Organizer creates event
3. Customer buys ticket
4. Stripe payment
5. Ticket email
6. QR scan
7. Analytics dashboard
8. Explain architecture

---

# PHASE 34 — Bonus Features

If time remains:

* waitlist when sold out
* dark mode
* PDF invoices
* multilingual
* payout requests
* mobile scanner mode

---

# FINAL SUCCESS CHECKLIST

[✓] Multi-tenant system
[✓] Real payment flow
[✓] QR validation
[✓] Queues
[✓] Reports
[✓] Security
[✓] Professional structure
[✓] Strong Traject B depth
