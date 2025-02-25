<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Http\Resources\SubscribeResource;
use App\Services\EmailService;
use App\Services\SubscribeService;

class SubscribeController extends Controller
{
    protected SubscribeService $subscribeService;
    protected EmailService $emailService;

    public function __construct(SubscribeService $subscribeService, EmailService $emailService)
    {
        $this->subscribeService = $subscribeService;
        $this->emailService = $emailService;
    }

    public function subscribeToLink(SubscribeRequest $request)
    {
        $data = $request->validated();

        $subscribers = $this->subscribeService->subscribeToLink($data);

        return new SubscribeResource($subscribers);
    }

    public function mailConfirmation(string $uuid)
    {
        $result = $this->emailService->confirmation($uuid);

        return response()->json([
            'result' => $result,
        ]);
    }
}
