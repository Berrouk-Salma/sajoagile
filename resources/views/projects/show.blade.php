<x-app-layout>
    <h1>Détails du projet</h1>

    <div class="project-details">
        <p><strong>Nom :</strong> {{ $project->name }}</p>
        <p><strong>Description :</strong> {{ $project->description }}</p>
        <p><strong>Date de début :</strong> {{ $project->start_date }}</p>
        <p><strong>Date de fin :</strong> {{ $project->end_date }}</p>
    </div>

    <a href="{{ route('projects.index') }}">Retour à la liste</a>
    <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">Modifier le projet</a>
</x-app-layout>
