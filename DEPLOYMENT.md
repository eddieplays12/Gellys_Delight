# Gellys Deployment Notes

This repository has two apps:

- Laravel API/backend in the project root
- Next.js customer frontend in `frontend/`

## GitHub

Do not commit real secrets. Keep `.env`, `vendor/`, `node_modules/`, `.next/`, and uploaded storage files out of Git.

Recommended first push:

```bash
git init
git add .
git commit -m "Prepare Gellys app for deployment"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git
git push -u origin main
```

## Vercel Frontend

When importing this repo in Vercel, set:

- Root Directory: `frontend`
- Framework Preset: Next.js
- Build Command: `npm run build`
- Install Command: `npm install`

Environment variable:

```text
NEXT_PUBLIC_API_ORIGIN=https://your-laravel-backend-domain.com
```

Local fallback is `http://127.0.0.1:8000`, but that only works on your own computer. Online users need a real hosted backend URL.

## Student Demo Without A Domain

If you do not have a domain yet, use a temporary Cloudflare Tunnel URL for the Laravel backend:

```powershell
cloudflared tunnel --url http://127.0.0.1:8000
```

Cloudflare will print a temporary URL like:

```text
https://random-name.trycloudflare.com
```

Put that URL in Vercel:

```text
NEXT_PUBLIC_API_ORIGIN=https://random-name.trycloudflare.com
```

Then redeploy the Vercel frontend.

See [STUDENT_ONLINE_GUIDE.md](STUDENT_ONLINE_GUIDE.md) for the multi-day demo checklist.

## Laravel Backend

The Laravel backend cannot be served by Vercel as-is. Put it on PHP hosting, a VPS, or a Laravel-friendly platform.

For production `.env`, set:

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-laravel-backend-domain.com
CORS_ALLOWED_ORIGINS=https://your-vercel-app.vercel.app,https://your-custom-domain.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

Then run on the backend server:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Local Development

Run:

```bat
start-dev.bat
```

Customer products page:

```text
http://127.0.0.1:3000/products
```
