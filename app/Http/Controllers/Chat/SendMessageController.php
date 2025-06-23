<?php

namespace App\Http\Controllers\Chat;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendMessageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Consultation $consultation)
    {
        $message = Message::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'consultation_id' => $consultation->id
        ]);

        broadcast(new MessageSent($message, $consultation))->toOthers();

        return response()->json(['status' => 'Message Sent!']);
    }
}
