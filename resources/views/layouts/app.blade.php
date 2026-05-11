<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Pickup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #1f2937;
            background: #f3f4f6;
        }

        /* HEADER */
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
            padding: 15px 20px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        header nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            transition: color 0.3s;
        }

        header nav a:hover {
            color: #ffc107;
        }

        .logout-form button {
            color: #fff;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 500;
            transition: color 0.3s;
        }

        .logout-form button:hover {
            color: #ffc107;
        }

        /* MAIN */
        main {
            flex: 1;
            padding: 2rem 0;
        }

        .page-wrapper {
            width: min(1200px, calc(100% - 40px));
            margin: 0 auto;
            padding: 1rem 1.25rem 2rem;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .main-background {
            background: url('{{ asset("images/background.jpg") }}') no-repeat center center / cover;
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
            top:0;
            left:0;
            width:100%;
            height:100%;
            background: rgba(221, 227, 223, 0.863);
        }

        .main-background > * {
            position: relative;
            z-index: 1;
        }

        .main-background h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .main-background p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .main-background .btn {
            background: #ffc107;
            color: #000;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .main-background .btn:hover {
            background: #e0a800;
        }

        /* CARTES - Taille réduite */
        .card {
            width: 180px;
            padding: 10px;
            font-size: 0.85rem;
            border-radius: 8px;
            background: rgba(109, 221, 80, 0.9);
            box-shadow: 0 2px 5px rgb(6, 237, 72);
            margin: 10px;
            display: inline-block;
            vertical-align: top;
        }

        /* Dashboard Vidangeur */
        .dashboard-wrapper {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f8fafc;
        }

        /* Utilitaires Tailwind-like (pour compatibilité avec le template existant) */
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
        .overflow-x-auto { overflow-x: auto; }
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

        .badge-pending, .badge-accepted, .badge-canceled { display:inline-block; padding:2px 8px; border-radius:999px; font-size:.7rem; }

        .btn-sm { color:white; border:0; padding:6px 10px; border-radius:8px; cursor:pointer; }
        .btn-accept { background:#059669; }
        .btn-cancel { background:#dc2626; }
        .btn-delete { background:#4b5563; }

        .text-left { text-align:left; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .text-center { text-align:center; }

        .table-auto { width:100%; border-collapse: collapse; }

        .text-xs { font-size: .75rem; }

        /* FOOTER */
        .dashboard-top {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .dashboard-card {
            border-radius: 12px;
            padding: 18px;
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            min-height: 106px;
        }

        .dashboard-card.total { background: linear-gradient(90deg, #4f46e5, #3b82f6); }
        .dashboard-card.pending { background: linear-gradient(90deg, #f59e0b, #f97316); }
        .dashboard-card.accepted { background: linear-gradient(90deg, #10b981, #059669); }
        .dashboard-card.canceled { background: linear-gradient(90deg, #ef4444, #b91c1c); }

        .dashboard-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .auth-page {
            min-height: calc(100vh - 110px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 16px;
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            padding: 30px;
        }

        .auth-card h2 {
            margin-bottom: 20px;
            color: #1e3a8a;
            font-size: 1.8rem;
            text-align: center;
            font-weight: 700;
        }

        .form-label {
            display: block;
            color: #334155;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-field {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            margin-bottom: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-field:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #4f46e5, #2563eb);
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.07);
        }

        .text-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .dashboard-section h2 {
            margin-bottom: 15px;
            color: #1f2937;
            font-size: 1.25rem;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            color: #374151;
        }

        .dashboard-table th,
        .dashboard-table td {
            padding: 12px 10px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }

        .dashboard-table thead {
            background: #f3f4f6;
            color: #1f2937;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-accepted { background: #d1fae5; color: #064e3b; }
        .badge-canceled { background: #fee2e2; color: #991b1b; }
        .badge-completed { background: #dbeafe; color: #1e3a8a; }

        .btn-sm {
            border: none;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            cursor: pointer;
            color: #fff;
            margin-right: 5px;
        }

        .btn-accept { background: #059669; }
        .btn-cancel { background: #dc2626; }
        .btn-delete { background: #4b5563; }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 15px 0;
            background: #0d6efd;
            color: #fff;
            font-size: 0.9rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            header nav {
                margin-top: 10px;
            }
            header nav a {
                margin: 5px 10px;
            }
            .main-background h1 {
                font-size: 2rem;
            }
            .main-background p {
                font-size: 1rem;
            }
            .card {
                width: 140px;
                font-size: 0.13rem;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fa fa-recycle"></i> Pickup
            </div>
            <nav>
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('services.index') }}">Services</a>

                @auth
                    @if(auth()->user()->role == 'menagere')
                        <a href="{{ route('avis.index') }}">Avis</a>
                    @endif
                    <a href="{{ route('dashboard') }}">Dashboard</a>

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
            <div class="page-wrapper">
                @yield('content')
            </div>
        @endif
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 Pickup. Tous droits réservés.</p>
    </footer>
</body>
</html>
