<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Record;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index($recordId)
    {
        $record = Record::findOrFail($recordId);
        $notes = $record->notes; // Получаем заметки для этой записи

        return view('notes.index', compact('record', 'notes'));
    }

    // В NoteController
    public function show($recordId, $noteId)
    {
        $record = Record::findOrFail($recordId);

        $note = $record->notes()->findOrFail($noteId);

        return view('notes.show', compact('record', 'note'));
    }



    // Создание новой заметки
    public function store(Request $request, $recordId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $record = Record::findOrFail($recordId);
        $record->notes()->create([
            'content' => $validated['content']
        ]);

        return redirect()->route('notes.index', $recordId)
            ->with('success', 'Заметка успешно добавлена');
    }

    // Отображение формы для редактирования заметки
    public function edit($recordId, $noteId)
    {
        $record = Record::findOrFail($recordId);
        $note = Note::where('record_id', $recordId)->findOrFail($noteId);

        return view('notes.edit', compact('record', 'note'));
    }

    public function update(Request $request, $recordId, $noteId)
    {
        // Валидация входных данных
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string', // Разрешаем пустой контент
        ]);

        // Находим заметку по ID
        $note = Note::where('record_id', $recordId)->findOrFail($noteId);

        // Обновляем название и содержимое заметки
        $note->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        // Перенаправляем на страницу с деталями заметки с сообщением об успехе
        return redirect()->route('notes.show', ['recordId' => $recordId, 'noteId' => $noteId])
            ->with('success', 'Заметка успешно обновлена');
    }



    // В NoteController
    public function destroy($recordId, $noteId)
    {
        // Находим запись и заметку
        $record = Record::findOrFail($recordId);
        $note = $record->notes()->findOrFail($noteId);

        $note->delete();

        return redirect()->route('records.show', $record->id)
            ->with('success', 'Заметка удалена');
    }

}


