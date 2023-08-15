<?php

namespace App\Traits;

use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

trait ApiLogActivity
{
    use LogsActivity;

    public $logLocale = 'uz';

    // public function getDescriptionForEvent(string $eventName): string
    // {
    //     $message = 'log_message.' . $this->getTable() . '.' . $eventName;
    //     $messageAttributes = 'message_attributes.' . $this->getTable();

    //     if (!(Lang::has($message, $this->logLocale) && Lang::has($messageAttributes, $this->logLocale))) {
    //         return ('log_message.default.' . $eventName, [
    //             'first_attribute' => $this->id,
    //             'second_attribute' => null
    //         ], $this->logLocale);
    //     }

    //     $attribute = [];

    //     foreach (($messageAttributes, [], $this->logLocale) as $key => $value) {
    //         $attribute[$key] = $this->convertObject($value);
    //     }

    //     return __($message, $attribute, $this->logLocale);
    // }

    public function convertObject($array)
    {
        $model = $this;
        foreach ($array as $value) {
            $model = $model?->{$value};
        }
        return $model;
    }

    // modelning ba'zi attributlari o'zgarishiga e'tibor bermaslik
    public function getLogExcept()
    {
        $defaultExcept = ['updated_at', 'image'];
        if ($this->logExcept) {
            return array_merge($defaultExcept, $this->logExcept);
        }
        return $defaultExcept;
    }

    public static function bootApiLogActivity()
    {
        Activity::saving(function (Activity $activity) {
            $activity->properties = $activity->properties->put('user_data', [
                'user_id' =>  _user()->id ?? null,
                'user_role' =>  _user()->role ?? null,
                'user_subrole' =>  _user()->subrole ?? null,
                'user_fio' =>  _user()->fio ?? null,
            ]);
        });
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept($this->getLogExcept())
            ->useLogName($this->getTable())
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    public function getDescriptionForEvent(string $eventName): string
    {
        $user = _user()->role ?? 'admin';
        switch ($eventName) {
            case 'created':
                return  $user;
                break;
            case 'updated':
                return "$user";
                break;
            case 'deleted':
                return "$user";
                break;
            default:
                return '';
        }
    }
}
