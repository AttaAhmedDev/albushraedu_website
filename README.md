# Online Learning Platform

🌐 **Live Demo:** https://albushraedu.com

PHP JSON API + React SPA for English and Math kindergarten learning content.

## Features

- Responsive design
- English and Math learning resources
- Interactive worksheets, presentations, games, and flashcards
- Admin dashboard for content management
- User-friendly interface

## Structure

- `backend/` — PHP API (`backend/public/index.php` front controller)
- `frontend/` — React (Vite) source; production build in `frontend/dist/`
- `uploads/` — uploaded worksheets, presentations, flashcards (not in Git)
- `images/` — static images
- `.htaccess` — same-origin routing: `/api/*` → PHP, everything else → React SPA

## Requirements

- XAMPP (Apache + MySQL + PHP)
- Node.js 18+ (to build the frontend)
- Database: `kids_app` (configure credentials in `backend/config/db.php`)

## Setup

1. Place the project at `C:\xampp\htdocs\OnlineLearningPlatform` (or your XAMPP `htdocs` path).
2. Ensure Apache `mod_rewrite` is enabled and `AllowOverride All` for htdocs.
3. Configure `backend/config/db.php` if needed.
4. Build the frontend:

```bash
cd frontend
npm install
npm run build
```

5. Open: `http://localhost/OnlineLearningPlatform/`

## Development

- API is always served by Apache at `/OnlineLearningPlatform/api/...`
- Optional Vite HMR:

```bash
cd frontend
npm run dev
```

Vite is configured with `base: '/OnlineLearningPlatform/'` and proxies API calls to Apache.

## API overview

| Method | Path | Notes |
|--------|------|-------|
| POST | `/api/auth/login` | `{ role, email, password }` |
| POST | `/api/auth/register` | student register |
| POST | `/api/auth/logout` | |
| GET | `/api/auth/me` | session user |
| GET/POST/DELETE | `/api/worksheets/{type}` | type: english, math, sight, word |
| GET/POST/DELETE | `/api/presentations/{type}` | |
| GET/POST/DELETE | `/api/games/{subject}` | english, math |
| GET/POST/DELETE | `/api/flashcards/{kind}` | letters, numbers |
| GET/PUT | `/api/settings/footer` | |
| PUT | `/api/admin/profile` | admin only |

Auth uses PHP sessions (cookies). Mutating admin routes require an admin session.
