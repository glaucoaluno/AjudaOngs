<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoacaoFamilia extends Model
{
    use HasFactory;

    protected $table = 'doacoes_familias';

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