@extends('layouts.app')

@section('title', 'Choisissez votre rôle')

@section('content')
<div class="role-page">
    <div class="role-header">
        <h2>Choisissez votre rôle</h2>
        <p>Commencez par sélectionner le rôle qui correspond le mieux à votre besoin.</p>
    </div>

    <div class="role-grid">
        <div class="role-card" onclick="window.location='{{ route('register', ['role' => 'vidangeur']) }}'">
            <img src="{{ asset('images/vidangeur.png.jpeg') }}" alt="Vidangeur">
            <h3>Je suis Vidangeur</h3>
            <p>Proposez vos services de vidange aux ménages.</p>
        </div>

        <div class="role-card" onclick="window.location='{{ route('register', ['role' => 'menage']) }}'">
            <img src="{{ asset('images/menage.png.jpeg') }}" alt="Ménagere">
            <h3>Je suis un Ménage</h3>
            <p>Réservez facilement un vidangeur proche de chez vous.</p>
        </div>
    </div>
</div>

<style>
.role-page {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 2rem;
    align-items: center;
}
.role-header {
    max-width: 720px;
}
.role-header h2 {
    font-size: 2.75rem;
    margin-bottom: 0.75rem;
}
.role-header p {
    color: #475569;
    font-size: 1.1rem;
}
.role-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    width: 100%;
    max-width: 960px;
}
.role-card {
    cursor: pointer;
    border-radius: 24px;
    padding: 2rem 1.75rem;
    background: linear-gradient(180deg, #ecfccb 0%, #d9f99d 100%);
    border: 1px solid #d9f99d;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.role-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 50px rgba(15, 23, 42, 0.2);
}
.role-card img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 999px;
    border: 2px solid rgba(15, 23, 42, 0.08);
}
.role-card h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #0f172a;
}
.role-card p {
    color: #334155;
    line-height: 1.6;
}
</style>
@endsection
