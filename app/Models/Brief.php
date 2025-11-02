<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brief extends Model
{
    protected $casts = ['structured_output' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
}
