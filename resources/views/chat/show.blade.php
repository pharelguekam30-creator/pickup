@extends('layouts.app')

@section('title', 'Discussion - Intervention #'.$reservation->id)

@section('content')
<div style="max-width:700px;margin:0 auto;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h1 style="font-size:1.3rem;font-weight:bold;color:#1e3a8a;">Discussion #{{ $reservation->id }}</h1>
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" style="color:#2563eb;text-decoration:none;font-size:.9rem;">&larr; Retour</a>
    </div>

    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1.5rem;margin-bottom:1rem;">
        <p style="color:#475569;font-size:.9rem;">
            <strong>Service :</strong> {{ $reservation->service->name ?? 'N/A' }} &mdash;
            <strong>Client :</strong> {{ $reservation->client->name }} &mdash;
            <strong>Vidangeur :</strong> {{ $reservation->user->name }}
        </p>
    </div>

    <div id="chatBox" style="background:#f8fafc;border-radius:1rem;box-shadow:0 2px 8px #00000011;padding:1rem;height:400px;overflow-y:auto;margin-bottom:1rem;display:flex;flex-direction:column;gap:.5rem;">
        @foreach($messages as $msg)
            <div style="max-width:80%;padding:.6rem 1rem;border-radius:1rem;align-self:{{ $msg->sender_id === auth()->id() ? 'flex-end;background:#3b82f6;color:#fff' : 'flex-start;background:#e2e8f0;color:#1e293b' }};">
                <div style="font-size:.7rem;opacity:.7;margin-bottom:2px;">{{ $msg->sender->name }}</div>
                <div>{{ $msg->message }}</div>
                <div style="font-size:.65rem;opacity:.6;text-align:right;margin-top:3px;">{{ $msg->created_at->format('H:i') }}</div>
            </div>
        @endforeach
    </div>

    <form id="chatForm" style="display:flex;gap:.5rem;">
        <input type="text" id="messageInput" placeholder="Votre message..." required
               style="flex:1;padding:.8rem 1rem;border:1px solid #e2e8f0;border-radius:10px;font-size:.95rem;">
        <button type="submit" style="padding:.8rem 1.5rem;background:#2563eb;color:#fff;border:none;border-radius:10px;font-weight:600;cursor:pointer;">Envoyer</button>
    </form>
</div>

<script>
const chatBox = document.getElementById('chatBox');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const reservationId = {{ $reservation->id }};
const userId = {{ auth()->id() }};
const originalTitle = document.title;
let blinkInterval = null;

function scrollToBottom() { chatBox.scrollTop = chatBox.scrollHeight; }

function playBeep() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 800;
        osc.type = 'sine';
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.3);
    } catch(e) {}
}

function notifyNewMessage() {
    playBeep();
    if (document.hidden && !blinkInterval) {
        blinkInterval = setInterval(function() {
            document.title = document.title === originalTitle ? 'Nouveau message!' : originalTitle;
        }, 1000);
    }
}

document.addEventListener('visibilitychange', function() {
    if (!document.hidden && blinkInterval) {
        clearInterval(blinkInterval);
        blinkInterval = null;
        document.title = originalTitle;
    }
});

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = messageInput.value.trim();
    if (!msg) return;
    fetch('/chat/' + reservationId + '/send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ message: msg })
    }).then(r => r.json()).then(data => {
        if (data.error) return;
        appendMessage(data.message, userId, data.created_at);
        if (data.id > lastId) lastId = data.id;
        messageInput.value = '';
    });
});

let lastId = {{ $messages->last()->id ?? 0 }};

function poll() {
    fetch('/chat/' + reservationId + '/messages')
        .then(r => r.json()).then(messages => {
            if (!Array.isArray(messages)) return;
            messages.forEach(m => {
                if (m.id > lastId) {
                    appendMessage(m.message, m.sender_id, m.time);
                    lastId = m.id;
                }
            });
        });
}

function appendMessage(text, senderId, time) {
    const div = document.createElement('div');
    div.style.cssText = 'max-width:80%;padding:.6rem 1rem;border-radius:1rem;align-self:' + (senderId === userId ? 'flex-end' : 'flex-start') + ';background:' + (senderId === userId ? '#3b82f6' : '#e2e8f0') + ';color:' + (senderId === userId ? '#fff' : '#1e293b');
    div.innerHTML = '<div style="font-size:.7rem;opacity:.7;margin-bottom:2px;">' + (senderId === userId ? 'Moi' : '') + '</div><div>' + text + '</div><div style="font-size:.65rem;opacity:.6;text-align:right;margin-top:3px;">' + time + '</div>';
    chatBox.appendChild(div);
    scrollToBottom();
    if (senderId !== userId) notifyNewMessage();
}

setInterval(poll, 2000);
scrollToBottom();
</script>
@endsection
