<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Incident;
use App\Models\RescueRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * RescueRequestController
 * Handles /api/rescue-requests endpoints for the enthusiast dashboard.
 */
class RescueRequestController extends Controller
{
    /**
     * GET /api/rescue-requests
     * Returns all pending rescue requests.
     * Enthusiasts see all pending requests; users can filter to their own.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $user    = $request->user();

        // Find the expert record for this user (if any)
        $expert = Expert::where('user_id', $user->user_id)->first();

        $query = RescueRequest::with(['incident'])
            ->where('status', 'pending');

        if ($expert) {
            // Show requests assigned to this expert OR unassigned ones
            $query->where(function ($q) use ($expert) {
                $q->where('expert_id', $expert->id)->orWhereNull('expert_id');
            });
        }

        $requests = $query->latest()->paginate($perPage);

        return response()->json($requests->getCollection()->map(function ($req) {
            $incident = $req->incident;
            if (!$incident) return null;

            return [
                'id'            => 'REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT),
                'user_name'     => 'Anonymous',
                'location_name' => $incident->location_name ?? $incident->location,
                'lat'           => $incident->lat,
                'lng'           => $incident->lng,
                'distance_km'   => null, // Calculated on client side
                'reported_at'   => $incident->created_at
                    ? Carbon::parse($incident->created_at)->diffForHumans()
                    : null,
                'description'   => $incident->description,
                'image_url'     => $incident->image_path
                    ? asset('storage/' . $incident->image_path)
                    : null,
                'status'        => $req->status,
            ];
        })->filter()->values());
    }

    /**
     * POST /api/rescue-requests/{id}/accept
     * Accept a rescue request and return user contact info.
     */
    public function accept(Request $request, $id)
    {
        $rescueReq = RescueRequest::findOrFail($id);

        if ($rescueReq->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.',
            ], 409);
        }

        $rescueReq->update(['status' => 'accepted']);

        // Return reporter's contact info
        $incident = $rescueReq->incident;
        $reporter = $incident ? User::find($incident->user_id) : null;

        return response()->json([
            'success'      => true,
            'message'      => 'Rescue request accepted.',
            'contact_info' => $reporter ? [
                'name'  => $reporter->fname . ' ' . $reporter->lname,
                'phone' => $reporter->phone,
                'email' => $reporter->email,
            ] : null,
        ]);
    }

    /**
     * POST /api/rescue-requests/{id}/reject
     * Reject/pass a rescue request.
     */
    public function reject(Request $request, $id)
    {
        $rescueReq = RescueRequest::findOrFail($id);

        if ($rescueReq->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.',
            ], 409);
        }

        $rescueReq->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Rescue request rejected.',
        ]);
    }
}
