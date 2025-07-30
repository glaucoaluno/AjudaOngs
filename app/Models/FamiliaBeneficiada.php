<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model FamiliaBeneficiada - Representa uma família beneficiada no sistema
 * 
 * @property int $id ID único da família
 * @property string $nome_representante Nome do representante da família
 * @property string $cpf_responsavel CPF do responsável pela família (único)
 * @property string $telefone Telefone da família
 * @property string $endereco Endereço da família
 * @property \Carbon\Carbon $data_cadastro Data de cadastro da família
 * @property \Carbon\Carbon $created_at Data de criação do registro
 * @property \Carbon\Carbon $updated_at Data de última atualização
 */
class FamiliaBeneficiada extends Model
{
    use HasFactory;

    protected $table = 'familia_beneficiadas';

    /**
     * Atributos que podem ser preenchidos em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'nome_representante',
        'cpf_responsavel',
        'telefone',
        'endereco',
        'data_cadastro'
    ];

    /**
     * Conversões de tipos para os atributos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'data_cadastro' => 'datetime',
    ];

    /**
     * Relacionamento com as doações recebidas pela família
     * 
     * @return HasMany<DoacaoFamilia>
     */
    public function doacoesRecebidas(): HasMany
    {
        return $this->hasMany(DoacaoFamilia::class, 'familia_id_familia');
    }
}