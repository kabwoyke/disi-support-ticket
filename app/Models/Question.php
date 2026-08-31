<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //

    protected $connection = 'mysql_solves';

    protected $table = 'questions';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'created_by',
        'status',
        'views',
        'is_final',
        'attachment',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'views' => 'integer',
        'is_final' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(SolveUser::class, 'created_by');
    }
}
