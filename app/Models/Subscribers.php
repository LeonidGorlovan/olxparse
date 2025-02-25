<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string email
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class Subscribers extends Model
{
    use Notifiable;

    protected $fillable = ['email'];

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class, 'subscriber_link', 'subscribers_id', 'link_id');
    }
}
