<?php

namespace App\Models;

//use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;
//    use ApiLogActivity;
    protected $fillable = ['user_id', 'candidate_id', 'body'];

     /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

        /**
     * The belongs to Relationship
     *
     * @var array
     */

    public function candidates():HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
