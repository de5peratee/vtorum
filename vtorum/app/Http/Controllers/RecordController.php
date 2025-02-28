<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index()
    {
        $records = Record::with('notes')->get();

        return view('records.index', compact('records'));
    }


    public function create()
    {
        return view('records.create');
    }


    public function store(Request $request)
    {
        // Валидация данных
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subscribers' => 'nullable|string',
            'status' => 'required|in:новый,в работе,завершено',
            'deadline' => 'nullable|date',
            'tags' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'kanban' => 'nullable|string',
            'attachments' => 'nullable|string',
            'relations' => 'nullable|string',
        ]);

        // Преобразование строки в массив
        $subscribers = $request->has('subscribers') ? explode(",", $request->input('subscribers')) : [];
        $tags = $request->has('tags') ? explode(",", $request->input('tags')) : [];
        $attachments = $request->has('attachments') ? explode(",", $request->input('attachments')) : [];
        $relations = $request->has('relations') ? explode(",", $request->input('relations')) : [];

        $subscribers = implode(",", $subscribers);
        $tags = implode(",", $tags);
        $attachments = implode(",", $attachments);
        $relations = implode(",", $relations);

        $record = Record::create([
            'title' => $validated['title'],
            'subscribers' => $subscribers,
            'status' => $validated['status'],
            'deadline' => $validated['deadline'],
            'tags' => $tags,
            'category' => $validated['category'],
            'kanban' => $validated['kanban'],
            'attachments' => $attachments,
            'relations' => $relations,
        ]);

        // Преобразуем строку обратно в массив перед добавлением заметок
        if (!empty($relations)) {
            $relationsArray = explode(",", $relations); // Преобразуем строку в массив
            foreach ($relationsArray as $relation) {
                Note::create([
                    'record_id' => $record->id,  // Привязываем заметку к только что созданной записи
                    'title' => $relation,       // Название заметки — это значение из массива 'relations'
                ]);
            }
        }

        return redirect()->route('records.create')->with('success', 'Запись успешно создана');
    }




    public function edit(Record $record)
    {
        return view('records.edit', compact('record'));
    }

    public function update(Request $request, Record $record)
    {
        // Валидируем входящие данные
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subscribers' => 'nullable|string',
            'status' => 'required|string|max:50',
            'deadline' => 'nullable|date',
            'tags' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'kanban' => 'nullable|string',
            'attachments' => 'nullable|string',
            'relations' => 'nullable|string',
        ]);

        // Преобразуем входные данные в массивы, игнорируя пустые строки или строки с пробелами
        $subscribers = $request->has('subscribers') ? explode(",", $request->input('subscribers')) : [];
        $tags = $request->has('tags') ? explode(",", $request->input('tags')) : [];
        $attachments = $request->has('attachments') ? explode(",", $request->input('attachments')) : [];
        $relations = $request->has('relations') ? explode(",", $request->input('relations')) : [];

        // Убираем пустые строки или строки, состоящие только из пробелов
        $subscribers = array_filter($subscribers, fn($value) => trim($value) !== '');
        $tags = array_filter($tags, fn($value) => trim($value) !== '');
        $attachments = array_filter($attachments, fn($value) => trim($value) !== '');
        $relations = array_filter($relations, fn($value) => trim($value) !== '');

        // Преобразуем массивы обратно в строки
        $subscribers = implode(",", $subscribers);
        $tags = implode(",", $tags);
        $attachments = implode(",", $attachments);
        $relationsString = implode(",", $relations);  // Строка, а не массив

        // Обновляем запись
        $record->update([
            'title' => $validated['title'],
            'subscribers' => $subscribers,
            'status' => $validated['status'],
            'deadline' => $validated['deadline'],
            'tags' => $tags,
            'category' => $validated['category'],
            'kanban' => $validated['kanban'],
            'attachments' => $attachments,
            'relations' => $relationsString,
        ]);

        // Получаем текущие заметки
        $currentNotes = $record->notes()->pluck('title')->toArray();

        // Преобразуем строки в массивы
        $relationsArray = explode(",", $relationsString); // Преобразуем строку обратно в массив

        // Находим заметки, которых нет в новых данных
        $notesToDelete = array_diff($currentNotes, $relationsArray);

        // Удаляем те заметки, которых больше нет в данных
        foreach ($notesToDelete as $noteTitle) {
            $noteToDelete = $record->notes()->where('title', $noteTitle)->first();
            if ($noteToDelete) {
                $noteToDelete->delete(); // Удаляем всю заметку
            }
        }

        // Создаем новые заметки, если они отсутствуют
        foreach ($relationsArray as $relation) {
            // Если заметки с таким названием еще нет, создаем ее
            if (!empty(trim($relation)) && !in_array($relation, $currentNotes)) {
                Note::create([
                    'record_id' => $record->id,  // Привязываем заметку к записи
                    'title' => $relation,         // Название заметки — это значение из массива 'relations'
                ]);
            }
        }

        // Перенаправляем на страницу создания записи с сообщением об успехе
        return redirect()->route('records.create')->with('success', 'Запись обновлена');
    }


    public function destroy($id)
    {
        $record = Record::findOrFail($id);

        $record->notes()->delete();

        $record->delete();

        return redirect()->route('records.create', ['recordId' => $record->id])
            ->with('success', 'Запись успешно удалена');
    }



    public function show($id)
    {
        $record = Record::findOrFail($id);
        return view('records.show', compact('record'));
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order'); // Это массив с порядком элементов

        foreach ($order as $index => $recordId) {
            $record = Record::find($recordId);
            if ($record) {
                $record->order = $index;
                $record->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
