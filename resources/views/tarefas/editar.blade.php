@extends('_base')

@section('titulo', 'Editar tarefa')

@section('conteudo')

    {{-- Imagem atual (formulário próprio, fora do form de edição) --}}
    @if ($tarefa->imagem)
        <div class="card">
            <h2><x-icon name="photo" /> Imagem atual</h2>
            <img src="{{ asset('storage/' . $tarefa->imagem) }}" alt="Imagem da tarefa" class="task-thumb">
            <div class="mt">
                <form action="{{ route('tarefas.imagem.remover', $tarefa) }}" method="POST"
                      onsubmit="return confirm('Remover a imagem desta tarefa?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost">
                        <x-icon name="image-off" /> Remover imagem
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="card">
        <h2><x-icon name="pencil" /> Editar tarefa</h2>

        <form action="{{ route('tarefas.atualizar', $tarefa) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tarefa->titulo) }}" required minlength="3" maxlength="255">

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao">{{ old('descricao', $tarefa->descricao) }}</textarea>

            <label>{{ $tarefa->imagem ? 'Trocar imagem' : 'Adicionar imagem' }} (opcional)</label>
            <div class="file-field">
                <label class="file-button" for="imagem">
                    <x-icon name="photo" />
                    <span>Escolher imagem</span>
                </label>
                <input type="file" name="imagem" id="imagem" accept="image/*">
                <span class="file-name" data-vazio="Nenhuma imagem selecionada">Nenhuma imagem selecionada</span>
            </div>

            <div class="form-actions mt">
                <button type="submit" class="btn btn-primary">
                    <x-icon name="check-mini" /> Salvar alterações
                </button>
                <a href="{{ route('tarefas.index') }}" class="btn btn-ghost">
                    <x-icon name="close" /> Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection
