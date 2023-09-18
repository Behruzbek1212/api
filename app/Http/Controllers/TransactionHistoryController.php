<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionHistoryResource;
use App\Models\Trafic;
use App\Models\TraficPrice;
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
            'service_trafic_price_id' => ['integer', 'required'],
            'started_at' => ['date', 'nullable'],
            'expire_at' => ['date', 'nullable'],
        ]);

        // Trafic::where('id', $request->service_id)->firstOrFail()->update(['trafic_price_id' => $request->service_trafic_price_id]);

        $trafic_price = TraficPrice::where('id', $request->service_trafic_price_id)->firstOrFail();
        $trafic = Trafic::where('id', $request->service_id)->firstOrFail();

        $total_amount = User::where('id', $user_id)->first()->balance ?? 0;
        $service_price = $trafic_price->price;
        $service_count = $trafic_price->count;
        if (empty($service_count) && $service_count <= 0) {
            return $this->errorResponse(__('message.service_count is null'), 403);
        }

        if (!empty($service_price) && $total_amount >= $service_price) {
            $balance = $total_amount - $service_price;
            User::where('id', $user_id)
                ->update([
                    'balance' => $balance
                ]);
            TransactionHistory::create([
                'user_id' => $user_id,
                'service_id' => $request->service_id,
                'service_count' => $service_count ?? 0,
                'service_sum' => $service_price ?? 0,
                'service_name' => $trafic->name,
                'started_at' => $request->started_at ?? null,
                'expire_at' => $request->expire_at ?? null,
                'key' => $trafic->key ?? null
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
