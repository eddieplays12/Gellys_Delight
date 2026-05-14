# Gelly's Delight

Gelly's Delight is a school demo ordering system for coffee, pastries, and desserts.

The project has two main parts:

- **Laravel backend**: API, database, admin dashboard, products, orders, ratings, and uploaded images.
- **Next.js frontend**: customer website deployed on Vercel.

Current live frontend:

```text
https://gellys-delight.vercel.app
```

Current backend is served from the owner's laptop through Cloudflare Tunnel:

```text
https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
```

## How The System Works

```text
Customer device
    |
Vercel Next.js frontend
    |
Cloudflare Tunnel URL
    |
Laravel backend on laptop through XAMPP Apache
    |
Local MySQL database in XAMPP
```

The customer opens the Vercel site. The Vercel site calls the Laravel API using `NEXT_PUBLIC_API_ORIGIN`. Laravel reads and writes data in the local MySQL database.

The admin dashboard is not on Vercel. It is part of the Laravel backend.

Admin login page:

```text
https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public/admin/login
```

## Requirements

Install these before running the project locally:

- PHP 8.2 or newer
- Composer
- XAMPP with Apache and MySQL
- Node.js and npm
- Git
- Cloudflare Tunnel (`cloudflared.exe`) for online  access

## Step-By-Step Local Setup

1. Clone the repository:

```bash
git clone https://github.com/eddieplays12/Gellys_Delight.git
cd Gellys_Delight
```

2. Install Laravel dependencies:

```bash
composer install
```

3. Create the Laravel environment file:

```bash
copy .env.example .env
```

4. Generate the Laravel app key:

```bash
php artisan key:generate
```

5. Configure database in `.env`.

For the current XAMPP setup:

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=gellys
DB_USERNAME=root
DB_PASSWORD=
```

6. Create the database in MySQL:

```sql
CREATE DATABASE gellys;
```

7. Run migrations:

```bash
php artisan migrate
```

8. Install frontend dependencies:

```bash
cd frontend
npm install
```

9. Create the frontend environment file:

```bash
copy .env.example .env.local
```

For local Laravel server:

```text
NEXT_PUBLIC_API_ORIGIN=http://127.0.0.1:8000
```

For the current online demo:

```text
NEXT_PUBLIC_API_ORIGIN=https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
```

## Running Locally

Option 1: use the helper script from the project root:

```bat
start-dev.bat
```

Option 2: run manually.

Terminal 1, Laravel backend:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Terminal 2, Next.js frontend:

```bash
cd frontend
npm.cmd run dev
```

Open the frontend:

```text
http://127.0.0.1:3000
```

Open the API directly:

```text
http://127.0.0.1:8000/api/products
```

## Current Online Demo Setup

For the current online, the backend is served by XAMPP Apache:

```text
http://127.0.0.1/gellys-laravel/public
```

Cloudflare Tunnel points to:

```text
http://127.0.0.1
```

That is why the public backend URL includes:

```text
/gellys-laravel/public
```

To keep the online demo working, keep these running:

- Laptop
- Internet connection
- XAMPP Apache
- XAMPP MySQL
- Cloudflare Tunnel

If the laptop sleeps or the tunnel stops, the Vercel frontend may still open, but login, products, checkout, orders, and admin pages may stop working.

## Starting Cloudflare Tunnel

Example command:

```powershell
C:\Users\User\Documents\Codex\2026-05-14\files-mentioned-by-the-user-gellys\cloudflared.exe tunnel --url http://127.0.0.1
```

Cloudflare will print a URL like:

```text
https://random-name.trycloudflare.com
```

If the tunnel URL changes, update these:

- Vercel `NEXT_PUBLIC_API_ORIGIN`
- Laravel `.env` `APP_URL`

Then clear Laravel cache:

```bash
php artisan config:clear
php artisan cache:clear
```

Redeploy the Vercel frontend after changing `NEXT_PUBLIC_API_ORIGIN`.

## Vercel Deployment

When importing this GitHub repo in Vercel:

```text
Root Directory: frontend
Framework: Next.js
```

Environment variable:

```text
NEXT_PUBLIC_API_ORIGIN=https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
```

The frontend calls the backend using this variable in:

```text
frontend/lib/apiClient.js
```

## Laravel Environment For Online 

Important `.env` values:

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=https://households-resort-mario-wow.trycloudflare.com/gellys-laravel/public
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,https://gellys-delight.vercel.app
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=none
```

