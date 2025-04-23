<x-app-layout>
    <h1>Détails du projet</h1>

    <div class="project-details">
        <p><strong>Nom :</strong> {{ $project->name }}</p>
        <p><strong>Description :</strong> {{ $project->description }}</p>
        <p><strong>Date de début :</strong> {{ $project->start_date }}</p>
        <p><strong>Date de fin :</strong> {{ $project->end_date }}</p>
    </div>

    <hr>

    <div class="sprints mt-4">
        <h2>Sprints du projet</h2>

        @if($project->sprints && $project->sprints->count() > 0)
            <ul>
                @foreach($project->sprints as $sprint)
                    <li>
                        <strong>{{ $sprint->goal }}</strong><br>
                        <span><strong>Date de début:</strong> {{ $sprint->start_date }}</span><br>
                        <span><strong>Date de fin:</strong> {{ $sprint->end_date }}</span><br>

                        <form action="{{ route('sprints.destroy', [$project, $sprint]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sprint ?')">Supprimer</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Aucun sprint trouvé pour ce projet.</p>
        @endif

        <div class="create-sprint mt-4">
            <a href="{{ route('sprints.create', $project) }}" class="btn btn-primary">Créer un nouveau sprint</a>
        </div>
    </div>

    <hr>

    <div class="search-users mt-4">
        <h2>Inviter un utilisateur</h2>
        <input type="text" id="user-search" placeholder="Rechercher par nom ou email..." class="form-control mb-2" />
        <ul id="search-results"></ul>
    </div>

    <a href="{{ route('projects.index') }}">Retour à la liste</a>
    <a href="{{ route('projects.edit', $project) }}" class="btn btn-warning">Modifier le projet</a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('user-search');
            const results = document.getElementById('search-results');

            input.addEventListener('input', function () {
                const keyword = input.value.trim();

                if (keyword.length < 2) {
                    results.innerHTML = '';
                    return;
                }

                fetch('{{ route('users.search') }}?keyword=' + encodeURIComponent(keyword))
                    .then(response => response.json())
                    .then(data => {
                        results.innerHTML = '';

                        if (data.length === 0) {
                            results.innerHTML = '<li>Aucun utilisateur trouvé.</li>';
                            return;
                        }

                        data.forEach(user => {
                            const li = document.createElement('li');
                            li.innerHTML = `
                        ${user.name} (${user.email})
                        <button class="invite-btn" data-email="${user.email}">Inviter</button>
                    `;
                            results.appendChild(li);
                        });

                        document.querySelectorAll('.invite-btn').forEach(btn => {
                            btn.addEventListener('click', function () {
                                const email = this.getAttribute('data-email');
                                inviteUser(email);
                            });
                        });
                    })
                    .catch(err => {
                        console.error('Erreur lors de la recherche :', err);
                        results.innerHTML = '<li>Erreur de recherche.</li>';
                    });
            });

            function inviteUser(email) {
                fetch('{{ route('projects.invite') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        project_id: {{ $project->id }}
                    })
                })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(({ status, body }) => {
                        if (status === 200) {
                            alert(body.message);
                        } else {
                            alert(body.message || 'Erreur lors de l\'invitation');
                        }
                    })
                    .catch(err => {
                        console.error('Erreur invitation :', err);
                        alert('Erreur lors de l\'invitation.');
                    });
            }
        });

    </script>
</x-app-layout>
