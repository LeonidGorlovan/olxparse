<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Http\Resources\SubscribeResource;
use App\Models\Link;
use App\Models\Subscribers;

class SubscribeController extends Controller
{
    public function __invoke(SubscribeRequest $request)
    {
        $data = $request->validated();

        $subscribers = Subscribers::firstOrCreate([
                'email' => $data['email'],
            ]);

        $link = Link::firstOrCreate([
                'link' => $data['link'],
            ]);

        $subscribers->links()->syncWithoutDetaching([$link->id]);
        $subscribers->load('links');

        return new SubscribeResource($subscribers);
    }
}
