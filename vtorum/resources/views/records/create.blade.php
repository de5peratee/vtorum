@extends('layouts.app')

@section('content')
    @vite(['resources/css/record.css'])
    @vite(['resources/js/tags.js'])
    <h1>Создать запись</h1>
    <form action="{{ route('records.store') }}" method="POST">
        @csrf

        <div class="inline-fields">
            <div class="inline-field">
                <label for="title">Название:</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="inline-field">
                <label for="status">Статус:</label>
                <select name="status">
                    <option value="новый" {{ old('status') == 'новый' ? 'selected' : '' }}>Новый</option>
                    <option value="в работе" {{ old('status') == 'в работе' ? 'selected' : '' }}>В работе</option>
                    <option value="завершено" {{ old('status') == 'завершено' ? 'selected' : '' }}>Завершено</option>
                </select>
                @error('status') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="inline-fields">
            <div class="inline-field">
                <label>Подписчики:</label>
                <div class="tag-input-container">
                    <input type="text" id="subscribers-input" placeholder="Введите подписчика и нажмите Enter">
                    <div id="subscribers-list"></div>
                    <input type="hidden" name="subscribers" value="{{ old('subscribers') }}">
                </div>
                @error('subscribers') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="inline-field">
                <label>Теги:</label>
                <div class="tag-input-container">
                    <input type="text" id="tags-input" placeholder="Введите тег и нажмите Enter">
                    <div id="tags-list"></div>
                    <input type="hidden" name="tags" value="{{ old('tags') }}">
                </div>
                @error('tags') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="inline-fields">
            <div class="inline-field">
                <label>Срок:</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}">
                @error('deadline') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="inline-field">
                <label>Категория:</label>
                <input type="text" name="category" value="{{ old('category') }}">
                @error('category') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="inline-field">
            <label>Вложения:</label>
            <div class="tag-input-container">
                <input type="text" id="attachments-input" placeholder="Введите ссылку и нажмите Enter">
                <div id="attachments-list"></div>
                <input type="hidden" name="attachments" value="{{ old('attachments') }}">
            </div>
            @error('attachments') <span class="error">{{ $message }}</span> @enderror
        </div>
        <br>

        <label>Канбан:</label>
        <textarea name="kanban">{{ old('kanban') }}</textarea>
        @error('kanban') <span class="error">{{ $message }}</span> @enderror

        <label>Связи:</label>
        <div class="tag-input-container">
            <input type="text" id="relations-input" placeholder="Введите связь и нажмите Enter">
            <div id="relations-list"></div>
            <input type="hidden" name="relations" value="{{ old('relations') }}">
        </div>
        @error('relations') <span class="error">{{ $message }}</span> @enderror

        <br>
        <button type="submit" class="create-button" >
            <img src="{{ asset('icon/Add_Plus_Circle.svg') }}" alt="logo">
            Создать
        </button>

    </form>
@endsection
