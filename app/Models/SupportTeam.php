<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTeam extends Model
{
    //

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'specialty',
        'available',
        'profile_picture',
        'max_ticket_capacity',
        'ticket_count'
    ];

public function ticket_assignment(){
    return $this->hasMany(TicketAssignment::class , 'teamId');
}
}
