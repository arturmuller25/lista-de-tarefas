<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TarefaController extends Controller
{
    /**
     * Lista todas as tarefas.
     *
     * Ordenação via Eloquent/SQL: as tarefas NÃO concluídas (concluida = 0)
     * aparecem primeiro e as CONCLUÍDAS (concluida = 1) vão para o final da
     * lista. Dentro de cada grupo, as mais recentes aparecem em cima.
     */
    public function index()
    {
        $tarefas = Tarefa::orderBy('concluida', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tarefas.index', compact('tarefas'));
    }

    /**
     * Regras de validação reutilizadas no cadastro e na edição.
     *
     * @return array<string, string>
     */
    private function regras(): array
    {
        return [
            'titulo'    => 'required|string|min:3|max:255',
            'descricao' => 'nullable|string',
            'imagem'    => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ];
    }

    /**
     * Mensagens de validação em português.
     *
     * @return array<string, string>
     */
    private function mensagens(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.min'      => 'O título deve ter pelo menos :min caracteres.',
            'titulo.max'      => 'O título não pode passar de :max caracteres.',
            'imagem.image'    => 'O arquivo enviado precisa ser uma imagem.',
            'imagem.mimes'    => 'A imagem deve ser dos tipos: :values.',
            'imagem.max'      => 'A imagem não pode passar de 2 MB.',
        ];
    }

    /**
     * Nomes amigáveis dos atributos nas mensagens de erro.
     *
     * @return array<string, string>
     */
    private function atributos(): array
    {
        return [
            'titulo'    => 'título',
            'descricao' => 'descrição',
            'imagem'    => 'imagem',
        ];
    }

    /**
     * Salva uma nova tarefa (com imagem opcional).
     */
    public function gravar(Request $request)
    {
        $dados = $request->validate($this->regras(), $this->mensagens(), $this->atributos());

        // Faz upload da imagem, se enviada (uma por tarefa).
        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('tarefas', 'public');
        }

        Tarefa::create($dados);

        return redirect()->route('tarefas.index')
            ->with('mensagem', 'Tarefa criada com sucesso!');
    }

    /**
     * Exibe o formulário de edição de uma tarefa.
     */
    public function editar(Tarefa $tarefa)
    {
        return view('tarefas.editar', compact('tarefa'));
    }

    /**
     * Atualiza uma tarefa existente.
     */
    public function atualizar(Request $request, Tarefa $tarefa)
    {
        $dados = $request->validate($this->regras(), $this->mensagens(), $this->atributos());

        // Se uma nova imagem for enviada, remove a antiga e guarda a nova.
        if ($request->hasFile('imagem')) {
            if ($tarefa->imagem) {
                Storage::disk('public')->delete($tarefa->imagem);
            }
            $dados['imagem'] = $request->file('imagem')->store('tarefas', 'public');
        }

        $tarefa->update($dados);

        return redirect()->route('tarefas.index')
            ->with('mensagem', 'Tarefa atualizada com sucesso!');
    }

    /**
     * Marca/desmarca a tarefa como concluída.
     */
    public function concluir(Tarefa $tarefa)
    {
        $tarefa->update([
            'concluida' => ! $tarefa->concluida,
        ]);

        return redirect()->route('tarefas.index')
            ->with('mensagem', $tarefa->concluida ? 'Tarefa concluída!' : 'Tarefa reaberta!');
    }

    /**
     * Remove apenas a imagem da tarefa, mantendo a tarefa.
     */
    public function removerImagem(Tarefa $tarefa)
    {
        if ($tarefa->imagem) {
            Storage::disk('public')->delete($tarefa->imagem);
            $tarefa->update(['imagem' => null]);
        }

        return redirect()->back()
            ->with('mensagem', 'Imagem removida com sucesso!');
    }

    /**
     * Envia a tarefa para a lixeira (soft delete).
     */
    public function excluir(Tarefa $tarefa)
    {
        $tarefa->delete();

        return redirect()->route('tarefas.index')
            ->with('mensagem', 'Tarefa movida para a lixeira.');
    }

    /**
     * Lista as tarefas que estão na lixeira.
     */
    public function lixeira()
    {
        $tarefas = Tarefa::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('tarefas.lixeira', compact('tarefas'));
    }

    /**
     * Restaura uma tarefa da lixeira.
     */
    public function restaurar(Tarefa $tarefa)
    {
        $tarefa->restore();

        return redirect()->route('tarefas.lixeira')
            ->with('mensagem', 'Tarefa restaurada com sucesso!');
    }

    /**
     * Exclui a tarefa definitivamente (e a sua imagem, se houver).
     */
    public function excluirDeVez(Tarefa $tarefa)
    {
        if ($tarefa->imagem) {
            Storage::disk('public')->delete($tarefa->imagem);
        }

        $tarefa->forceDelete();

        return redirect()->route('tarefas.lixeira')
            ->with('mensagem', 'Tarefa excluída definitivamente.');
    }
}
