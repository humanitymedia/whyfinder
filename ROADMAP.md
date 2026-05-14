# WhyFinder Roadmap

WhyFinder is an online course platform that helps people discover their life purpose and build a business around it. Free flagship course "Find Your Why" plus paid courses on website building, branding, marketing, and entrepreneurship. Contributing instructors earn from enrollments; platform supports one-time purchases and Stripe subscriptions; includes a purpose-focused blog and per-course discussion forums.

Reference docs:
- Project brief: `~/Dropbox (Personal)/WHYFINDER/whyfinder-project-brief-v2.docx`
- Design prototype: https://whyfinder.lovable.app
- Hosting model: `~/Sites/hosting-panel/Laravel_Dev_Rules.md`, `~/Sites/hosting-panel/Architecture.md`
- Staging: https://whyfinder-laravel.staging.humanitymedia.net

## Legend

- ✅ Shipped (in code, deployed to staging)
- ⚠️ Built but not operational on staging (config / infra gap)
- 🚧 Partial — some pieces shipped, others outstanding
- ❌ Not yet built

---

## Module status

### Module 1 — Roles & Permissions ✅
spatie/laravel-permission, four roles (admin, manager, instructor, student), 54 granular permissions defined in `database/seeders/RolesAndPermissionsSeeder.php`. `whyfinder:assign-admin {email}` Artisan command. Middleware enforces `role:admin`, `role:instructor`, etc. in `routes/web.php`. Registration auto-assigns `student`. Profile page shows current role badge.

**Operational gap (⚠️):** seeder hasn't run on staging yet — roles/permissions tables are empty. See `.infra-request-draft.md` for the request.

### Module 2 — Public pages & layout ✅ (with one gap)
- ✅ Public layout: branded nav (logo, Courses/Blog/Teach, Login + Get Started Free) and footer
- ✅ Home page with all five brief sections: Hero, How It Works, Featured Courses, Stories of Purpose (testimonials), bottom CTA
- ✅ Auth pages (login, register, forgot-password, reset, verify) re-skinned off Breeze
- ❌ **Hero background image** — brief specifies "dark background photo of a person working on a laptop outdoors at sunset" with overlay text. Current home is a flat `bg-brand-brown` color. Will be fixed by the **Branding/Content CMS** below.

### Module 3 — Admin dashboard & user management ✅
- ✅ Admin sidebar layout: Dashboard, Users, Instructors, Students, Courses, Blog, Reviews, Payments, Forums, Settings
- ✅ User CRUD with role assignment
- ✅ Dashboard metrics
- ✅ Instructor/student management sub-areas

### Module 4 — Instructor dashboard & course builder ✅
- ✅ Instructor dashboard with all four stat cards (Courses, Total Students, Total Earnings, This Month) computed in `Instructor/DashboardController.php`
- ✅ Course CRUD views under `resources/views/instructor/courses/`
- ✅ Public `/teach` page + apply form → `instructor_applications` table → admin review queue
- 🚧 **Drag-and-drop section/lesson reordering** — not yet built (brief asked for this). Sections + lessons exist as models with `sort_order` columns, so this is a UI add only.
- 🚧 **Video player + downloadable file lesson types** — `lessons.type` supports video/text/download in the model; UI for video upload/embed and file management needs verification.

### Module 5 — Course catalog & student experience ✅
- ✅ `/courses` with search box, level filter, category pills
- ✅ Course detail pages (free + paid)
- ✅ Enrollment flow (free) and checkout flow (paid)
- ✅ Course player `/learn/{course}/{lesson}` with lesson navigation + completion tracking
- ✅ Student dashboard `/my-learning` and `/dashboard/payments`

### Module 6 — Payment integration ⚠️
- ✅ `laravel/cashier` ^16.3 installed, Stripe columns migrated onto users
- ✅ `PaymentController` (checkout, success, history) and `StripeWebhookController`
- ✅ `instructor_earnings` table + platform fee logic (configurable %)
- ✅ Admin Settings UI for Stripe keys (encrypted in `settings` table) + platform fee
- ⚠️ **Stripe env vars not set in production `.env`** — `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`, `CASHIER_CURRENCY` need to be filed as an infra request. (Or — since admin Settings stores them in DB encrypted — verify which path the code prefers and document.)
- 🚧 **Recurring subscriptions** — brief mentions subscription plans. `subscriptions` migration exists (Cashier-managed) but no routes/UI for plan selection or subscription dashboard yet.

### Module 7 — Blog ✅
- ✅ Public `/blog` listing with featured post + 3-col grid
- ✅ Blog post detail page with bottom CTA
- ✅ Admin blog CRUD with rich-text editor (EditorJS via `body-editor` element)
- ✅ Categories + tags

