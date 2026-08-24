# Hartono Group — Corporate Event Experience & On-Site Management Portal

An integrated enterprise event management platform and public corporate portal built for **PT Hartono Raya Motor** and the **Hartono Group** automotive ecosystem (Mercedes-Benz Dealership, HR Auto Studio Body & Detailing, Hartono Rent Car, PT Rudy Darma Engineering Karoseri, and Hartono Heavy Transport).

Designed to manage high-profile customer gatherings, VIP soft openings, press conferences, on-site reception desks, and stage entertainment with real-time digital interaction.

---

## 🌟 Key Highlights & Feature Modules

### 1. 🏛️ Public Experience Portal
- **Dynamic Hero Slider**: Carousel featuring active corporate events with real-time countdown clocks and rich call-to-actions.
- **Business Units Ecosystem**: Interactive showcase linking gatherings to specific divisions (*Mercedes-Benz Dealership, HR Auto Studio, Rent Car, Karoseri, Heavy Transport*).
- **Events Calendar & Agenda Rundowns**: Comprehensive timeline breakdown with speakers, VIP designations, and venue map integrations.
- **Official Media Gallery**: High-resolution photography showcase and video reels with a responsive full-screen lightbox viewer.
- **Executive Post-Event Recap**: Post-ceremony narrative summary with milestone metrics, gallery highlights, and congratulatory wall.
- **Digital Guest Book & Wishes Wall**: Public message wall where guests and VIPs can leave warm wishes, congratulations, and event selfies with like counts.
- **Press Releases & Media Kit**: Official PR briefings with embargo controls, download packs, and brand color palette guides.

### 2. 🎟️ Multi-Attendee RSVP & Digital QR E-Tickets
- **Group Attendance Registration**: Accommodates corporate invitations where 1 company registers multiple attendees—automatically assigning distinct attendee records and doorprize ticket numbers to each companion.
- **Instant Digital E-Ticket**: Generates individualized mobile-ready E-Tickets containing QR check-in codes, dress code reminders, and vehicle details.
- **Calendar & Print Ready**: Single-click "Add to Google Calendar" and formatted print ticket views.

### 3. 📱 On-Site Reception Desk & Live QR Scanner
- **Live Camera QR Scanner**: Fast, continuous webcam/camera scanning using `Html5Qrcode` with instant audio-visual sound effects and check-in confirmation badges.
- **Tablet / iPad Kiosk Mode**: One-touch toggle to hide the admin sidebar for full-screen use at physical reception registration desks.
- **Live Attendance Metrics**: Real-time counter showing Total Registered, Checked-In Attendees, Remaining Guests, and VIP count.
- **Manual Fallback Search**: Instant check-in search by attendee name, vehicle model, license plate, or registration code.

### 4. 🎡 Big-Screen Live Doorprize Roulette
- **Predefined Prize Distribution**: Configure prize tiers (*Grand Prize, 2nd Prize, 3rd Prize, Door Prizes*) with allocated quantities, sponsor details, and custom icons.
- **Stage Presentation Roulette Wheel**: High-performance Canvas-rendered roulette wheel designed for auditorium LED walls with realistic physics (acceleration, deceleration, tick audio effects, and celebration fanfare).
- **Attendance-Filtered Pool**: Spins exclusively among verified attendees who checked in at the event guestbook.
- **Winner Management**: Displays celebratory confetti modals, records winners in database, and prevents duplicate wins with individual or batch reset options.

### 5. 🛠️ Organizer Back-Office & CMS
- **Real-Time KPI Dashboard**: Centralized dashboard summarizing upcoming gatherings, check-in percentages, and recent attendee activity.
- **WYSIWYG Rich Text Editor**: Integrated formatting toolbar for event descriptions, rundowns, and post-event summaries.
- **Live Footer Customizer**: Admin interface (`/admin/settings/footer`) to update brand narratives, regional dealership branch networks, contact numbers, and copyright text with live preview.
- **Role-Based Access Control (RBAC)**: Support for *Super Admin*, *Event Organizer*, *VIP Member / Customer*, and *Accredited Press Media*.
- **In-App Guide Modal**: Built-in visual walkthrough explaining the 4-phase event lifecycle for staff onboarding.

---

## 🎨 Design & Theme System

