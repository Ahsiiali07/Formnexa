<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Resume extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'template_style',
        'accent_color',
        'font_family',
        'photo_data',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Resume $resume) {
            if (empty($resume->uuid)) {
                $resume->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
