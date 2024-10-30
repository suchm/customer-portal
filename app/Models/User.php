<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'telephone',
        'tel_country_code',
        'address_1',
        'address_2',
        'county',
        'city',
        'country',
        'postcode',
        'profile_image',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class)
            ->withPivot(
                'start_date',
                'end_date')
            ->join(
                'statuses',
                'subscription_user.status_id',
                '=',
                'statuses.id')
            ->select(
                'subscriptions.*',
                'statuses.name as status_name',
                'statuses.code as status_code'
            );
    }

    public function avatar()
    {
        if ( $this->profile_image ) {
            return config('filesystems.default') === 'public'
                ? '/storage/profile_images/'.$this->profile_image
                : '/my-account/profile_image/'.$this->profile_image;

        } else {
            $name = $this->last_name ? $this->name . ' ' . $this->last_name : $this->name;
            return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
        }
    }

    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'participants')
            ->withPivot('last_read')
            ->withTimestamps()
            ->with(['messages' => function ($query) {
                // Get the most recent message with its user
                $query->latest()
                    ->limit(1)
                    ->with('user'); // Eager load the user of the latest message
            }])
            ->orderBy(function ($query) {
                // Subquery to order by the most recent message's created_at
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('messages.chat_id', 'chats.id')
                    ->latest()
                    ->limit(1);
            }, 'desc'); // Order by the latest message's created_at
    }
}
