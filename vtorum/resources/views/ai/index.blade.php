@extends('layouts.app')

@section('title', 'Мои заметки')

@section('content')
    <div class="container">
        <h2>Мои заметки</h2>

        <a href="{{ route('notes.create') }}" class="btn btn-primary">Добавить заметку</a>

        <ul class="notes-list">
            @foreach($notes as $note)
                <li>
                    <a href="{{ route('notes.show', $note->id) }}">{{ $note->title }}</a>
                    <form action="{{ route('notes.destroy', $note->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
