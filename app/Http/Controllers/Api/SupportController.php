<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketReplyResource;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    function index(Request $request)
    {
        $user = $request->user;

        $tickets = Ticket::where('user_id', $user->id)->get();

        $tickets = TicketResource::collection($tickets);

        return $this->sendResponse($tickets, "Suport Tickets");
    }

    function store(TicketRequest $request)
    {

        $user = $request->user;

        $image = null;

        if ($request->hasFile('file')) {
            $image = uploadFile($request->file('file'), "support", "do_spaces");
        }

        $ticket = Ticket::create([
            'user_id'   => $user->id,
            'subject'   => $request->subject,
            'content'   => $request->content,
            'status'    => 'open',
            'file_url'  => $image
        ]);

        $ticket = new TicketResource($ticket);

        return $this->sendResponse($ticket, "Ticket created successfully.", 201);
    }

    function show(Ticket $ticket)
    {
        $data['ticket'] = new TicketResource($ticket);
        $data['replies'] = TicketReplyResource::collection($ticket->replies);

        return $this->sendResponse($data, "", 200);
    }

    function reply(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'message'  => 'required',
            'file'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user;

        $image = null;

        if ($request->hasFile('file')) {
            $image = uploadFile($request->file('file'), "support", "do_spaces");
        }

        $reply = TicketReply::create([
            'user_id'       => $user->id,
            'ticket_id'     => $ticket->id,
            'reply'         => $request->message,
            'file_url'      => $image
        ]);

        $reply = new TicketReplyResource($reply);

        $ticket->update([
            'status' => 'open'
        ]);

        return $this->sendResponse($reply, "", 200);
    }
}
