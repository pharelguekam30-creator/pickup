@extends('layouts.app')

@section('title', isset($plan) ? 'Modifier le plan' : 'Nouveau plan')

@section('content')
<div style="max-width:600px;margin:0 auto;">
    <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;margin-bottom:1.5rem;">{{ isset($plan) ? 'Modifier le plan' : 'Nouveau plan' }}</h1>

    <form method="POST" action="{{ isset($plan) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" style="background:#fff;border-radius:1rem;padding:1.5rem;box-shadow:0 2px 8px #00000011;">
        @csrf
        @if(isset($plan)) @method('PUT') @endif

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Nom</label>
            <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required class="form-field" style="width:100%;padding:.7rem;border:1px solid #d1d5db;border-radius:8px;">
        </div>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Description</label>
            <textarea name="description" rows="3" class="form-field" style="width:100%;padding:.7rem;border:1px solid #d1d5db;border-radius:8px;">{{ old('description', $plan->description ?? '') }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Type</label>
                <select name="type" required class="form-field" style="width:100%;padding:.7rem;border:1px solid #d1d5db;border-radius:8px;">
                    <option value="familial" {{ old('type', $plan->type ?? '') === 'familial' ? 'selected' : '' }}>Familial</option>
                    <option value="entreprise" {{ old('type', $plan->type ?? '') === 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Collectes / semaine</label>
                <input type="number" name="collections_per_week" value="{{ old('collections_per_week', $plan->collections_per_week ?? 1) }}" min="1" max="7" required class="form-field" style="width:100%;padding:.7rem;border:1px solid #d1d5db;border-radius:8px;">
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Jours de collecte</label>
            <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                    @php $checked = isset($plan) && in_array($day, $plan->collection_days ?? []); @endphp
                    <label style="display:flex;align-items:center;gap:.3rem;padding:.3rem .6rem;background:#f3f4f6;border-radius:6px;font-size:.85rem;cursor:pointer;">
                        <input type="checkbox" name="collection_days[]" value="{{ $day }}" {{ $checked ? 'checked' : '' }}>
                        {{ ucfirst($day) }}
                    </label>
                @endforeach
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr auto;gap:1rem;margin-bottom:1rem;align-items:end;">
            <div>
                <label style="display:block;font-weight:600;color:#374151;margin-bottom:.3rem;">Prix par mois (FCFA)</label>
                <input type="number" name="price_per_month" value="{{ old('price_per_month', $plan->price_per_month ?? 0) }}" min="0" required class="form-field" style="width:100%;padding:.7rem;border:1px solid #d1d5db;border-radius:8px;">
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:.5rem;font-weight:600;color:#374151;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                    Actif
                </label>
            </div>
        </div>

        <button type="submit" style="width:100%;padding:.8rem;background:#4f46e5;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">{{ isset($plan) ? 'Enregistrer' : 'Créer' }}</button>
    </form>
</div>
@endsection
