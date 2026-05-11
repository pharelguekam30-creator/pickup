<!-- resources/views/users/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-gradient-to-br from-blue-50 to-green-50 min-h-screen">
    <h1 class="text-4xl font-bold mb-6 text-center text-blue-800">Liste des utilisateurs</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 text-center">
        <a href="{{ route('users.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-lg">
            <i class="fas fa-plus mr-2"></i>Ajouter un utilisateur
        </a>
    </div>

    <!-- Graphique des rôles -->
    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Répartition des rôles</h2>
        <div class="w-full max-w-md mx-auto" style="height:320px;">
            <canvas id="rolesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gradient-to-r from-blue-600 to-green-600 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Nom</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rôle</th>
                    <th class="py-3 px-4 text-left">Téléphone</th>
                    <th class="py-3 px-4 text-left">Ville</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="py-3 px-4">{{ $user->id }}</td>
                        <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($user->role === 'vidangeur') bg-blue-100 text-blue-800
                                @elseif($user->role === 'menagere') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $user->phone ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $user->city ?? '-' }}</td>
                        <td class="py-3 px-4 flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-200">
                                <i class="fas fa-edit mr-1"></i>Modifier
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour le graphique
    const roleCounts = {
        vidangeur: {{ $users->where('role', 'vidangeur')->count() }},
        menagere: {{ $users->where('role', 'menagere')->count() }},
        admin: {{ $users->where('role', 'admin')->count() }}
    };

    const ctx = document.getElementById('rolesChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Vidangeurs', 'Ménagères', 'Admins'],
            datasets: [{
                data: [roleCounts.vidangeur, roleCounts.menagere, roleCounts.admin],
                backgroundColor: [
                    '#3b82f6', // Bleu pour vidangeur
                    '#8b5cf6', // Violet pour menagere
                    '#6b7280'  // Gris pour admin
                ],
                borderColor: [
                    '#2563eb',
                    '#7c3aed',
                    '#4b5563'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Répartition des utilisateurs par rôle'
                }
            }
        }
    });
</script>
@endsection
