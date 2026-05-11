@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="home-page">
    <section class="hero-section text-center">
        <div class="hero-card">
            <h1>Bienvenue sur Pickup</h1>
            <p>Réservez facilement vos vidangeurs de poubelles en ligne, où que vous soyez.</p>
            <a href="{{ route('choix.role') }}" class="btn-primary">S'inscrire</a>
        </div>
    </section>

    <section class="testimonials-section">
        <h2>Témoignages</h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <p>"En tant qu'étudiante à Yaoundé, je n'ai pas toujours le temps de sortir mes poubelles. Grâce à Pickup, un voisin s'en occupe maintenant et je peux me concentrer sur mes études."</p>
                <strong>Awa Mballa</strong>
            </div>
            <div class="testimonial-card">
                <p>"Je suis chauffeur de taxi à Douala et j'ai du temps libre. En devenant vidangeur sur Pickup, je gagne un complément de revenu tout en aidant ma communauté."</p>
                <strong>Thierry Bissong</strong>
            </div>
            <div class="testimonial-card">
                <p>"Notre famille à Bamenda est très occupée. Pickup nous permet de ne plus penser à la collecte des déchets et nous fait gagner du temps chaque semaine."</p>
                <strong>Nadia Kamga</strong>
            </div>
        </div>
    </section>
</div>

<style>
.home-page {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}
.hero-section {
    background: linear-gradient(135deg, #2563eb, #10b981);
    border-radius: 28px;
    color: #fff;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
}
.hero-card {
    max-width: 820px;
    margin: 0 auto;
}
.hero-section h1 {
    font-size: clamp(2.5rem, 4vw, 4.5rem);
    margin-bottom: 1.5rem;
    line-height: 1.05;
}
.hero-section p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    max-width: 680px;
    margin-left: auto;
    margin-right: auto;
}
.btn-primary {
    display: inline-block;
    background: #111827;
    color: #22c55e;
    padding: 1rem 2.2rem;
    border-radius: 999px;
    font-weight: 700;
    transition: transform 0.25s ease, background-color 0.25s ease;
    text-decoration: none;
}
.btn-primary:hover {
    transform: translateY(-3px);
    background: #111827;
    color: #d1fae5;
}
.testimonials-section {
    padding: 2.5rem 1.5rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 28px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    text-align: center;
}
.testimonials-section h2 {
    font-size: 2.25rem;
    margin-bottom: 2rem;
}
.testimonial-grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
}
.testimonial-card {
    background: #f8fafc;
    padding: 1.8rem;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.testimonial-card p {
    font-size: 1rem;
    color: #334155;
    margin-bottom: 1.5rem;
}
.testimonial-card strong {
    color: #0f172a;
    font-size: 0.95rem;
}
</style>
@endsection
