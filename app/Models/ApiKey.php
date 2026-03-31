<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = ['name', 'key', 'is_active', 'expires_at'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public static function generate(string $name, ?int $daysUntilExpiry = null): self
    {
        $key = 'rk_'.Str::random(32);

        $apiKey = new self([
            'name' => $name,
            'key' => $key,
            'is_active' => true,
        ]);

        if ($daysUntilExpiry) {
            $apiKey->expires_at = now()->addDays($daysUntilExpiry);
        }

        $apiKey->save();

        return $apiKey;
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
