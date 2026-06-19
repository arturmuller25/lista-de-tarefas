<?php

namespace Tests\Feature;

use App\Models\Tarefa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TarefaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Gera um PNG 1x1 real como arquivo de upload (não depende da extensão GD).
     */
    private function imagemFake(string $nome = 'foto.png'): UploadedFile
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        );
        $caminho = tempnam(sys_get_temp_dir(), 'img') . '.png';
        file_put_contents($caminho, $png);

        // O quinto parâmetro (true) coloca o objeto em "modo de teste".
        return new UploadedFile($caminho, $nome, 'image/png', null, true);
    }

    public function test_pagina_inicial_carrega(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_cria_tarefa(): void
    {
        $this->post('/tarefas', [
            'titulo'    => 'Estudar Laravel',
            'descricao' => 'Fazer o trabalho',
        ])->assertRedirect(route('tarefas.index'));

        $this->assertDatabaseHas('tarefas', [
            'titulo'    => 'Estudar Laravel',
            'concluida' => false,
        ]);
    }

    public function test_titulo_e_obrigatorio(): void
    {
        $this->post('/tarefas', ['titulo' => ''])
            ->assertSessionHasErrors('titulo');
    }

    public function test_cria_tarefa_com_imagem(): void
    {
        Storage::fake('public');

        $this->post('/tarefas', [
            'titulo' => 'Tarefa com imagem',
            'imagem' => $this->imagemFake(),
        ])->assertRedirect(route('tarefas.index'));

        $tarefa = Tarefa::first();
        $this->assertNotNull($tarefa->imagem);
        Storage::disk('public')->assertExists($tarefa->imagem);
    }

    public function test_atualiza_tarefa(): void
    {
        $tarefa = Tarefa::create(['titulo' => 'Antigo']);

        $this->put("/tarefas/{$tarefa->id}", ['titulo' => 'Novo título'])
            ->assertRedirect(route('tarefas.index'));

        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'titulo' => 'Novo título']);
    }

    public function test_marca_e_desmarca_como_concluida(): void
    {
        $tarefa = Tarefa::create(['titulo' => 'Tarefa']);

        $this->patch("/tarefas/{$tarefa->id}/concluir");
        $this->assertTrue($tarefa->fresh()->concluida);

        $this->patch("/tarefas/{$tarefa->id}/concluir");
        $this->assertFalse($tarefa->fresh()->concluida);
    }

    public function test_remove_imagem_mantendo_tarefa(): void
    {
        Storage::fake('public');

        $this->post('/tarefas', [
            'titulo' => 'Com imagem',
            'imagem' => $this->imagemFake(),
        ]);

        $tarefa = Tarefa::first();
        $caminho = $tarefa->imagem;
        Storage::disk('public')->assertExists($caminho);

        $this->delete("/tarefas/{$tarefa->id}/imagem")
            ->assertRedirect();

        $this->assertNull($tarefa->fresh()->imagem);
        Storage::disk('public')->assertMissing($caminho);
    }

    public function test_excluir_envia_para_a_lixeira(): void
    {
        $tarefa = Tarefa::create(['titulo' => 'Para a lixeira']);

        $this->delete("/tarefas/{$tarefa->id}")
            ->assertRedirect(route('tarefas.index'));

        // Soft delete: continua no banco, mas com deleted_at preenchido.
        $this->assertSoftDeleted('tarefas', ['id' => $tarefa->id]);
    }

    public function test_restaura_tarefa_da_lixeira(): void
    {
        $tarefa = Tarefa::create(['titulo' => 'Restaurar']);
        $tarefa->delete();

        $this->patch("/tarefas/{$tarefa->id}/restaurar")
            ->assertRedirect(route('tarefas.lixeira'));

        $this->assertDatabaseHas('tarefas', ['id' => $tarefa->id, 'deleted_at' => null]);
    }

    public function test_exclui_definitivamente(): void
    {
        $tarefa = Tarefa::create(['titulo' => 'Sumir']);
        $tarefa->delete();

        $this->delete("/tarefas/{$tarefa->id}/destruir")
            ->assertRedirect(route('tarefas.lixeira'));

        $this->assertDatabaseMissing('tarefas', ['id' => $tarefa->id]);
    }

    public function test_concluidas_vao_para_o_final_da_lista(): void
    {
        Tarefa::create(['titulo' => 'Pendente A']);
        Tarefa::create(['titulo' => 'Concluida X', 'concluida' => true]);
        Tarefa::create(['titulo' => 'Pendente B']);

        $resposta = $this->get('/');
        $resposta->assertOk();

        $conteudo = $resposta->getContent();
        $posConcluida = strpos($conteudo, 'Concluida X');
        $posPendenteB = strpos($conteudo, 'Pendente B');

        $this->assertGreaterThan($posPendenteB, $posConcluida,
            'A tarefa concluída deveria aparecer no final da lista.');
    }
}
