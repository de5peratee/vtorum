@extends('layouts.app')

@section('title', $note->title)

@section('content')
    <div class="container">
        <h2>{{ $note->title }}</h2>
        <p>{{ $note->content }}</p>

        <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-warning">Редактировать</a>

        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>
@endsection
