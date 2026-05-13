# Gellys Laravel + Next.js App

Gellys is a small ecommerce-style ordering app with:

- Laravel 12 API/admin backend
- Next.js customer frontend in `frontend/`
- Product listing, cart flow, orders, ratings, and admin product management

## Local Development

Start both Laravel and Next:

```bat
start-dev.bat
```

Or run manually:

```bash
php artisan serve --host=127.0.0.1 --port=8000
cd frontend
npm.cmd run dev
```

Customer frontend:

```text
http://127.0.0.1:3000/products
```

Laravel API:

```text
http://127.0.0.1:8000/api/products
```

## Frontend Environment

Copy:

```bash
cp frontend/.env.example frontend/.env.local
```

Set:

```text
NEXT_PUBLIC_API_ORIGIN=http://127.0.0.1:8000
```

For Vercel, set `NEXT_PUBLIC_API_ORIGIN` to your hosted Laravel backend URL.

## Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for GitHub, Vercel, and Laravel backend hosting notes.
