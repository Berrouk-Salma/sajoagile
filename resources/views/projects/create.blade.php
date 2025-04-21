<x-app-layout>
    <h1>Créer un nouveau projet</h1>

    <!-- Afficher les erreurs de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire de création de projet -->
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <!-- nom -->
        <div class="form-group">
            <label for="name">Nom du projet</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <!-- description -->
        <div class="form-group">
            <label for="description">Description du projet</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>

        <!-- la date de début -->
        <div class="form-group">
            <label for="start_date">Date de début</label>
            <input type="date" id="startDate" name="start_date" class="form-control" value="{{ old('startDate') }}" required>
        </div>

        <!-- la date de fin -->
        <div class="form-group">
            <label for="end_date">Date de fin</label>
            <input type="date" id="endDate" name="end_date" class="form-control" value="{{ old('endDate') }}" required>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Créer le projet</button>
    </form>

</x-app-layout>
