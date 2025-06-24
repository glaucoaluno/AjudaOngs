<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'unidade',
        'validade',
        'descricao',
        'doacao_id_doacao',
        'data'
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function doacao()
    {
        return $this->belongsTo(DoacaoDoador::class, 'doacao_id_doacao', 'id_doacao');
    }

    public function doacoesFamilias()
    {
        return $this->hasMany(DoacaoFamilia::class, 'produtos_id');
    }
}
