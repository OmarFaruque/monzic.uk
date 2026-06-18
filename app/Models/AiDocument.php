<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'paddle_checkout_id',
        'title',
        'prompt',
        'content',
        'pdf_path',
        'image_path',
        'amount',
        'currency',
        'uuid',
        'status',
        'output_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relate AiDocument to User using email.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
