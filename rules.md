# rules.md

# DEVELOPMENT RULES — Premium Multi-Tenant Ticketing Platform (Traject B)

## Purpose

These rules ensure the project is:

* Professional
* Clean
* Scalable
* Secure
* Easy to defend during jury presentation
* Strong enough for Traject B expectations

---

# SECTION 1 — GENERAL PROJECT RULES

## Rule 1.1

Use **Laravel conventions first**.
Never fight the framework.

## Rule 1.2

Write code as if another developer will maintain it.

## Rule 1.3

Avoid quick hacks. Build maintainable solutions.

## Rule 1.4

Every feature must solve a real business need.

## Rule 1.5

Prefer simplicity over unnecessary complexity.

---

# SECTION 2 — CLEAN CODE RULES

## Rule 2.1

Use clear naming.

Good:

* CreateEventService
* ProcessPaymentService
* ValidateTicketService

Bad:

* Helper1
* DataStuff
* FinalLogic

## Rule 2.2

One class = one responsibility.

## Rule 2.3

One method = one clear task.

## Rule 2.4

Keep methods short.

## Rule 2.5

Remove dead code immediately.

## Rule 2.6

No duplicated logic.

Create reusable methods/services.

---

# SECTION 3 — CONTROLLER RULES

## Rule 3.1

Controllers must stay thin.

Controller should:

* validate request
* call service
* return response

## Rule 3.2

Never place business logic in controllers.

Bad:

* payment processing
* ticket generation
* stock checks

Move to Services.

---

# SECTION 4 — SERVICE LAYER RULES

## Rule 4.1

Use Services for complex logic.

Examples:

* EventService
* CheckoutService
* PaymentService
* TicketService
* RefundService

## Rule 4.2

Services should be reusable.

## Rule 4.3

Inject dependencies properly.

---

# SECTION 5 — DATABASE RULES

## Rule 5.1

Use foreign keys everywhere.

## Rule 5.2

Use indexes for:

* organizer_id
* event_id
* order_id
* payment_status
* created_at

## Rule 5.3

Use softDeletes where needed.

Examples:

* events
* organizers
* coupons

## Rule 5.4

Use timestamps always.

## Rule 5.5

Use transactions for sensitive flows.

Examples:

* order creation
* payment success
* refunds

## Rule 5.6

Never trust manual database updates.

Use services.

---

# SECTION 6 — MULTI-TENANCY RULES

## Rule 6.1

Every organizer sees only own data.

## Rule 6.2

All organizer-owned tables must contain organizer_id.

## Rule 6.3

Protect access using:

* Policies
* Scopes
* Middleware

## Rule 6.4

Admin can bypass tenant limits.

## Rule 6.5

Never expose another organizer’s data.

Critical rule.

---

# SECTION 7 — AUTHORIZATION RULES

## Roles

* Admin
* Organizer
* Customer

## Rule 7.1

Use Policies instead of manual if checks.

## Rule 7.2

Every sensitive action needs authorization.

Examples:

* edit event
* refund payment
* scan ticket
* delete coupon

## Rule 7.3

Unauthorized users must receive 403.

---

# SECTION 8 — VALIDATION RULES

## Rule 8.1

Use Form Requests.

Examples:

* StoreEventRequest
* CheckoutRequest
* ApplyCouponRequest

## Rule 8.2

Validate all inputs.

## Rule 8.3

Never trust frontend validation only.

## Rule 8.4

Use custom messages where useful.

---

# SECTION 9 — PAYMENT RULES

## Rule 9.1

Never trust redirect success page only.

Use webhooks.

## Rule 9.2

Store payment logs.

## Rule 9.3

Use statuses:

* pending
* paid
* failed
* refunded

## Rule 9.4

Prevent duplicate webhook processing.

## Rule 9.5

Always verify Stripe signature.

---

# SECTION 10 — ORDER RULES

## Rule 10.1

An order must preserve historical data.

Do not rely only on current product/event values.

## Rule 10.2

Save:

* price at purchase time
* quantity
* customer info

## Rule 10.3

Use immutable totals after payment.

---

# SECTION 11 — TICKET RULES

## Rule 11.1

