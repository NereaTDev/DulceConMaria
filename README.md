# DulceConMaría – Chocolate Bonbon Online Campus 🍫

DulceConMaría is a private online campus to learn how to make bonbons and chocolate from scratch, with a strong focus on the student experience:

- Courses and lessons organized by level.
- Embedded videos with progress tracking.
- Admin panel to manage courses, lessons, recipes and students.
- Full auth flow: register, login, password reset.

---

## ✨ Main Features

- **Laravel 11 + Blade + Vite**
- **Full authentication**: registration, login, email verification, password reset.
- **Private student campus**:
  - List of courses the student is enrolled in.
  - Lesson view with video and summary.
  - Course progress automatically updated when ≥ 80% of a lesson video has been watched.
- **Admin panel**:
  - Manage courses, lessons and recipes.
  - Enrollments with states (`pending`, `paid`, `cancelled`).
  - Roles (`user`, `admin`) and flags like `grant_all_courses`.
- **Per-course progress**:
  - When the student watches ≥ 80% of a video, the lesson is marked as completed.
  - Progress bar in the campus and `X/Y lessons (Z%)` counter.
- **Security**:
  - Environment variables for secrets (DB, email provider, etc.).
  - Row Level Security (RLS) enabled on sensitive tables in the DB (when using Supabase).
  - Email existence checked before sending password reset emails.
- **Legal pages**:
  - Privacy policy: `/privacidad`
  - Cookies policy: `/cookies`
  - Links visible in the footer.

---

## 🧱 Tech Stack

- **Backend**: Laravel 11 (PHP 8.3/8.4)
- **Frontend**:
  - Blade components and custom layouts
  - Vite (JS + Tailwind CSS)
  - Alpine.js for light interactions
- **Database**: PostgreSQL (SQLite can be used in development)
- **Hosting**: Render (Docker + Apache/PHP) or any compatible platform
- **Email**: external provider (currently integrated via HTTP API in production)
- **Video**: YouTube IFrame API

---

## 🚀 Getting Started (Local)

### Prerequisites

- PHP ≥ 8.3
- Composer
- Node.js + npm
- SQLite or PostgreSQL locally

### 1. Clone the repository

```bash
git clone https://github.com/<your-username>/DulceConMaria.git
cd DulceConMaria
