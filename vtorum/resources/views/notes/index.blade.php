@extends('layouts.app')

@section('content')
    <h1>Заметки для записи "{{ $record->title }}"</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="notes-list">
        @foreach($notes as $note)
            <div class="note">
                <p>{{ $note->content }}</p>
                <a href="{{ route('notes.edit', [$record->id, $note->id]) }}">Редактировать</a>
                <form action="{{ route('notes.destroy', [$record->id, $note->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить эту заметку?')">Удалить</button>
                </form>
            </div>
        @endforeach
    </div>

    <form action="{{ route('notes.store', $record->id) }}" method="POST">
        @csrf
        <textarea name="content" placeholder="Введите текст заметки" required></textarea>
        <button type="submit">Добавить заметку</button>
    </form>
@endsection
