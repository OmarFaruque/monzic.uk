<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Entry extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'entries';
  

     /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];
  

  

}
