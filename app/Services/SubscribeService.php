<?php

namespace App\Services;

use App\Models\Link;
use App\Models\Subscribers;
use DB;
use Illuminate\Support\Str;
use Log;
use Throwable;

class SubscribeService
{
    /**
     * @throws Throwable
     */
    public function subscribeToLink(array $postData): ?Subscribers
    {
        try {
            DB::beginTransaction();

            $subscriber = Subscribers::firstOrCreate([
                'email' => $postData['email'],
            ], [
                'email_verified_code' => Str::uuid()
            ]);

            $link = Link::firstOrCreate([
                'link' => $postData['link'],
            ]);

            $subscriber->links()->syncWithoutDetaching([$link->id]);
            $subscriber->load('links');

            DB::commit();

            return $subscriber;
        } catch (Throwable $th) {
            DB::rollBack();

            Log::error('Subscription failed: ' . $th->getMessage(), [
                'email' => $postData['email'] ?? null,
                'link' => $postData['link'] ?? null
            ]);

            return null;
        }
    }
}