These values allow the Vercel frontend to call the backend through the tunnel.

## Main Features

Customer side:

- View products
- Register and login
- Add products to cart
- Checkout order
- View own orders
- Add ratings and feedback

Admin side:

- Admin login
- View dashboard
- Add, edit, and delete products
- Upload product images
- View orders
- Update order status
- View ratings

## Important Folder And File Guide

### Root Laravel Files

`artisan`

- Laravel command line tool.
- Used for commands like `php artisan migrate`, `php artisan test`, and `php artisan config:clear`.

`composer.json`

- Lists PHP/Laravel dependencies.
- Also contains Laravel scripts.

`composer.lock`

- Locks exact PHP package versions.
- Keep this file in GitHub.

`.env`

- Local environment settings.
- Contains database settings, app URL, and secrets.
- Do not commit this file.

`.env.example`

- Example environment file for new setup.
- Safe to commit.

`.gitignore`

- Tells Git which local/generated files should not be committed.

`phpunit.xml`

- Test configuration.
- Used by `php artisan test`.

`start-dev.bat`

- Helper script to start local Laravel and Next.js development servers.

`vite.config.js` and root `package.json`

- Laravel Vite frontend scaffold.
- The customer frontend is in `frontend/`, but these files are kept for the Laravel default view/assets.

### App Folder

`app/Http/Controllers/Admin`

- Laravel admin dashboard controllers.
- Handles admin login, product management, order management, and ratings page.

`app/Http/Controllers/Api`

- API controllers used by the Next.js frontend.
- Handles users, products, orders, ratings, and admin API routes.

`app/Http/Middleware`

- Custom login checks.
- `AdminAuthenticated.php` protects admin pages.
- `UserAuthenticated.php` protects customer-only API routes.

`app/Models`

- Database models.
- Examples: `Product`, `Order`, `OrderItem`, `Rating`, `User`, `Admin`.

`app/Providers/AppServiceProvider.php`

- Application boot configuration.
- Forces HTTPS in production so Cloudflare Tunnel redirects and cookies work correctly.

### Bootstrap Folder

`bootstrap/app.php`

- Laravel app setup.
- Registers routes, middleware aliases, proxy headers, and API CSRF exception.

`bootstrap/cache`

- Generated Laravel cache files.
- Cache files should not be manually edited.

### Config Folder

`config/app.php`

- General Laravel app settings.

`config/database.php`

- Database connection settings.

`config/session.php`

- Session/cookie settings.
- Important for login and admin access.

`config/cors.php`

- Allows Vercel frontend to call the Laravel backend.

`config/sanctum.php`

- Sanctum/auth related settings.

Other config files:

- `auth.php`
- `cache.php`
- `filesystems.php`
- `logging.php`
- `mail.php`
- `queue.php`
- `services.php`

These are standard Laravel configuration files.

### Database Folder

`database/migrations`

- Creates database tables.
- Tables include users, products, orders, order items, ratings, admin, user addresses, and personal access tokens.

`database/seeders`

- Optional sample data setup.

`database/factories`

- Test data factories.

### Routes Folder

`routes/web.php`

- Laravel web routes.
- Includes admin pages and product image route.

`routes/api.php`

- API routes called by the Next.js frontend.
- Includes product, user, order, rating, and admin API endpoints.

`routes/console.php`

- Console command routes.

### Resources Folder

`resources/views/admin`

- Blade templates for the Laravel admin dashboard.

`resources/views/welcome.blade.php`

- Default Laravel home page.

`resources/css` and `resources/js`

- Laravel Vite scaffold assets.

### Public Folder

`public/index.php`

- Entry point for Laravel when served by Apache/XAMPP.

`public/robots.txt`

- Search engine instruction file.

`public/favicon.ico`

- Browser icon.

`public/storage`

- Storage symlink for uploaded files when enabled.

### Storage Folder

`storage/app/public/products`

