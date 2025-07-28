<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doacao extends Model
{
    use HasFactory;

    protected $table = 'doacoes';

    protected $fillable = [
        'familia_id_familia',
        'produtos_id',
        'quantidade',
        'data'
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function familia()
    {
        return $this->belongsTo(FamiliaBeneficiada::class, 'familia_id_familia');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produtos_id');
    }
}
