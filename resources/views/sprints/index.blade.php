<x-app-layout>
    <h1>Sprints du projet : {{ $project->name }}</h1>

    <a href="{{ route('sprints.create', $project) }}" class="btn btn-primary">Créer un sprint</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2>Sprints existants</h2>
    @if($sprints && $sprints->count() > 0)
        @foreach($sprints as $sprint)
            <div class="sprint">
                <h3>{{ $sprint->goal }}</h3>
                <p><strong>Start Date:</strong> {{ $sprint->start_date }}</p>
                <p><strong>End Date:</strong> {{ $sprint->end_date }}</p>
                <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}" class="btn btn-info">Voir le sprint</a>
                <a href="{{ route('projects.sprints.edit', [$project, $sprint]) }}" class="btn btn-warning">Modifier</a>

                <form action="{{ route('projects.sprints.destroy', [$project, $sprint]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sprint ?')">Supprimer</button>
                </form>
            </div>
        @endforeach
    @else
        <p>Aucun sprint trouvé pour ce projet.</p>
    @endif
</x-app-layout>
