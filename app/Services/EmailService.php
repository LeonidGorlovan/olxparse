<?php

namespace App\Services;

use App\Models\Subscribers;
use Log;
use Throwable;

class EmailService
{
    /**
     * @throws Throwable
     */
    public function confirmation(string $uuid): bool
    {
        if (empty($uuid)) {
            Log::warning('Empty UUID provided for email confirmation');
            return false;
        }

        try {
            $subscribers = Subscribers::query()
                ->where('email_verified_code', $uuid)
                ->where('created_at', '>', now()->subHours(24))
                ->first();

            $subscribers->update([
                'email_verified_code' => null,
                'email_verified_at' => now(),
            ]);

            return true;
        } catch (Throwable $th) {
            Log::error('Email confirmation failed: ' . $th->getMessage(), [
                'uuid' => $uuid,
            ]);

            return false;
        }
    }
}
