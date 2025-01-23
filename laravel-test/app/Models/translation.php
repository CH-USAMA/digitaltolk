<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\locale;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class translation extends Model
{
    use HasFactory;

    protected $fillable = ['locale_id', 'key_name', 'content', 'tags'];

    protected $casts = [
        'tags' => 'array'
    ];

    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}
