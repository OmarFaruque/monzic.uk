<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Intent extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'payment_intents';
  

     /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];


    
  

}
