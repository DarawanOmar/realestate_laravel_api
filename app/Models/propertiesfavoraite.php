<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class propertiesfavoraite extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'likes'=> 'array',
        'comments'=> 'array'
    ];

    public function user(){
        return  $this->belongsTo(User::class);
    }

}
