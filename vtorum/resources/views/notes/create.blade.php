@extends('layouts.app')

@section('title', 'Создать заметку')

@section('content')
    <div class="container">
        <h2>Создать новую заметку</h2>

        <form action="{{ route('notes.store', ['records' => $record->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Название:</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="content">Содержание:</label>
                <textarea name="content" id="content" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-success" style="">Создать</button>
        </form>
    </div>
@endsection
