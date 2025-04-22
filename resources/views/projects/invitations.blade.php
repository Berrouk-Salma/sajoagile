<x-app-layout>
    <h1>Mes invitations aux projets</h1>

    @if ($invitations->isEmpty())
        <p>Aucune invitation pour le moment.</p>
    @else
        <table class="table-auto w-full mt-4">
            <thead>
            <tr>
                <th class="border px-4 py-2">Projet</th>
                <th class="border px-4 py-2">Statut</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($invitations as $invitation)
                <tr>
                    <td class="border px-4 py-2">{{ $invitation->project->name }}</td>
                    <td class="border px-4 py-2">
                        @if($invitation->status === 'pending')
                            <span class="text-yellow-500">En attente</span>
                        @elseif($invitation->status === 'accepted')
                            <span class="text-green-500">Acceptée</span>
                        @else
                            <span class="text-red-500">Refusée</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                        @if ($invitation->status === 'pending')
                            <form action="{{ route('projects.respond', $invitation->project_id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="btn btn-sm btn-success">Accepter</button>
                            </form>
                            <form action="{{ route('projects.respond', $invitation->project_id) }}" method="POST" class="inline ml-2">
                                @csrf
                                <input type="hidden" name="action" value="decline">
                                <button type="submit" class="btn btn-sm btn-danger">Refuser</button>
                            </form>
                        @else
                            <em>Répondu</em>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('dashboard') }}" class="btn mt-4">Retour au tableau de bord</a>
</x-app-layout>
