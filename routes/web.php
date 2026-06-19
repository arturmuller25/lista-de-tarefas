<?php

use App\Http\Controllers\TarefaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Web - Lista de Tarefas
|--------------------------------------------------------------------------
*/

// Página inicial: lista de tarefas
Route::get('/', [TarefaController::class, 'index'])->name('tarefas.index');

Route::prefix('tarefas')->name('tarefas.')->group(function () {

    // Criar nova tarefa
    Route::post('/', [TarefaController::class, 'gravar'])->name('gravar');

    // Lixeira (precisa vir antes de /{tarefa} para não conflitar)
    Route::get('/lixeira', [TarefaController::class, 'lixeira'])->name('lixeira');

    // Editar / atualizar
    Route::get('/{tarefa}/editar', [TarefaController::class, 'editar'])->name('editar');
    Route::put('/{tarefa}', [TarefaController::class, 'atualizar'])->name('atualizar');

    // Marcar/desmarcar como concluída
    Route::patch('/{tarefa}/concluir', [TarefaController::class, 'concluir'])->name('concluir');

    // Remover apenas a imagem da tarefa
    Route::delete('/{tarefa}/imagem', [TarefaController::class, 'removerImagem'])->name('imagem.remover');

    // Enviar tarefa para a lixeira (soft delete)
    Route::delete('/{tarefa}', [TarefaController::class, 'excluir'])->name('excluir');

    // Ações sobre tarefas que já estão na lixeira (incluem registros soft-deleted)
    Route::patch('/{tarefa}/restaurar', [TarefaController::class, 'restaurar'])
        ->name('restaurar')->withTrashed();
    Route::delete('/{tarefa}/destruir', [TarefaController::class, 'excluirDeVez'])
        ->name('destruir')->withTrashed();
});