Each ticket must be unique.

## Rule 11.2

Use UUID or secure code.

## Rule 11.3

Ticket statuses:

* valid
* used
* cancelled
* refunded

## Rule 11.4

Used ticket cannot be reused.

Critical rule.

## Rule 11.5

Store scan history.

---

# SECTION 12 — QR SCANNER RULES

## Rule 12.1

Scanning must be fast.

## Rule 12.2

Display clear result:

* Valid
* Already used
* Invalid

## Rule 12.3

Save:

* scanned_at
* scanner_user_id
* device/ip optional

---

# SECTION 13 — QUEUE RULES

## Rule 13.1

Use queues for slow tasks.

Examples:

* emails
* image processing
* reports

## Rule 13.2

Never block checkout with email sending.

## Rule 13.3

Retry failed jobs.

---

# SECTION 14 — MAIL RULES

## Rule 14.1

Use clean templates.

## Rule 14.2

Include:

* event name
* date
* ticket info
* QR code

## Rule 14.3

Emails must be mobile friendly.

---

# SECTION 15 — FILE UPLOAD RULES

## Rule 15.1

Allow only safe file types.

## Rule 15.2

Validate size.

## Rule 15.3

Rename files safely.

## Rule 15.4

Store in Laravel storage.

## Rule 15.5

Optimize images.

---

# SECTION 16 — UI RULES

## Rule 16.1

UI must be clean and usable.

## Rule 16.2

Consistency in spacing/colors/components.

## Rule 16.3

Use Flux components when possible.

## Rule 16.4

Responsive design required.

## Rule 16.5

Loading states for Livewire actions.

---

# SECTION 17 — LIVEWIRE RULES

## Rule 17.1

Keep components focused.

## Rule 17.2

Do not create giant components.

## Rule 17.3

Use computed properties where useful.

## Rule 17.4

Validate Livewire actions.

## Rule 17.5

Emit events only when needed.

---

# SECTION 18 — PERFORMANCE RULES

## Rule 18.1

Use eager loading.

## Rule 18.2

Avoid N+1 queries.

## Rule 18.3

Use pagination.

## Rule 18.4

Cache dashboard stats.

## Rule 18.5

Index search columns.

---

# SECTION 19 — LOGGING RULES

## Log Important Actions

* payments
* refunds
* failed scans
* event edits
* organizer approvals
* login failures

## Rule 19.1

Logs must help debugging.

---

# SECTION 20 — ERROR HANDLING RULES

## Rule 20.1

Use try/catch in services.

## Rule 20.2

Show friendly errors to users.

## Rule 20.3

Log technical details privately.

---

# SECTION 21 — TESTING RULES

Test minimum:

* login
* permissions
* event creation
* checkout
* payment webhook
* scan once only
* refund flow

---

# SECTION 22 — README RULES

README must contain:

* installation
* environment setup
* queue worker
* stripe webhook
* demo accounts
* feature list

---

# SECTION 23 — GIT RULES

## Rule 23.1

Use clear commits.

Good:

* Add ticket validation service
* Implement organizer dashboard charts

Bad:

* update
* fix stuff

## Rule 23.2

Commit regularly.

---

# SECTION 24 — JURY PRESENTATION RULES

## During demo show:

1. Real problem solved
2. Multi-organizer architecture
3. Payment flow
4. QR validation
5. Reports
6. Clean code structure

## Speak about:

* why services used
* why queues used
* why policies used
* why webhooks needed

---

# SECTION 25 — TRAJECT B WINNING RULES

To score high, prove:

## Depth

Not just pages, but logic.

## Professionalism

Architecture + clean code.

## Realism

Real payment flow + real permissions.

## Scalability

Queues + services + analytics.

## Reliability

Validation + transactions + logging.

---

# SECTION 26 — THINGS TO AVOID

Do NOT:

* put all logic in controllers
* skip policies
* skip validation
* trust payment redirect only
* allow duplicate ticket use
* hardcode IDs
* expose organizer data
* leave ugly UI
* ignore mobile design

---

# FINAL GOLDEN RULE

Build it like a startup product, not a classroom CRUD project.

That mindset alone can change your final score.
