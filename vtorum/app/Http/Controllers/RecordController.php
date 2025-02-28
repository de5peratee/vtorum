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

        $subscribers = array_filter(explode(",", $request->input('subscribers', '')), 'trim');
        $tags = array_filter(explode(",", $request->input('tags', '')), 'trim');
        $attachments = array_filter(explode(",", $request->input('attachments', '')), 'trim');
        $relations = array_filter(explode(",", $request->input('relations', '')), 'trim');

        $record->update([
            'title' => $validated['title'],
            'subscribers' => implode(",", $subscribers),
            'status' => $validated['status'],
            'deadline' => $validated['deadline'],
            'tags' => implode(",", $tags),
            'category' => $validated['category'],
            'kanban' => $validated['kanban'],
            'attachments' => implode(",", $attachments),
            'relations' => implode(",", $relations),
        ]);

        // Получаем текущие заметки
        $currentNotes = $record->notes()->get()->keyBy('title'); // Ассоциативный массив title => объект Note

        foreach ($relations as $relation) {
            if (isset($currentNotes[$relation])) {
                // Если заметка уже существует — не трогаем её
                continue;
            }

            // Если заметки нет — создаем новую
            Note::create([
                'record_id' => $record->id,
                'title' => $relation,
            ]);
        }

        // Удаляем только те заметки, которых нет в новых данных
        foreach ($currentNotes as $title => $note) {
            if (!in_array($title, $relations)) {
                $note->delete();
            }
        }

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
