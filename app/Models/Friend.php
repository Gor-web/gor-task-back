<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;
    protected $table = 'requests';
    protected $fillable = [
        'my_id',
        'friend_id',
        'accepted_id',
    ];

    public function user() {
       return $this->hasMany(User::class,'id','my_id');
    }
    public function friendInfo() {
        return $this->hasMany(User::class,'id','accepted_id');
    }

    public function acceptedToMe() {
        return $this->hasMany(User::class,'id','friend_id');
    }

}
