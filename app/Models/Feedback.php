<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    
    protected $fillable = ['user_id', 'rating', 'comment', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
                ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}