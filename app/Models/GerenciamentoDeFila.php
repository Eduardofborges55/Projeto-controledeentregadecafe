<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GerenciamentoDeFila extends Model
{
    use HasFactory;

    protected $table = 'GerenciamentoDeFila';

    protected $fillable = [
        'user_id',
        'ativo',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fila(): BelongsTo
    {
        return $this->belongsTo(GerenciamentoDeFila::class);
    }

    public $timestamps = true;
}
