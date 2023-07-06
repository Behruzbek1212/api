<?php

namespace App\Traits;

<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> 9dd938732b278c20f39123e23e16f1692d97e310
use Illuminate\Support\Facades\Lang;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait ApiLogActivity
{
    use LogsActivity;

    public $logLocale = 'uz';

<<<<<<< HEAD
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

=======
>>>>>>> 9dd938732b278c20f39123e23e16f1692d97e310
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept($this->getLogExcept())
            ->useLogName($this->getTable())
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
<<<<<<< HEAD
}
=======

    public function getDescriptionForEvent(string $eventName): string
{
     $user = _auth()->user()->role;
  
    switch ($eventName) {
        case 'created':
            return "$user" ;
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
>>>>>>> 9dd938732b278c20f39123e23e16f1692d97e310
