<x-app-layout>
    <h1>Créer un Sprint pour le projet : {{ $project->name }}</h1>

    <form method="POST" action="{{ route('sprints.store', $project) }}">
        @csrf
        <div>
            <label for="name">Objectif</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="start_date">Date de début</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="form-control">
            @error('start_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="end_date">Date de fin</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="form-control">
            @error('end_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Créer Sprint</button>
    </form>
</x-app-layout>
