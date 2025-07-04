<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PromoCode extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'promo_codes';
  

     /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];
  

  

}
