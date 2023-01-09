<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'slug';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'background',
        'button',
        'content',
        'role',
        'slug'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'blank',

        'title_uz',
        'title_ru',
        'title_en',

        'content_uz',
        'content_ru',
        'content_en',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'title',
        'content'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'background' => 'array',
        'button' => 'array',
        'blank' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get translated title
     *
     * @return Attribute
     */
    public function title(): Attribute
    {
        $locale = 'content_' . app()->getLocale();

        return Attribute::make(
            get: fn ($value, $attr) =>
                $attr[$locale]
        );
    }

    /**
     * Get translated content
     *
     * @return Attribute
     */
    public function content(): Attribute
    {
        $locale = 'content_' . app()->getLocale();

        return Attribute::get(
            fn ($value, $attr) => $attr[$locale]
        );
    }
}
