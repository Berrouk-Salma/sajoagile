<x-app-layout>
    <h1>Détails du Sprint</h1>

    <div class="sprint-details">
        <p><strong>Nom du Sprint :</strong> {{ $sprint->name }}</p>
        <p><strong>Date de début :</strong> {{ $sprint->start_date }}</p>
        <p><strong>Date de fin :</strong> {{ $sprint->end_date }}</p>
        <p><strong>Projet :</strong> <a href="{{ route('projects.show', $sprint->project_id) }}">{{ $sprint->project->name }}</a></p>
    </div>

    <hr>

    <div class="actions">
        <a href="{{ route('sprints.edit', $sprint) }}" class="btn btn-warning">Modifier</a>

        <form action="{{ route('sprints.destroy', $sprint) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sprint ?')">Supprimer</button>
        </form>

        <a href="{{ route('projects.show', $sprint->project_id) }}" class="btn btn-secondary">Retour au projet</a>
    </div>
</x-app-layout>
