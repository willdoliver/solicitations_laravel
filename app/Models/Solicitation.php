<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitation extends Model
{
    protected $table = 'solicitations';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'status',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}