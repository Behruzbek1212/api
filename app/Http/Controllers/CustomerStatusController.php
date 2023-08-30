<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CustomerStatusController extends Controller
{




    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|array',
            'status' => 'required|string'
        ]);
        $data = $request->get('name');
        $user = _auth()->user();

        $customer = $user->customer->customerStatus()->create([
            'name' => $data,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => "Successfully created "
        ]);
    }

    public function updatedCandidateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
            'chat_id' => 'required|integer'
        ]);

        $user = _auth()->user();
        $chat = $user->customer->chats()->where(function ($query) use ($request) {
            $query->where('id', '=', $request->chat_id);
        })->firstOrFail();

        $chat->update([
            'status' => $request->status
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Successfully updated'
        ]);
    }

    public function update(Request $request)
    {
        return "fdsfdsdfs";
    }

    public function destroy(Request $request)
    {
    }
}