- **Dark & Light Theme Overhaul**: Dual-theme support with bespoke color contrast tailoring (`#070B0E` deep luxury dark mode and high-contrast `#F8FAFC` light mode).
- **Brand Typography**: Montserrat and Inter via Google Fonts with tailored letter-spacing and hierarchy.
- **Brand Logomarks**: Integrated official Hartono full-color logomarks and favicon suite across all desktop and mobile viewports.

---

## 🏗️ Technical Architecture & Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 11.x (PHP 8.4) |
| **Frontend Architecture** | Inertia.js v2 + Vue 3 (Composition API, `<script setup>`) |
| **Styling & Design System** | Tailwind CSS v4 + Vanilla CSS + Glassmorphism |
| **Build Tooling** | Vite 6.x |
| **Database** | MySQL 8.0 / MariaDB |
| **QR Engine & Scanner** | `simplesoftwareio/simple-qrcode` & `html5-qrcode` |
| **Special FX & Motion** | `canvas-confetti` + HTML5 Web Audio API |
| **Icons** | `lucide-vue-next` |

---

## 🚀 Getting Started (Local Development)

### Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+ & NPM
- MySQL 8.0+

### 1. Installation
```bash
# Clone the repository
git clone <repo-url> hartono-events
cd hartono-events

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment configuration
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

### 2. Database & Seeding
Configure your database credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hartono_events
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and database seeds:
```bash
php artisan migrate:fresh --seed
```

### 3. Run Development Servers
```bash
# Start Laravel dev server
php artisan serve --port=8088

# In a separate terminal, start Vite dev server
npm run dev
```

Visit the application at: `http://localhost:8088`

---

## 🔐 Default Demo Accounts

| Role | Email | Password | Access Area |
|---|---|---|---|
| **Super Admin** | `admin@hartonomotor.com` | `P@ssword#hrm765.` | Full Back-Office, User Management & Settings |
| **Event Organizer** | `organizer@hartonomotor-group.com` | `password` | Event CMS, Live Scanner, Doorprize Roulette |
| **VIP Member** | `member@hartonomotor-group.com` | `password` | Customer Portal, Digital Passes Wallet |
| **Press / Media** | `media@autonetmagz.com` | `password` | Media Room, Embargoed Press Releases |

---

## 🐳 Production Deployment (Docker & Portainer)

The project includes production-ready container definitions configured for Portainer stacks and Cloudflare Tunnel publishing:

### 1. Docker Compose Services
- **`app`**: PHP 8.4 FPM + Nginx runtime serving the compiled Laravel application (Exposed on port `3033`).
- **`mysql`**: MySQL 8.0 database container with persistent data volumes.

### 2. Deployment Commands
```bash
# Build frontend assets for production
npm run build

# Start Docker containers
docker compose up -d --build

# Run database migrations
docker exec hartono_events_app php artisan migrate --force

# Optimize and cache configuration
docker exec hartono_events_app php artisan optimize:clear
docker exec hartono_events_app php artisan config:cache
docker exec hartono_events_app php artisan route:cache
docker exec hartono_events_app php artisan view:cache
```

---

## 📂 Key Directory Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Back-Office Controllers (Events, CheckIn, Doorprize, Settings)
│   │   │   ├── Public/         # Public Portal Controllers (Home, Events, Recap, Gallery)
│   │   │   ├── Media/          # Accredited Press Portal Controllers
│   │   │   └── Member/         # VIP Member Portal Controllers
│   │   └── Middleware/         # Inertia shared props, RBAC filters
│   └── Models/                 # Eloquent Models (Event, Doorprize, Registration, SiteSetting)
├── database/
│   ├── migrations/             # Database schema migrations
│   └── seeders/                # Initial seeders with Hartono business units & demo events
├── public/
│   ├── images/logos/           # Official Hartono & Mercedes-Benz brand logomarks
│   └── favicon*                # High-res favicon suite
├── resources/
│   ├── css/                    # Tailwind CSS v4 & custom design tokens
│   ├── js/
│   │   ├── Components/         # Reusable UI widgets (Navbar, Footer, QR, RichText, Doorprize)
│   │   ├── Layouts/            # AdminLayout & PublicLayout
│   │   ├── Pages/              # Inertia Vue page views
│   │   └── Utils/              # Helper utilities (date formatter, sound effects)
│   └── views/app.blade.php     # Main SPA Blade host template
└── routes/
    └── web.php                 # Web application routes
```

---

## 📄 License & Ownership
Copyright © 2026 **PT Hartono Raya Motor & Hartono Group**. All rights reserved.
