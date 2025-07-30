<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Doador - Representa um doador no sistema
 * 
 * @property int $id ID único do doador
 * @property string $nome Nome completo do doador
 * @property string $email Email do doador (único)
 * @property string $telefone Telefone do doador
 * @property string $endereco Endereço do doador
 * @property \Carbon\Carbon $data_cadastro Data de cadastro do doador
 * @property \Carbon\Carbon $created_at Data de criação do registro
 * @property \Carbon\Carbon $updated_at Data de última atualização
 */
class Doador extends Model
{
    use HasFactory;

    protected $table = 'doadores';

    /**
     * Atributos que podem ser preenchidos em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'nome',
        'email',
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
        'data_cadastro' => 'datetime'
    ];

    /**
     * Relacionamento com as doações feitas pelo doador
     * 
     * @return HasMany<DoacaoDoador>
     */
    public function doacoes(): HasMany
    {
        return $this->hasMany(DoacaoDoador::class, 'id_doador');
    }
}
