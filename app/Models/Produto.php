<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Produto - Representa um produto doado
 * 
 * @property int $id ID único do produto
 * @property string $nome Nome do produto
 * @property int $unidade Quantidade de unidades do produto
 * @property string $validade Data de validade do produto
 * @property string|null $descricao Descrição adicional do produto
 * @property int $doacao_id_doacao ID da doação à qual o produto pertence
 * @property \Carbon\Carbon $data Data do produto
 * @property \Carbon\Carbon $created_at Data de criação do registro
 * @property \Carbon\Carbon $updated_at Data de última atualização
 */
class Produto extends Model
{
    use HasFactory;

    /**
     * Atributos que podem ser preenchidos em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'nome',
        'unidade',
        'validade',
        'descricao',
        'doacao_id_doacao',
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
     * Relacionamento com a doação à qual o produto pertence
     * 
     * @return BelongsTo<DoacaoDoador>
     */
    public function doacao(): BelongsTo
    {
        return $this->belongsTo(DoacaoDoador::class, 'doacao_id_doacao', 'id_doacao');
    }

    /**
     * Relacionamento com as doações para famílias que usam este produto
     * 
     * @return HasMany<DoacaoFamilia>
     */
    public function doacoesFamilias(): HasMany
    {
        return $this->hasMany(DoacaoFamilia::class, 'produtos_id');
    }
}
