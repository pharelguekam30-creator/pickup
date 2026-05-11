@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<h2>Profil de {{ $user->name }}</h2>
<p>Email: {{ $user->email }}</p>
<p>Téléphone: {{ $user->phone }}</p>
<p>Solde: {{ $user->solde }} FCFA</p>

<h3>Historique des réservations</h3>
<ul>
    @foreach($user->reservations as $reservation)
        <li>{{ $reservation->service->name }} le {{ $reservation->date }} à {{ $reservation->time }}</li>
    @endforeach
</ul>
@endsection
