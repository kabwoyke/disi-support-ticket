<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //

    protected $fillable = [
        'question_id',
        'answer_text',
        'created_by',
        'status',
        'attachment'
    ];


    public function question(){
        return $this->belongsTo(Question::class , 'question_id');
    }

    public function author(){
        $this->belongsTo(SolveUser::class , "created_by");
    }
}
