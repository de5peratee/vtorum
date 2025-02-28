@extends('layouts.app')

@section('content')
    @foreach($records as $record)
        <li class="note-item">
            <a href="{{ route('records.show', ['records' => $record->id]) }}">
                {{ $record->title }}
            </a>
        </li>
    @endforeach
@endsection