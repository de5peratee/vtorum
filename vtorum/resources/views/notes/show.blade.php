@extends('layouts.app')

@section('title', 'Детали заметки')

@section('content')
    <div class="note-details">
        <h1>{{ $note->title }}</h1>
        <p><strong>Запись:</strong> {{ $record->title }}</p>
        <p><strong>Дата создания:</strong> {{ $note->created_at }}</p>

        <h3>Редактировать заметку</h3>
        <form action="{{ route('notes.update', ['recordId' => $record->id, 'noteId' => $note->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Поле для редактирования названия -->
            <label for="title">Название заметки:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $note->title) }}"
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">

            <!-- Поле для редактирования содержимого -->
            <label for="content">Содержимое заметки:</label>
            <textarea name="content" id="content" rows="4" placeholder="Редактировать заметку"
                      style="width: 100%; height: 100px; min-height: 400px; max-height: 1000px; padding: 10px; border: 1px solid #ccc;
                             border-radius: 5px; font-size: 16px; line-height: 1.5; box-sizing: border-box; resize: vertical; overflow-y: auto;">
                {{ old('content', $note->content) }}
            </textarea>

            <button type="submit" class="create-button" style="margin-top: 5px">
                <img src="{{ asset('icon/ok-svgrepo-com.svg') }}" alt="logo">
                Сохранить изменения
            </button>

{{--            <button type="submit">Сохранить изменения</button>--}}
        </form>

        <form action="{{ route('notes.destroy', ['recordId' => $record->id, 'noteId' => $note->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="create-button" id='delete' style="margin-top: 5px">
                <img src="{{ asset('icon/white-trash-xmark-svgrepo-com.svg') }}" alt="logo">
                Удалить заметку
            </button>

        </form>
    </div>

    <script>
        window.onload = function () {
            var element = document.getElementById("delete");
            if (element) {
                element.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        }
    </script>

@endsection
