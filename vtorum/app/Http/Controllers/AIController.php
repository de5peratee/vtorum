<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Note;

class AIController extends Controller
{
    public function index()
    {
        return view('chat_ai.index');
    }

    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $apiKey = env('GEMINI_API_KEY');
            if (empty($apiKey)) {
                return response()->json(['error' => 'API-ключ не найден'], 500);
            }

            // Генерация персонализированного промта на основе заметок пользователя
            $userNotes = Note::pluck('content')->filter()->implode(' ');            Log::info("Данные: ", ['message' => $userNotes]);

            $prompt = "Ты - человек, обладающий следующими качествами и поступками: $userNotes. Отвечай естественно, как свойственно человеку. Ты не языковая модель, а человек, который был описан. Его имя, характер и поведение должно полностью повторяться. Рассказывай и объясняй по простому как человек, а не как машина, твоя цель - вести диалог, будто я это ты, а ты это я, ибо данные которые передаются, это мои данные или данные моих близких. Ты знаешь кто я и не должен задавать вопросов подобного формата, говори на неформальном языке, и только в нужных случаях используй такой подход. Веди себя естественно, лишнего не болтай, но и не умалчивай. Веди обычный человеческий диалог. Не надо постоянно задавать кучу вопросов и постоянн крутиться вокруг одного и того же.";

            $userMessage = $request->input('content');

            $response = Http::withHeaders([
                "Content-Type" => "application/json",
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey", [
                "contents" => [
                    ["parts" => [["text" => $prompt . "\n\nПользователь: " . $userMessage]]]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Ошибка API: ' . $response->body());
                return response()->json(['error' => 'Ошибка запроса к API'], 500);
            }
            Log::info("Отправляемый промт: ", ['prompt' => $prompt]);
            Log::info("Сообщение пользователя: ", ['message' => $userMessage]);

            $data = $response->json();
            $message = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Ошибка в ответе API';

            return response()->json(['response' => $message]);

        } catch (Throwable $e) {
            Log::error('Ошибка сервера: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка сервера'], 500);
        }
    }

    private function generateSummary($notes)
    {
        if (empty($notes)) {
            return "нет данных о характере, отвечай нейтрально.";
        }

        // Конкатенация всех заметок пользователя
        $text = implode(' ', $notes);

        // Список возможных качеств
        $qualities = [
            'доброта', 'ответственность', 'целеустремленность',
            'эмпатия', 'ум', 'честность', 'смелость'
        ];

        // Поиск ключевых качеств в заметках
        $foundQualities = [];
        foreach ($qualities as $quality) {
            if (str_contains(mb_strtolower($text), $quality)) {
                $foundQualities[] = $quality;
            }
        }

        return empty($foundQualities) ? "нет явных черт характера" : implode(', ', $foundQualities);
    }
}
