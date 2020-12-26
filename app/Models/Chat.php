<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    use HasFactory;
    protected $fillable = [
        'my_id',
        'friend_id',
        'message',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
    public function sender()
    {
        return $this->belongsTo('App\Models\User',"friend_id","id");
    }
    public function getter()
    {
        return $this->belongsTo('App\Models\User',"my_id","id");
    }
}
