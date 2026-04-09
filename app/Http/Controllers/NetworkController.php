<?php

namespace App\Http\Controllers;

use App\Models\NetworkRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function joinRequest(Request $request)
    {
        $request->validate([
            'wholesaler_id' => 'required|exists:users,id',
        ]);

        $retailer = auth()->user();

        // Check if already has a pending or active request
        $existing = NetworkRequest::where('retailer_id', $retailer->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active or pending network request.');
        }

        NetworkRequest::create([
            'retailer_id' => $retailer->id,
            'wholesaler_id' => $request->wholesaler_id,
            'status' => 'pending',
            'message' => 'Request to join distribution network'
        ]);

        Notification::create([
            'user_id' => $request->wholesaler_id,
            'type' => 'network_request',
            'title' => 'New Join Request',
            'message' => "Retailer {$retailer->name} wants to join your network."
        ]);

        return back()->with('success', 'Request sent successfully!');
    }
}
