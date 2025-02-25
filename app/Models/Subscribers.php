<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

/**
 * @property int id
 * @property string email
 * @property string email_verified_code
 * @property Carbon email_verified_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class Subscribers extends Model
{
    use Notifiable;

    protected $fillable = ['email', 'email_verified_code', 'email_verified_at'];

    public function links(): BelongsToMany
    {
        return $this->belongsToMany(Link::class, 'subscriber_link', 'subscribers_id', 'link_id');
    }

    public function scopeVerified($query)
    {
        return $query->whereNull('email_verified_code')->whereNotNull('email_verified_at');
    }
}
