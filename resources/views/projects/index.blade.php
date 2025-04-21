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

    @if($projects && $projects->count() > 0)
        @foreach($projects as $project)
            <div class="project">
                <h3>{{ $project->name }}</h3>
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">Modifier</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">Supprimer</button>
                </form>
            </div>
        @endforeach
    @else
        <p>Aucun projet trouvé.</p>
    @endif

</x-app-layout>
