<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Quote extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'quotes';
  

     /**
     * Use this insteald of fillable
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];
  

    public function intents(): HasMany
    {
        return $this->hasMany(Intent::class, 'quote_id', "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

  

}