- Uploaded product images are stored here locally.

`storage/framework`

- Laravel cache, sessions, and compiled views.

`storage/logs`

- Laravel log files.

Do not commit generated storage files unless specifically needed.

### Frontend Folder

`frontend/package.json`

- Next.js frontend dependencies and scripts.

`frontend/package-lock.json`

- Locks exact frontend package versions.

`frontend/vercel.json`

- Tells Vercel this is a Next.js project.

`frontend/.env.example`

- Example frontend environment file.

`frontend/.env.local`

- Local frontend environment.
- Contains `NEXT_PUBLIC_API_ORIGIN`.
- Do not commit this file.

`frontend/lib/apiClient.js`

- Central API helper.
- Sets API base URL and sends requests to Laravel.

`frontend/lib/shopStorage.js`

- Handles local browser storage for cart and logged-in user state.

`frontend/pages`

- Next.js pages.
- `index.js`: home page
- `products.js`: menu/products page
- `cart.js`: cart and checkout page
- `orders.js`: customer orders page
- `about.js`: about page
- `_app.js`: global app wrapper

`frontend/components`

- Reusable UI components.
- Includes navbar, hero, menu, product cards, login/register modal, ratings modal, feedback modal, and footer.

`frontend/styles/globals.css`

- Main frontend styling.
- Includes responsive mobile styles.

`frontend/public/images`

- Static frontend images, including the Gelly's Delight logo.

### Tests Folder

`tests/Feature`

- Feature/browser-like Laravel tests.

`tests/Unit`

- Unit tests.

`tests/TestCase.php`

- Base Laravel test class.

## How Data Flows

Product list:

```text
frontend/components/Menu.js
    -> frontend/lib/apiClient.js
    -> GET /api/products
    -> app/Http/Controllers/Api/ProductController.php
    -> products table
```

Register/login:

```text
frontend/components/AuthModal.js
    -> POST /api/users/register or /api/users/login
    -> app/Http/Controllers/Api/UserController.php
    -> users table and Laravel session
```

Checkout:

```text
frontend/pages/cart.js
    -> POST /api/orders
    -> app/Http/Controllers/Api/OrderController.php
    -> orders and order_items tables
```

Admin product management:

```text
Laravel admin dashboard
    -> routes/web.php
    -> app/Http/Controllers/Admin/ProductController.php
    -> products table and storage/app/public/products
```

Ratings:

```text
frontend/components/RatingModal.js
    -> POST /api/ratings
    -> app/Http/Controllers/Api/RatingController.php
    -> ratings table
```

## Testing

Run Laravel tests:

```bash
php artisan test
```

Build the frontend:

```bash
cd frontend
npm.cmd run build
```

Both should pass before presentation.

## Common Problems

Products do not load:

- XAMPP Apache may be stopped.
- XAMPP MySQL may be stopped.
- Cloudflare Tunnel may be stopped.
- Vercel `NEXT_PUBLIC_API_ORIGIN` may be using an old tunnel URL.

Register/login has CSRF or session issues:

- Clear Laravel cache with `php artisan optimize:clear`.
- Make sure `APP_URL` uses the current HTTPS tunnel URL.
- Make sure `CORS_ALLOWED_ORIGINS` includes the Vercel URL.

Admin login shows `419 PAGE EXPIRED`:

- Refresh the page.
- Try incognito/private browsing.
- Clear Laravel cache.
- Confirm the tunnel URL is current.

Cloudflare tunnel URL changed:

- Update Vercel `NEXT_PUBLIC_API_ORIGIN`.
- Update Laravel `.env` `APP_URL`.
- Run `php artisan config:clear`.
- Redeploy Vercel.

Product with ratings/orders cannot be deleted:

- The project now deletes related ratings and order items before deleting a product.
- If the issue returns, check database foreign key relationships.

## Notes For Laptop server

- Keep the laptop plugged in.
- Disable sleep mode.
- Keep XAMPP Apache and MySQL running.
- Keep Cloudflare Tunnel running.
- Do not expose real passwords in GitHub.
- Do not commit `.env` or `.env.local`.

More demo-specific notes are in:

```text
DEMO_DOCUMENTATION.md
```
