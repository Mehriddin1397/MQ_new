<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->allConversations()
            ->with(['user', 'artisan', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherUser = $conversation->getOtherUser(auth()->user());

        return view('chat.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function startChat(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->isArtisan()) {
            $conversation = Conversation::firstOrCreate(
                ['user_id' => $user->id, 'artisan_id' => $currentUser->id]
            );
        } else {
            $conversation = Conversation::firstOrCreate(
                ['user_id' => $currentUser->id, 'artisan_id' => $user->id]
            );
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $request->validate([
            'body' => 'required_without:attachment|nullable|string|max:5000',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $type = 'text';
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mime = $file->getMimeType();
            $attachmentPath = $file->store('chat', 'public');

            if (str_starts_with($mime, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $type = 'video';
            } else {
                $type = 'file';
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'body' => $request->body,
            'type' => $type,
            'attachment' => $attachmentPath,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Dispatch broadcasting event
        broadcast(new MessageSent($message))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'message' => $message->load('sender'),
            ]);
        }

        return back();
    }

    private function authorizeConversation(Conversation $conversation): void
    {
        $userId = auth()->id();
        if ($conversation->user_id !== $userId && $conversation->artisan_id !== $userId) {
            abort(403);
        }
    }
}
