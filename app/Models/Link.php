<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string link
 * @property float price
 * @property string currency
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class Link extends Model
{
    protected $fillable = ['link', 'price', 'currency'];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscribers::class, 'subscriber_link', 'link_id', 'subscribers_id');
    }
}
