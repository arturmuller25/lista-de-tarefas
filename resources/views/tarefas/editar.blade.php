@extends('_base')

@section('titulo', 'Editar tarefa')

@section('conteudo')

    {{-- Imagem atual (formulário próprio, fora do form de edição) --}}
    @if ($tarefa->imagem)
        <div class="card">
            <h2>Imagem atual</h2>
            <img src="{{ asset('storage/' . $tarefa->imagem) }}" alt="Imagem da tarefa" class="task-thumb">
            <div class="mt">
                <form action="{{ route('tarefas.imagem.remover', $tarefa) }}" method="POST"
                      onsubmit="return confirm('Remover a imagem desta tarefa?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-muted">Remover imagem</button>
                </form>
            </div>
        </div>
    @endif

    <div class="card">
        <h2>Editar tarefa</h2>

        <form action="{{ route('tarefas.atualizar', $tarefa) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="titulo">Título *</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tarefa->titulo) }}" required>

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao">{{ old('descricao', $tarefa->descricao) }}</textarea>

            <label for="imagem">{{ $tarefa->imagem ? 'Trocar imagem' : 'Adicionar imagem' }} (opcional)</label>
            <input type="file" name="imagem" id="imagem" accept="image/*">

            <div class="mt">
                <button type="submit" class="btn btn-success">Salvar alterações</button>
                <a href="{{ route('tarefas.index') }}" class="btn btn-muted">Cancelar</a>
            </div>
        </form>
    </div>

@endsection
