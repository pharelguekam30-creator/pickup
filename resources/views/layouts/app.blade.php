<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Pickup</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png.jpeg') }}">
    <script src="https://cdn.tailwindcss.com" onerror="document.getElementById('tailwind-fallback').removeAttribute('media')"></script>
    <style id="tailwind-fallback" media="all">
        .container{max-width:1200px;margin:0 auto;padding:0 1rem}.flex{display:flex}.flex-wrap{flex-wrap:wrap}.items-center{align-items:center}.justify-between{justify-content:space-between}.text-center{text-align:center}.w-full{width:100%}.hidden{display:none}.inline{display:inline}.block{display:block}.inline-block{display:inline-block}.rounded{border-radius:.5rem}.px-4{padding-left:1rem;padding-right:1rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.px-3{padding-left:.75rem;padding-right:.75rem}.py-1{padding-top:.25rem;padding-bottom:.25rem}.p-4{padding:1rem}.m-0{margin:0}.mt-4{margin-top:1rem}.mb-4{margin-bottom:1rem}.gap-2{gap:.5rem}.gap-4{gap:1rem}.text-white{color:#fff}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.font-bold{font-weight:700}.font-semibold{font-weight:600}.hover\:bg-gray-700:hover{background:#374151}.hover\:bg-blue-700:hover{background:#1d4ed8}.hover\:bg-green-700:hover{background:#15803d}.transition{transition:all .2s}.border-b{border-bottom:1px solid #e5e7eb}.bg-gray-50{background:#f9fafb}.bg-gray-600{background:#4b5563}.bg-green-600{background:#16a34a}.bg-blue-600{background:#2563eb}.bg-red-600{background:#dc2626}
    </style>
    <link rel="stylesheet" href="/fontawesome/all.min.css">
    @stack('styles')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #1f2937;
            background: #f3f4f6;
        }

        header {
            width: 100%;
            background: #2563eb;
            color: #fff;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 16px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .logo img { height: 28px; width: auto; border-radius: 6px; }

        .hamburger {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 4px 8px;
        }

        nav { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }

        header nav a, .logout-form button {
            color: #fff;
            text-decoration: none;
            padding: 6px 10px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;
            white-space: nowrap;
        }

        header nav a:hover, .logout-form button:hover { color: #ffc107; }

        .logout-form button {
            border: none;
            background: transparent;
            cursor: pointer;
        }

        main {
            flex: 1;
            padding: 1.5rem 0;
        }

        .page-wrapper {
            width: min(1200px, calc(100% - 32px));
            margin: 0 auto;
            padding: 1rem 1.25rem 2rem;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .main-background {
            background: url('{{ asset("images/fond.jpeg") }}') no-repeat center center / cover;
            position: relative;
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .main-background::before {
            content: "";
            position: absolute;
            top:0; left:0; width:100%; height:100%;
            background: rgba(221, 227, 223, 0.863);
        }

        .main-background > * { position: relative; z-index: 1; }

        .main-background h1 {
            font-size: clamp(1.8rem, 5vw, 3rem);
            margin-bottom: 16px;
        }

        .main-background p {
            font-size: clamp(0.95rem, 2.5vw, 1.2rem);
            margin-bottom: 24px;
        }

        .main-background .btn {
            background: #ffc107;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
            display: inline-block;
        }

        .main-background .btn:hover { background: #e0a800; }

        .card {
            width: 160px;
            padding: 10px;
            font-size: 0.85rem;
            border-radius: 8px;
            background: rgba(109, 221, 80, 0.9);
            box-shadow: 0 2px 5px rgb(6, 237, 72);
            margin: 8px;
            display: inline-block;
            vertical-align: top;
        }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: 16px auto;
            padding: 16px;
            background: #f8fafc;
        }

        .bg-white { background-color: #fff !important; }
        .text-gray-600 { color: #4b5563 !important; }
        .text-gray-700 { color: #374151 !important; }
        .text-gray-500 { color: #6b7280 !important; }
        .text-blue-700 { color: #1d4ed8 !important; }
        .text-yellow-700 { color: #b45309 !important; }
        .text-green-700 { color: #047857 !important; }
        .text-red-700 { color: #b91c1c !important; }
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-yellow-50 { background-color: #fffbeb !important; }
        .bg-green-50 { background-color: #ecfdf5 !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .bg-gray-100 { background-color: #f3f4f6 !important; }
        .border { border: 1px solid #e5e7eb !important; }
        .border-gray-200 { border-color: #e5e7eb !important; }
        .rounded-lg { border-radius: 0.75rem !important; }
        .p-6 { padding: 1.5rem !important; }
        .p-4 { padding: 1rem !important; }
        .p-5 { padding: 1.25rem !important; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .md\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .overflow-x-auto { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .inline { display: inline; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .border-b { border-bottom: 1px solid #e5e7eb; }
        .hover\:bg-gray-50:hover { background-color: #f9fafb; }
        .transition { transition: all 0.15s ease-in-out; }
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }
        .uppercase { text-transform: uppercase; }
        .tracking-wide { letter-spacing: 0.05em; }

        .bg-indigo-500 { background-color: #6366f1 !important; }
        .hover\:bg-indigo-500:hover { background-color: #6366f1 !important; }
        .bg-green-600 { background-color: #16a34a !important; }
        .hover\:bg-green-700:hover { background-color: #15803d !important; }
        .bg-red-600 { background-color: #dc2626 !important; }
        .hover\:bg-red-700:hover { background-color: #b91c1c !important; }
        .bg-gray-600 { background-color: #4b5563 !important; }
        .hover\:bg-gray-700:hover { background-color: #374151 !important; }

        .text-white { color: #fff !important; }
        .text-indigo-100 { color: #e0e7ff !important; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .rounded { border-radius: 0.25rem !important; }
        .text-left { text-align:left; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .text-center { text-align:center; }
        .table-auto { width:100%; border-collapse: collapse; }

        .dashboard-top {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .dashboard-card {
            border-radius: 12px;
            padding: 14px;
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .dashboard-card.total { background: linear-gradient(90deg, #4f46e5, #3b82f6); }
        .dashboard-card.pending { background: linear-gradient(90deg, #f59e0b, #f97316); }
        .dashboard-card.accepted { background: linear-gradient(90deg, #10b981, #059669); }
        .dashboard-card.canceled { background: linear-gradient(90deg, #ef4444, #b91c1c); }

        .dashboard-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .auth-page {
            min-height: calc(100vh - 110px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px 12px;
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            padding: 24px;
        }

        .auth-card h2 {
            margin-bottom: 16px;
            color: #1e3a8a;
            font-size: 1.5rem;
            text-align: center;
            font-weight: 700;
        }

        .form-label { display: block; color: #334155; margin-bottom: 4px; font-weight: 600; font-size: 0.9rem; }

        .form-field {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            margin-bottom: 12px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-field:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .btn-primary {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #4f46e5, #2563eb);
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn-primary:hover { transform: translateY(-1px); filter: brightness(1.07); }

        .text-link { color: #2563eb; text-decoration: none; font-weight: 600; }
        .text-link:hover { text-decoration: underline; }

        .dashboard-section h2 {
            margin-bottom: 12px;
            color: #1f2937;
            font-size: 1.1rem;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            color: #374151;
        }

        .dashboard-table th,
        .dashboard-table td {
            padding: 10px 8px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }

        .dashboard-table thead { background: #f3f4f6; color: #1f2937; }

        .badge-pending { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .badge-accepted { background: #d1fae5; color: #064e3b; padding: 2px 6px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .badge-canceled { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
        .badge-completed { background: #dbeafe; color: #1e3a8a; padding: 2px 6px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; display: inline-block; }

        .btn-sm {
            border: none;
            padding: 5px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            cursor: pointer;
            color: #fff;
            margin: 2px;
        }

        .btn-accept { background: #059669; }
        .btn-cancel { background: #dc2626; }
        .btn-delete { background: #4b5563; }

        .mobile-sidebar-toggle { display: none; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            z-index: 999;
        }

        .sidebar-overlay.active { display: block; }

        footer {
            text-align: center;
            padding: 12px 0;
            background: #0d6efd;
            color: #fff;
            font-size: 0.85rem;
        }

        .flex-wrap-force { flex-wrap: wrap !important; }
        .gap-2-force { gap: 0.5rem !important; }
        .w-full-force { width: 100% !important; }

        @media (max-width: 768px) {
            .hamburger { display: block; }
            nav {
                display: none;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                padding: 8px 0;
                gap: 2px;
            }
            nav.open { display: flex; }
            header nav a, .logout-form button {
                padding: 6px 12px;
                width: 100%;
                text-align: left;
            }
            .logout-form { width: 100%; }
            .header-container { flex-wrap: wrap; }

            .page-wrapper {
                width: calc(100% - 20px);
                padding: 0.8rem 1rem 1.5rem;
                border-radius: 16px;
            }

            main { padding: 1rem 0; }

            .main-background { min-height: 60vh; padding: 16px; }
            .main-background h1 { font-size: 1.6rem; }
            .main-background p { font-size: 0.9rem; }

            .card { width: 130px; margin: 6px; }

            .dashboard-wrapper { padding: 12px; }
            .dashboard-section { padding: 12px; }
            .dashboard-section h2 { font-size: 1rem; }
            .dashboard-table { font-size: 0.8rem; }
            .dashboard-table th, .dashboard-table td { padding: 6px 4px; }

            .dashboard-top { grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 8px; }
            .dashboard-card { padding: 10px; }

            .auth-page { padding: 16px 8px; }
            .auth-card { padding: 16px; }
            .auth-card h2 { font-size: 1.3rem; }
            .form-field { padding: 8px 10px; font-size: 0.9rem; margin-bottom: 10px; }

            .sidebar-overlay.active { display: block; }
            aside#sidebar { display: none !important; }
            aside#sidebar.mobile-visible { display: block !important; }
            aside.mobile-visible {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 280px !important;
                height: 100% !important;
                z-index: 1000 !important;
                border-radius: 0 16px 16px 0 !important;
                overflow-y: auto !important;
            }
            .mobile-sidebar-toggle { display: inline-block; }
        }

        @media (max-width: 480px) {
            .logo { font-size: 1.1rem; }
            .logo img { height: 24px; }
            .header-container { padding: 8px 12px; }

            .card { width: 100%; margin: 6px 0; display: block; }

            .dashboard-top { grid-template-columns: 1fr 1fr; gap: 6px; }
            .dashboard-card { padding: 8px; font-size: 0.8rem; }

            .dashboard-table { font-size: 0.7rem; }
            .dashboard-table th, .dashboard-table td { padding: 4px 3px; }

            .auth-card { padding: 12px; }
            .btn-primary { padding: 10px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="{{ asset('images/logo.png.jpeg') }}" alt="Pickup logo">
                Pickup
            </div>
            <button class="hamburger" id="hamburger" onclick="document.querySelector('nav').classList.toggle('open')">&#9776;</button>
            <nav id="nav-menu">
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('services.index') }}">Services</a>
                <a href="{{ route('map.index') }}">Carte</a>

                @auth
                    @if(auth()->user()->role == 'menagere')
                        <a href="{{ route('avis.index') }}">Avis</a>
                        <a href="{{ route('menagere.dashboard') }}">Dashboard</a>
                    @elseif(auth()->user()->role == 'vidangeur')
                        <a href="{{ route('vidangeur.dashboard') }}">Dashboard</a>
                    @elseif(auth()->user()->role == 'admin')
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    @endif
                    <a href="{{ route('payments.index') }}">Portefeuille</a>
                    <a href="{{ route('profile') }}">Mon Profil</a>

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('register') }}">Créer un compte</a>
                    <a href="{{ route('login') }}">Connexion</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        @hasSection('main-background')
            <div class="main-background">
                <div class="page-wrapper">
                    @yield('content')
                </div>
            </div>
        @else
            <div style="display:flex;gap:1.5rem;max-width:1200px;margin:0 auto;padding:0 16px;">
                @hasSection('sidebar')
                    <button class="mobile-sidebar-toggle" onclick="toggleSidebar()" style="background:#2563eb;color:#fff;border:none;padding:6px 12px;border-radius:8px;cursor:pointer;margin-bottom:8px;font-size:0.85rem;">&#9776; Menu</button>
                    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
                    <aside id="sidebar" style="width:220px;flex-shrink:0;background:#fff;border-radius:16px;padding:1.25rem;box-shadow:0 4px 12px rgba(15,23,42,0.06);">
                        <button class="mobile-sidebar-toggle" onclick="toggleSidebar()" style="float:right;background:none;border:none;font-size:1.2rem;cursor:pointer;color:#64748b;">&times;</button>
                        <div style="clear:both;"></div>
                        @yield('sidebar')
                    </aside>
                @endif
                @hasSection('fullwidth')
                    <div style="flex:1;min-width:0;">
                        @yield('content')
                    </div>
                @else
                    <div class="page-wrapper" style="flex:1;">
                        @yield('content')
                    </div>
                @endif
            </div>
        @endif
    </main>

    <script>
    window.globalInternetError = function(containerId) {
        var el = document.getElementById(containerId);
        if (el) {
            el.innerHTML = '<div style="padding:1rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:.5rem;color:#991b1b;text-align:center;">' +
                '<span style="font-size:1.25rem;">&#9888;</span> Verifiez votre connexion internet.</div>';
        }
    };

    function toggleSidebar() {
        var aside = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth <= 768) {
            aside.classList.toggle('mobile-visible');
            overlay.classList.toggle('active');
        }
    }

    document.addEventListener('click', function(e) {
        var nav = document.getElementById('nav-menu');
        var ham = document.getElementById('hamburger');
        if (window.innerWidth <= 768 && nav && ham && !nav.contains(e.target) && !ham.contains(e.target)) {
            nav.classList.remove('open');
        }
    });
    </script>
    @stack('scripts')

    <!-- FOOTER -->
    <footer>
        <p>&copy; {{ date('Y') }} Pickup. Tous droits réservés.</p>
    </footer>
</body>
</html>
