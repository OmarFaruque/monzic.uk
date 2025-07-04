<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BlackList extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'black_lists';
  

     /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];
  

  

}
