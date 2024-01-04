<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'message',
    ];
}
