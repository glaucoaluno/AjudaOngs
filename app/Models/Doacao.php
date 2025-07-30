<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Doacao - Representa uma doação (modelo alternativo)
 * 
 * @property int $id ID único da doação
 * @property int $familia_id_familia ID da família beneficiada
 * @property int $produtos_id ID do produto doado
 * @property int $quantidade Quantidade do produto doado
 * @property \Carbon\Carbon $data Data da doação
 * @property \Carbon\Carbon $created_at Data de criação do registro
 * @property \Carbon\Carbon $updated_at Data de última atualização
 */
class Doacao extends Model
{
    use HasFactory;

    protected $table = 'doacoes';

    /**
     * Atributos que podem ser preenchidos em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'familia_id_familia',
        'produtos_id',
        'quantidade',
        'data'
    ];

    /**
     * Conversões de tipos para os atributos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'date',
    ];

    /**
     * Relacionamento com a família beneficiada
     * 
     * @return BelongsTo<FamiliaBeneficiada>
     */
    public function familia(): BelongsTo
    {
        return $this->belongsTo(FamiliaBeneficiada::class, 'familia_id_familia');
    }

    /**
     * Relacionamento com o produto doado
     * 
     * @return BelongsTo<Produto>
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produtos_id');
    }
}
