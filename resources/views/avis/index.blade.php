@extends('layouts.app')

@section('title', 'Mes avis')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">

    <h1 class="text-3xl font-bold mb-8 text-center">Mes avis</h1>

    @if($avis->count() > 0)
        <div class="space-y-6">
            @foreach($avis as $review)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $review->service->name }}</h3>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-{{ $i <= $review->note ? 'yellow' : 'gray' }}-400">★</span>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">({{ $review->note }}/5)</span>
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-gray-700">{{ $review->contenu }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Vous n'avez pas encore donné d'avis.</p>
            <a href="{{ route('services.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Voir les services</a>
        </div>
    @endif
</div>
@endsection
