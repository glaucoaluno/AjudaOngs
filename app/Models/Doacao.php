<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doacao extends Model
{
    protected $fillable = ['familia', 'produto', 'quantidade', 'data'];
}
