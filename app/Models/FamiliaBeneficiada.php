<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamiliaBeneficiada extends Model
{
    use HasFactory;

    protected $table = 'familia_beneficiadas';

    protected $fillable = [
        'nome_representante',
        'cpf_responsavel',
        'telefone',
        'endereco',
        'data_cadastro'
    ];

    protected $casts = [
        'data_cadastro' => 'datetime',
    ];

    public function doacoesRecebidas()
    {
        return $this->hasMany(DoacaoFamilia::class, 'familia_id_familia');
    }
}