<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($reservationId)
    {
        $reservation = Reservation::with(['client', 'user', 'service'])->findOrFail($reservationId);

        $user = auth()->user();
        if ($user->id !== $reservation->client_id && $user->id !== $reservation->user_id && $user->role !== 'admin') {
            return back()->with('error', 'Acces refuse.');
        }

        $messages = Message::where('reservation_id', $reservationId)
            ->with('sender')->orderBy('created_at')->get();

        Message::where('reservation_id', $reservationId)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('chat.show', compact('reservation', 'messages'));
    }

    public function send(Request $request, $reservationId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $reservation = Reservation::findOrFail($reservationId);
        $user = auth()->user();

        if ($user->id !== $reservation->client_id && $user->id !== $reservation->user_id) {
            return response()->json(['error' => 'Acces refuse.'], 403);
        }

        $msg = Message::create([
            'reservation_id' => $reservationId,
            'sender_id' => $user->id,
            'message' => $request->message,
            'created_at' => now(),
        ]);

        return response()->json(['id' => $msg->id, 'message' => $msg->message, 'sender_id' => $msg->sender_id, 'created_at' => $msg->created_at->format('H:i')]);
    }

    public function fetch($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $user = auth()->user();

        if ($user->id !== $reservation->client_id && $user->id !== $reservation->user_id && $user->role !== 'admin') {
            return response()->json(['error' => 'Acces refuse.'], 403);
        }

        $messages = Message::where('reservation_id', $reservationId)
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'message' => $m->message,
                    'sender_id' => $m->sender_id,
                    'sender_name' => $m->sender->name,
                    'time' => $m->created_at->format('H:i'),
                ];
            });

        return response()->json($messages);
    }
}
