<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoacaoDoador extends Model
{
    use HasFactory;

    protected $table = 'doacoes_doadores';
    protected $primaryKey = 'id_doacao';

    protected $fillable = [
        'data_doacao',
        'data_entrada',
        'data_entrega',
        'id_doador'
    ];

    protected $casts = [
        'data_doacao' => 'date',
        'data_entrada' => 'datetime',
        'data_entrega' => 'datetime',
    ];

    public function doador()
    {
        return $this->belongsTo(Doador::class, 'id_doador');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'doacao_id_doacao', 'id_doacao');
    }
}