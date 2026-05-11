@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Modifier l'utilisateur</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-700">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Rôle</label>
            <select name="role" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Sélectionnez un rôle</option>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">Enregistrer</button>
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800">Retour à la liste</a>
        </div>
    </form>
</div>
@endsection