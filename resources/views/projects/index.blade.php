<x-app-layout>
    <h1>Mes projets</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">Créer un projet</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2>Projets créés par moi</h2>
    @if($projectsCreated && $projectsCreated->count() > 0)
        @foreach($projectsCreated as $project)
            <div class="project">
                <h3>
                    <a href="{{ route('projects.show', $project) }}">
                        {{ $project->name }}
                    </a>
                </h3>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</button>
                </form>
            </div>
        @endforeach
    @else
        <p>Aucun projet créé trouvé.</p>
    @endif

    <hr>

    <h2>Projets où je peux participer</h2>
    @if($projectsJoined && $projectsJoined->count() > 0)
        @foreach($projectsJoined as $project)
            <div class="project">
                <h3>
                    <a href="{{ route('projects.show', $project) }}">
                        {{ $project->name }}
                    </a>
                </h3>
                <a href="{{ route('projects.show', $project) }}" class="btn btn-info">Voir le projet</a>
            </div>
        @endforeach
    @else
        <p>Aucun projet où vous pouvez participer trouvé.</p>
    @endif
</x-app-layout>
