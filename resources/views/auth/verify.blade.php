@extends('layouts.app')

@section('title', 'Verification')

@section('main-background', '1')

@section('content')
<div style="width:100%;max-width:420px;margin:0 auto;">
    <div style="background:#fff;border-radius:24px;padding:2rem;text-align:center;box-shadow:0 24px 60px rgba(15,23,42,.08);">
        <h2 style="font-size:1.8rem;font-weight:bold;color:#2563eb;margin-bottom:.5rem;">Verification</h2>
        <p style="color:#64748b;margin-bottom:0.5rem;">
            @if (session('message'))
                {{ session('message') }}
            @else
                Un code a ete envoye selon le canal de verification choisi.
            @endif
        </p>
        @if (auth()->user()->verification_code)
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:12px;padding:12px;margin-bottom:1rem;">
                <p style="font-size:0.85rem;color:#166534;margin:0 0 4px;">Votre code de verification :</p>
                <p style="font-size:2rem;font-weight:bold;letter-spacing:8px;color:#16a34a;margin:0;">{{ auth()->user()->verification_code }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div style="border:1px solid #fca5a5;padding:12px;border-radius:8px;background:#fee2e2;color:#b91c1c;margin-bottom:16px;">
                <ul style="margin:0;padding-left:20px;text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('verification.verify') }}" method="POST">
            @csrf
            <input type="text" name="code" placeholder="Entrez le code a 6 chiffres"
                   maxlength="6" inputmode="numeric" pattern="[0-9]*"
                   style="width:100%;padding:14px;font-size:1.5rem;text-align:center;letter-spacing:8px;border:2px solid #cbd5e1;border-radius:12px;margin-bottom:1rem;outline:none;"
                   required autofocus>
            <button type="submit" style="width:100%;padding:12px;border:none;border-radius:10px;background:linear-gradient(90deg,#4f46e5,#2563eb);color:#fff;font-weight:700;cursor:pointer;">
                Verifier
            </button>
        </form>

        <form action="{{ route('verification.resend') }}" method="POST" style="margin-top:1rem;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#2563eb;font-weight:600;cursor:pointer;text-decoration:underline;">
                Renvoyer le code
            </button>
        </form>
    </div>
</div>
@endsection