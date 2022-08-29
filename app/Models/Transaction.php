<?php

namespace App\Models;

use App\Services\DataFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|int $state
 */
class Transaction extends Model
{
    use SoftDeletes;

    const TIMEOUT = 43200000;
    const RETURN_URL = 'https://jobo.uz/';

    const STATE_CREATED = 1;
    const STATE_COMPLETED = 2;
    const STATE_CANCELLED = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED = 3;
    const REASON_CANCELLED_BY_TIMEOUT = 4;
    const REASON_FUND_RETURNED = 5;
    const REASON_UNKNOWN = 10;

    const CURRENCY_CODE_UZS = 860;
    const CURRENCY_CODE_RUB = 643;
    const CURRENCY_CODE_USD = 840;

    protected $fillable = [
        'payment_system', 'currency_code',
        'state', 'system_transaction_id',
        'comment', 'amount', 'updated_time',
        'detail', 'transactionable_type',
        'transactionable_id',
    ];

    protected $dates = [ 'deleted_at' ];

    protected $casts = [ 'detail' => 'json' ];

    /**
     * Cancel a transaction
     *
     * @param string $reason
     * @return void
     */
    public function cancel(string $reason): void
    {
        $this->updated_time = DataFormat::timestamp(true);

        if ($this->state == self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        } else {
            // Scenario: CreateTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED;
        }

        $this->comment = $reason;
        $detail = $this->detail;
        $detail['cancel_time'] = $this->updated_time;
        $this->detail = $detail;

        $this->update();
    }

    /**
     * Check if transaction is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->state == self::STATE_CREATED &&
            DataFormat::datetime2timestamp($this->updated_time) - time() > self::TIMEOUT;
    }
}
