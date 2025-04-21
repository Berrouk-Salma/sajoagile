<x-app-layout>
    <h1>Modifier le projet</h1>

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom du projet</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $project->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="start_date">Date de début</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $project->start_date) }}" required>
        </div>

        <div class="form-group">
            <label for="end_date">Date de fin</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $project->end_date) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour le projet</button>
    </form>
</x-app-layout>
