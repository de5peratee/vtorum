@extends('layouts.app')

@section('content')
    <h1>Редактировать заметку</h1>

    <form action="{{ route('notes.update', [$record->id, $note->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="content" required>{{ old('content', $note->content) }}</textarea>
        <button type="submit">Обновить заметку</button>
    </form>
@endsection
