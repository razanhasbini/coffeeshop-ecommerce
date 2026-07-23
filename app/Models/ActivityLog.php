<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'actor_id', 'action', 'target_type', 'target_id', 'description',
        'ip_address', 'user_agent', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public static function record(string $action, string $description, ?Model $target = null, array $metadata = []): self
    {
        $request = request();

        return self::create([
            'actor_id' => auth()->id(),
            'action' => $action,
            'target_type' => $target ? class_basename($target) : null,
            'target_id' => $target?->getKey(),
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => mb_substr((string) $request?->userAgent(), 0, 500),
            'metadata' => $metadata ?: null,
        ]);
    }
}
