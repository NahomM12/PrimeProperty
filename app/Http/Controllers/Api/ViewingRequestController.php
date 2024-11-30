<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ViewingRequest;
use App\Models\ViewingTimeSlot;
use Illuminate\Http\Request;

class ViewingRequestController extends Controller
{
    /**
     * Customer creates a viewing request
     */
    public function requestViewing(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'property_id' => 'required|exists:properties,id',
            'message' => 'nullable|string'
        ]);

        $viewingRequest = ViewingRequest::create($validated);

        return response()->json([
            'message' => 'Viewing request created successfully',
            'viewing_request' => $viewingRequest
        ], 201);
    }

    /**
     * Manager suggests available time slots
     */
    public function suggestTimeSlots(Request $request, ViewingRequest $viewingRequest)
    {
        $validated = $request->validate([
            'time_slots' => 'required|array',
            'time_slots.*' => 'required|date_format:Y-m-d H:i:s|after:now'
        ]);

        foreach ($validated['time_slots'] as $timeSlot) {
            ViewingTimeSlot::create([
                'viewing_request_id' => $viewingRequest->id,
                'proposed_date_time' => $timeSlot
            ]);
        }

        $viewingRequest->update(['status' => 'time_suggested']);

        return response()->json([
            'message' => 'Time slots suggested successfully',
            'viewing_request' => $viewingRequest->load('timeSlots')
        ]);
    }

    /**
     * Customer selects a time slot
     */
    public function selectTimeSlot(Request $request, ViewingRequest $viewingRequest)
    {
        $validated = $request->validate([
            'time_slot_id' => 'required|exists:viewing_time_slots,id'
        ]);

        // Reset all time slots for this request
        $viewingRequest->timeSlots()->update(['is_selected' => false]);

        // Set the selected time slot
        ViewingTimeSlot::where('id', $validated['time_slot_id'])
            ->update(['is_selected' => true]);

        $viewingRequest->update(['status' => 'confirmed']);

        return response()->json([
            'message' => 'Time slot selected successfully',
            'viewing_request' => $viewingRequest->load('timeSlots')
        ]);
    }

    /**
     * Get pending viewing requests (for manager dashboard)
     */
    public function getPendingRequests()
    {
        $pendingRequests = ViewingRequest::with(['customer', 'property'])
            ->where('status', 'pending')
            ->get();

        return response()->json($pendingRequests);
    }

    /**
     * Get customer's viewing requests with time slots
     */
    public function getCustomerRequests($customerId)
    {
        $requests = ViewingRequest::with(['timeSlots', 'property'])
            ->where('customer_id', $customerId)
            ->get();

        return response()->json($requests);
    }
}