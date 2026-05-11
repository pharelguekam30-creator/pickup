@extends('layouts.app')

@section('title', 'Donner un avis')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-6 text-center">Donner un avis pour : {{ $service->name }}</h1>

    <form action="{{ route('avis.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        <input type="hidden" name="service_id" value="{{ $service->id }}">

        <div class="mb-4">
            <label for="note" class="block text-gray-700 font-bold mb-2">Note (1 à 5) :</label>
            <select name="note" id="note" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <option value="">-- Choisir une note --</option>
                @for($i=1; $i<=5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            @error('note')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="commentaire" class="block text-gray-700 font-bold mb-2">Commentaire :</label>
            <textarea name="commentaire" id="commentaire" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('commentaire') }}</textarea>
            @error('commentaire')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Retour aux services</a>
        </div>
    </form>
</div>
@endsection
