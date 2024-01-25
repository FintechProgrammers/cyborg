<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class SupportController extends Controller
{
    function index()
    {
        $data['tickets'] = Ticket::latest()->get();

        return view('admin.support.index', $data);
    }

    function show(Ticket $ticket)
    {
        $data['ticket'] = $ticket;
        $data['replies'] = TicketReply::where('ticket_id', $ticket->id)->get();

        return view('admin.support.show', $data);
    }

    function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => ['required']
        ]);

        TicketReply::create([
            'user_id'   => $request->user()->id,
            'ticket_id' => $ticket->id,
            'reply'     => $request->message
        ]);

        $ticket->update([
            'status' => 'closed'
        ]);

        return back()->with('success', 'Ticket replied successfully.');
    }
}
