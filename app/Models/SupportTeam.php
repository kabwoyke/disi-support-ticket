<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class SupportTeam extends Authenticatable
{
    //

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'specialty',
        'available',
        'profile_picture',
        'max_ticket_capacity',
        'ticket_count',
    ];


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
            'password' => 'hashed',
        ];
    }

public function ticket_assignment(){
    return $this->hasMany(TicketAssignment::class , 'teamId');
}


}
