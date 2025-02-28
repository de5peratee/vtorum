<?php

use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\NoteController;

Route::get('/', [NoteController::class, 'index'])->name('home');

Route::get('/ai', [NoteController::class, 'index'])->name('ai.chat');

Route::post('/chat', AIController::class)->name('chat');

Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::get('/notes/{id}', [NoteController::class, 'show'])->name('notes.show');
Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');
Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');

Route::get('/records', [RecordController::class, 'index'])->name('records.index');
Route::get('/records/create', [RecordController::class, 'create'])->name('records.create');
Route::get('/records/{records}', [RecordController::class, 'show'])->name('records.show');
Route::post('/records', [RecordController::class, 'store'])->name('records.store');
Route::get('/records/{record}/edit', [RecordController::class, 'edit'])->name('records.edit');
Route::delete('/records/{record}', [RecordController::class, 'destroy'])->name('records.destroy');
Route::put('/records/{record}', [RecordController::class, 'update'])->name('records.update');


Route::post('/update-record-order', [RecordController::class, 'updateOrder']);


Route::post('/records/{records}/notes', [NoteController::class, 'store'])->name('notes.store');





//Route::get('/about', function () {
//    return view('about');
//})->name('about');
//
//Route::get('/contact', function () {
//    return view('contact');
//})->name('contact');


