<x-app-layout>
    <h1>Edit Sprint for project: {{ $sprint->project->name }}</h1>

    <form method="POST" action="{{ route('sprints.update', $sprint) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Objective</label>
            <input type="text" id="name" name="name" value="{{ old('name', $sprint->name) }}" required class="form-control">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $sprint->start_date) }}" required class="form-control">
            @error('start_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $sprint->end_date) }}" required class="form-control">
            @error('end_date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Sprint</button>
    </form>
</x-app-layout>
