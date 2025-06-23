<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Message;
use Illuminate\Http\Request;

class FetchMessageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Consultation $consultation)
    {
        return $consultation->messages()->with('user')->orderBy('created_at', 'asc')->get();
    }
}
