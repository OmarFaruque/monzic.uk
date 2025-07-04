<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{

    protected $table = 'tickets';
  
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'subject',
        'first_name',
        'last_name',
        'phone',
        'unread',
        'policy_number',
        'email',
        'token',
        'is_closed',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'ticket_id', 'ticket_id');
    }
}
