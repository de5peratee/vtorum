<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Вторум')</title>
    @vite(['resources/css/style.css'])
    @vite(['resources/css/footer.css'])
    @vite(['resources/css/button.css'])
    @vite(['resources/js/transfer.js'])

</head>
<body>

{{--@include('partials.header')--}}

<main class="container">

    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </div>

        <div class="separator"></div>

        <h1>Проекты</h1>
        <a href="{{ route('records.create') }}" class="create-button">
            <img src="{{ asset('icon/Add_Plus_Circle.svg') }}" alt="logo">
            Создать запись
        </a>

        <h2>Записи</h2>
        <ul class="note-list" id="sortable">
            @foreach($records as $record)
                <li id="record-{{ $record->id }}" data-id="{{ $record->id }}" class="sortable-item">
            <span class="drag-handle">
                <img src="{{ asset('icon/drag-svgrepo-com.svg') }}" alt="drag" />
            </span>
                    <a href="{{ route('records.edit', $record) }}">{{ $record->title }}</a>
                    <form action="{{ route('records.destroy', $record) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="delete" type="submit">
                            <img src="{{ asset('icon/trash-xmark-svgrepo-com.svg') }}" />
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>


    </aside>


    <section class="main-content">
        @yield('content')
    </section>
</main>

{{--@include('partials.footer')--}}

@vite(['resources/js/app.js'])
</body>
</html>