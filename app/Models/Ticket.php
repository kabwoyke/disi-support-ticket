<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    //

    protected $fillable = [
    'subject',
    'categoryId',
    'priority',
    'equipmentId',
    'departmentId',
    'description',
    'attachment_url',
];


public function category(): BelongsTo
{
    return $this->belongsTo(TicketCategory::class, 'categoryId');
}

public function equipment(): BelongsTo
{
    return $this->belongsTo(Equipment::class, 'equipmentId');
}

public function department(): BelongsTo
{
    return $this->belongsTo(Department::class, 'departmentId');
}
}
