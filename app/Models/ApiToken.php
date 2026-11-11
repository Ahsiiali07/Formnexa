<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'token',
        'last_used_at',
        'expires_at',
    ];

    protected $hidden = [
        'token',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Issue a new hashed API Bearer token for the given user.
     *
     * @return array{apiToken: self, plainTextToken: string}
     */
    public static function issueFor(User $user, string $name = 'mobile', ?DateTimeInterface $expiresAt = null): array
    {
        $secret = Str::random(40);
        $apiToken = self::create([
            'user_id' => $user->id,
            'name' => mb_substr(trim($name) ?: 'mobile', 0, 100),
            'token' => hash('sha256', $secret),
            'expires_at' => $expiresAt,
        ]);

        return [
            'apiToken' => $apiToken,
            'plainTextToken' => $apiToken->id . '|' . $secret,
        ];
    }

    /**
     * Resolve a plain Bearer token ("{id}|{secret}" or raw secret) to a valid, unexpired ApiToken.
     */
    public static function findValid(string $plainTextToken): ?self
    {
        $plainTextToken = trim($plainTextToken);
        if ($plainTextToken === '') {
            return null;
        }

        if (str_contains($plainTextToken, '|')) {
            [$id, $secret] = explode('|', $plainTextToken, 2);
            if (!ctype_digit($id) || $secret === '') {
                return null;
            }
            $record = self::with('user')->find((int) $id);
            if (!$record || !hash_equals($record->token, hash('sha256', $secret))) {
                return null;
            }
        } else {
            $record = self::with('user')->where('token', hash('sha256', $plainTextToken))->first();
            if (!$record) {
                return null;
            }
        }

        if ($record->expires_at !== null && $record->expires_at->isPast()) {
            return null;
        }

        return $record->user ? $record : null;
    }
}
