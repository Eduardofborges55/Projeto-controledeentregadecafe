<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GerenciamentoDeCompra extends Model
{
    
    use SoftDeletes;

    protected $fillable = ['id', 'user_id', 'purchase_time', 'amount_Cafe', 'amount_Filtro'];
    protected $table = 'gerenciamentodecompra';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function Compra(): BelongsTo
{
    return $this->belongsTo(GerenciamentoDeCompra::class);
}
    public $timestamps = true;
}
