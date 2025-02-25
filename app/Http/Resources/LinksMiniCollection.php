<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @see \App\Models\Link */
class LinksMiniCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            $this->collection->map(function ($link) {
                return [
                    'id' => $link->id,
                    'link' => $link->link,
                ];
            }),
        ];
    }
}
