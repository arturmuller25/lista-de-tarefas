<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use SoftDeletes;

    /**
     * Nome da tabela no banco.
     *
     * @var string
     */
    protected $table = 'tarefas';

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'imagem',
        'concluida',
    ];

    /**
     * Conversão de tipos dos atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'concluida' => 'boolean',
    ];
}
