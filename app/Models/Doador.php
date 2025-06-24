<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doador extends Model
{
    use HasFactory;

    protected $table = 'doadores';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'data_cadastro'
    ];

    protected $casts = [
        'data_cadastro' => 'datetime'
    ];

    public function doacoes()
    {
        return $this->hasMany(DoacaoDoador::class, 'id_doador');
    }
}