### Module 8 — Discussion forums ✅
- ✅ Per-course forums at `/courses/{slug}/forum`
- ✅ Threads (create, edit, pin, lock, delete) + nested replies
- ✅ `InstructorRepliedToThread` notification
- ✅ Admin moderation at `/admin/forums`

### Module 9 — Certificates & reviews ✅
- ✅ `barryvdh/laravel-dompdf` ^3.1 installed; certificate template at `resources/views/certificates/pdf.blade.php`
- ✅ Public verification at `/verify/{certificateNumber}`
- ✅ Reviews (one per user per course) with admin approval
- ✅ `GenerateCertificate` job — **but see queue gap below**

---

## Cross-cutting / infra gaps

| Item | Status | Action |
|---|---|---|
| Roles/permissions seeded on staging | ⚠️ | File infra request (draft at `.infra-request-draft.md`) |
| Admin user for bear@humanitymedia.net | ⚠️ | Register at `/register`, then infra request to run `whyfinder:assign-admin` |
| Stripe env vars in production `.env` | ⚠️ | Infra request; or use admin Settings UI if code reads from DB |
| Horizon for queue workers | ❌ | `laravel/horizon` is NOT in `composer.json`. Without it, `GenerateCertificate` and `InstructorRepliedToThread` queue but never process. Add to composer + supervisor entry (infra request). Stopgap: set `QUEUE_CONNECTION=sync` in production `.env`. |
| S3 storage for production uploads | ❌ | Brief calls for S3 in Section 7.3. Currently `FILESYSTEM_DISK=local`. Add S3 disk config + infra request for credentials. |
| Sitemap | ❌ | `spatie/laravel-sitemap` not installed. |
| Image processing (resize, conversions) | ❌ | Neither `spatie/laravel-medialibrary` nor `intervention/image` installed. Currently no automatic thumbnail generation for course/blog featured images or avatar uploads. |
| Auto-slugs | 🚧 | `spatie/laravel-sluggable` not installed; slugs likely set manually in controllers. Audit. |
| Mail config in production | ❌ | Production `.env` mail vars unconfirmed. Password reset, instructor reply notifications, and certificate emails depend on this. |

---

## Out-of-scope from brief but worth building

| Item | Why |
|---|---|
| **Branding / Content CMS** (next up) | Brief assumes hero image + copy is hardcoded by developers. In practice you'll want to swap hero image, headline, sub, CTA labels, and "Stories of Purpose" testimonials without a code deploy. Build a `Branding` tab inside `/admin/settings` backed by the existing `Setting` model (already key/value/type/group with encryption + caching). First scope: hero image upload, hero headline/subhead/CTA copy, site logo, footer tagline. |
| Email digest / drip campaigns | Brief mentions notifications but only instructor-reply. Onboarding email for new students, weekly digest of forum activity, etc. would help retention. Backlog. |
| Affiliate / referral tracking | Brief doesn't mention it but common for course platforms. Backlog. |
| Course completion analytics for instructors | Brief shows stats; could deepen with per-lesson dropoff. Backlog. |

---

## Recommended next milestones

**Milestone 1 — Get staging operational** (blockers, do first)
1. File infra request to seed roles/permissions on staging + assign admin role to bear@humanitymedia.net.
2. Decide queue strategy: install Horizon (infra request for supervisor) **or** set `QUEUE_CONNECTION=sync` as stopgap.
3. Wire Stripe in test mode (decide between `.env` vars or admin Settings UI as source of truth; document the chosen path).
4. Confirm mail SMTP in production `.env`; test password reset email round-trip.

**Milestone 2 — Branding/Content CMS** (currently building per separate work)
5. New `/admin/branding` tab in Settings: hero image upload, hero copy fields, site logo, footer tagline, testimonials editor.
6. Replace hardcoded values in `home.blade.php` and `layouts/public.blade.php` with `Setting::get('home_hero_image', '...')` etc.
7. Add `intervention/image` for thumbnail resizing.

**Milestone 3 — Production-readiness polish**
8. S3 disk for uploads; rsync existing local uploads to S3 on cutover.
9. Sitemap generation (`spatie/laravel-sitemap`).
10. Auto-slugs (`spatie/laravel-sluggable`) — audit current manual slug logic and migrate.
11. Drag-and-drop section/lesson reordering in course builder.

**Milestone 4 — Beyond brief**
12. Recurring subscription plans + student subscription dashboard.
13. Onboarding email drip for new students.
14. Per-lesson analytics for instructors.

---

## How to update this doc

- Tick items off as they ship; move them from ❌/🚧 → ✅.
- Add new items under "Out-of-scope but worth building" as they're discovered (don't silently expand a module).
- When infra gaps close, move them out of "Cross-cutting" into the relevant module.
- Update the "Recommended next milestones" section when finishing a milestone.
