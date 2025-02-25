<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Http\Resources\SubscribeResource;
use App\Jobs\SendVerifyCodeJob;
use App\Services\EmailService;
use App\Services\SubscribeService;
use Throwable;

class SubscribeController extends Controller
{
    protected SubscribeService $subscribeService;
    protected EmailService $emailService;

    public function __construct(SubscribeService $subscribeService, EmailService $emailService)
    {
        $this->subscribeService = $subscribeService;
        $this->emailService = $emailService;
    }

    /**
     * @throws Throwable
     */
    public function subscribeToLink(SubscribeRequest $request)
    {
        $data = $request->validated();

        $subscriber = $this->subscribeService->subscribeToLink($data);

        if (!empty($subscriber->email_verified_code)) {
            SendVerifyCodeJob::dispatch($subscriber, $data['link'], $subscriber->email_verified_code);
        }

        return new SubscribeResource($subscriber);
    }

    /**
     * @throws Throwable
     */
    public function mailConfirmation(string $uuid)
    {
        $result = $this->emailService->confirmation($uuid);

        return response()->json([
            'result' => $result,
        ]);
    }
}
