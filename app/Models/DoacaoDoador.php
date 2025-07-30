<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model DoacaoDoador - Representa uma doação feita por um doador
 * 
 * @property int $id_doacao ID único da doação
 * @property \Carbon\Carbon $data_doacao Data da doação
 * @property \Carbon\Carbon $data_entrada Data de entrada da doação no sistema
 * @property \Carbon\Carbon|null $data_entrega Data de entrega da doação
 * @property int $id_doador ID do doador que fez a doação
 * @property \Carbon\Carbon $created_at Data de criação do registro
 * @property \Carbon\Carbon $updated_at Data de última atualização
 */
class DoacaoDoador extends Model
{
    use HasFactory;

    protected $table = 'doacoes_doadores';
    protected $primaryKey = 'id_doacao';

    /**
     * Atributos que podem ser preenchidos em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'data_doacao',
        'data_entrada',
        'data_entrega',
        'id_doador'
    ];

    /**
     * Conversões de tipos para os atributos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'data_doacao' => 'date',
        'data_entrada' => 'datetime',
        'data_entrega' => 'datetime',
    ];

    /**
     * Relacionamento com o doador que fez a doação
     * 
     * @return BelongsTo<Doador>
     */
    public function doador(): BelongsTo
    {
        return $this->belongsTo(Doador::class, 'id_doador');
    }

    /**
     * Relacionamento com os produtos da doação
     * 
     * @return HasMany<Produto>
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'doacao_id_doacao', 'id_doacao');
    }
}