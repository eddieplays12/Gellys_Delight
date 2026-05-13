<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelly's Delights Admin - @yield('title', 'Dashboard')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #d4755f 0%, #c85a47 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-nav {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-nav a {
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .header-nav a:hover {
            background: rgba(255,255,255,0.2);
        }

        .container-wrapper {
            display: flex;
            min-height: calc(100vh - 70px);
        }

        .sidebar {
            width: 250px;
            background: white;
            border-right: 1px solid #e0e0e0;
            padding: 2rem 0;
        }

        .sidebar-item {
            padding: 1rem 1.5rem;
            color: #333;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: #f9f0ed;
            border-left-color: #d4755f;
            color: #d4755f;
        }

        .sidebar-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #999;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-header h2 {
            font-size: 2rem;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-primary {
            background: #d4755f;
            color: white;
        }

        .btn-primary:hover {
            background: #c85a47;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #f5c6cb;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .table th {
            background: #f5f5f5;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            font-size: 0.9rem;
            color: #999;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #d4755f;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 6px;
            object-fit: cover;
        }

        .admin-product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.75rem;
        }

        .admin-product-card {
            background: white;
            border: 1px solid #ffd2e5;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(212, 117, 95, 0.12);
        }

        .admin-product-image-wrap {
            position: relative;
            aspect-ratio: 16 / 10;
            background: #ffe8f2;
            overflow: hidden;
        }

        .admin-product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .admin-product-badge {
            position: absolute;
            top: 0.9rem;
            right: 0.9rem;
            background: white;
            color: #d4755f;
            border-radius: 18px;
            padding: 0.45rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .admin-product-body {
            padding: 1.4rem;
        }

        .admin-product-body h3 {
            font-size: 1.2rem;
            margin-bottom: 0.6rem;
            color: #2f2f2f;
        }

        .admin-product-description {
            color: #666;
            min-height: 2.8rem;
            margin-bottom: 1.2rem;
            line-height: 1.5;
        }

        .admin-product-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.2rem;
        }

        .admin-product-meta strong {
            display: block;
            color: #d4755f;
            font-size: 1.05rem;
            margin-top: 0.2rem;
        }

        .admin-product-label {
            color: #999;
            font-size: 0.78rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .admin-product-card.is-bestseller {
            border-color: #f2a900;
            box-shadow: 0 14px 34px rgba(242, 169, 0, 0.2);
        }

        .admin-bestseller-badge {
            position: absolute;
            left: 0.9rem;
            top: 0.9rem;
            background: #f2a900;
            color: #3a2a00;
            border-radius: 18px;
            padding: 0.45rem 0.85rem;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.16);
        }
        .admin-product-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .admin-product-actions form,
        .admin-product-actions .btn {
            width: 100%;
        }

        .admin-product-actions .btn {
            text-align: center;
        }
        @media (max-width: 768px) {
            .container-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
                padding: 1rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gelly's Delights Admin</h1>
        <div class="header-nav">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="{{ route('admin.ratings.index') }}">Ratings</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: rgba(255,255,255,0.15); color: white; border: 0; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font: inherit;">Logout</button>
            </form>
            <a href="/">Back to Store</a>
        </div>
    </div>

    <div class="container-wrapper">
        <div class="sidebar">
            <div class="sidebar-title">Management</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                Manage Products
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                Track Orders
            </a>
            <a href="{{ route('admin.ratings.index') }}" class="sidebar-item {{ request()->routeIs('admin.ratings.*') ? 'active' : '' }}">
                Customer Ratings
            </a>
        </div>

        <div class="main-content">
            @if ($message = session('success'))
                <div class="alert alert-success">
                    Success: {{ $message }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    Please fix the errors below:
                    <ul style="margin-top: 0.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>




