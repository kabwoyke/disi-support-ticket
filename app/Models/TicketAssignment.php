<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAssignment extends Model
{
    //

    protected $fillable = [
        'ticketId',
        'teamId'
    ];

    public function ticket(){
        return $this->belongsTo(Ticket::class , 'ticketId');
    }

    public function support_team(){
        return $this->belongsTo(SupportTeam::class , 'teamId');
    }
}
