<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Setting extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'settings';
  

     /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];


    
  

}
