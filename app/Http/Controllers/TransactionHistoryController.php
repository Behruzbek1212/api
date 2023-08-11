<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionHistoryResource;
use App\Models\Trafic;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\TransactionHistoryServices;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    use ApiResponse;
    /**
     * Display all jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request)
    {
        return $this->successPaginate(TransactionHistoryResource::collection(TransactionHistoryServices::getInstance()->list($request)));
    }

    /**
     * Create vacancy
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $user_id = _auth()->user()->id;
        $request->validate([
            'service_id' => ['integer', 'required'],
            'service_sum' => ['numeric', 'required'],
            'service_name' => ['string', 'required'],
            'started_at' => ['date', 'required'],
            'expire_at' => ['date', 'required'],
        ]);

        $total_amount = User::where('id', $user_id)->first()->balance ?? 0;
        $service_price = $request->service_sum;

        if (!empty($service_price) && $total_amount >= $service_price) {
            $balance = $total_amount - $service_price;
            User::where('id', $user_id)
                ->update([
                    'balance' => $balance
                ]);
            TransactionHistory::create([
                'user_id' => $user_id,
                'service_id' => $request->service_id,
                'service_sum' => $service_price ?? 0,
                'service_name' => $request->service_name,
                'started_at' => $request->started_at,
                'expire_at' => $request->expire_at,
                'key' => $request->key ?? null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'TransationHistory successfully created',
            ]);
        }
        return $this->errorResponse(__('message.balance'), 403);
    }

    /**
     * Destroy vacancy
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'slug' => ['string', 'required']
        ]);

        TransactionHistory::query()
            ->findOrFail($params['slug'])->delete();
        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }
}
