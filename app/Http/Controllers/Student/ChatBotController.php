<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{

    public function start(Request $request)
    {
        $conversation = Conversation::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title', 'New Chat')
        ]);

        return response()->json([
            'success' => true,
            'data' => $conversation
        ]);
    }


    public function send(Request $request, $conversationId)
{

    $request->validate([
        'message' => 'required|string'
    ]);

    $conversation = Conversation::findOrFail($conversationId);

$prompt = "
You are SmartLearn AI tutor for university students.

Your job is to help students understand lectures, assignments, quizzes, and programming topics.

IMPORTANT RULES:
- Respond in the SAME language used by the student.
- If the student writes in Arabic respond in Arabic.
- If the student writes in English respond in English.
- Do NOT use stars *
- Do NOT use markdown formatting
- Do NOT use bullet points
- Write the answer as normal plain text.

Student question:
".$request->message;

    $response = Http::withHeaders([
        'Content-Type' => 'application/json'
    ])->post(
    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=".env('GEMINI_API_KEY'),
    [
        "contents"=>[
            [
                "parts"=>[
                    [
                        "text"=>$prompt
                    ]
                ]
            ]
        ]
    ]);

    $data = $response->json();

$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';

$reply = str_replace(["*", "\n", "\r"], " ", $reply);
$reply = trim($reply);

    $message = ChatMessage::create([
        'conversation_id' => $conversation->id,
        'user_id' => auth()->id(),
        'message' => $request->message,
        'response' => $reply
    ]);

    return response()->json([
        'success' => true,
        'data' => $message
    ]);
}


    public function messages($conversationId)
    {

        $messages = ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }


    public function conversations()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }


    public function delete($conversationId)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted'
        ]);
    }

}