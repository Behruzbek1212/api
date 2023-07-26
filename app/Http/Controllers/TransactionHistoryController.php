<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Http\Resources\TransactionHistoryResource;
use App\Models\Job;
use App\Models\Resume;
use App\Models\Trafic;
use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Notifications\RespondMessageNotification;
use App\Services\JobServices;
use App\Services\TransactionHistoryServices;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
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
            'trafic_id' => ['numeric', 'required'],
        ]);

        $total_amount = User::where('id', $user_id)->first()->balance ?? 0;
        $trafic_price =  Trafic::where('id', $request->trafic_id)->firstOrFail()->price;
        if (!empty($trafic_price) && $total_amount > $trafic_price) {
            $balance = $total_amount - $trafic_price;
            User::where('id', $user_id)
                ->update([
                    'balance' => $balance
                ]);
            TransactionHistory::create([
                'user_id' => $user_id,
                'trafic_id' => $request->trafic_id,
                'key' => $request->key ?? null,
                'amount' => $trafic_price ?? 0,
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
