@extends('_base')

@section('titulo', 'Lista de Tarefas')

@section('conteudo')

    <p class="subtitle">Organize as suas tarefas — as concluídas vão para o final da lista.</p>

    {{-- Formulário de criação --}}
    <div class="card">
        <h2>Nova tarefa</h2>
        <form action="{{ route('tarefas.gravar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="titulo">Título *</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" placeholder="O que precisa ser feito?" required minlength="3" maxlength="255">

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" placeholder="Detalhes (opcional)">{{ old('descricao') }}</textarea>

            <label for="imagem">Imagem (opcional)</label>
            <input type="file" name="imagem" id="imagem" accept="image/*">

            <button type="submit" class="btn btn-success">Adicionar tarefa</button>
        </form>
    </div>

    {{-- Listagem --}}
    @if ($tarefas->isEmpty())
        <div class="card empty">Nenhuma tarefa cadastrada ainda. Adicione a primeira! 🚀</div>
    @else
        <ul class="task-list">
            @foreach ($tarefas as $tarefa)
                <li class="task-item {{ $tarefa->concluida ? 'concluida' : '' }}">

                    {{-- Imagem da tarefa --}}
                    @if ($tarefa->imagem)
                        <img src="{{ asset('storage/' . $tarefa->imagem) }}" alt="Imagem da tarefa" class="task-thumb">
                    @endif

                    <div class="task-body">
                        <div class="task-title">
                            {{ $tarefa->titulo }}
                            @if ($tarefa->concluida)
                                <span class="badge badge-done">Concluída</span>
                            @else
                                <span class="badge badge-pending">Pendente</span>
                            @endif
                        </div>
                        @if ($tarefa->descricao)
                            <div class="task-desc">{{ $tarefa->descricao }}</div>
                        @endif
                        @if ($tarefa->created_at)
                            <div class="task-meta">Criada {{ $tarefa->created_at->locale('pt_BR')->diffForHumans() }}</div>
                        @endif
                    </div>

                    <div class="task-actions">
                        {{-- Marcar / desmarcar como concluída --}}
                        <form action="{{ route('tarefas.concluir', $tarefa) }}" method="POST" class="inline-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $tarefa->concluida ? 'btn-muted' : 'btn-success' }}">
                                {{ $tarefa->concluida ? 'Reabrir' : 'Concluir' }}
                            </button>
                        </form>

                        {{-- Editar --}}
                        <a href="{{ route('tarefas.editar', $tarefa) }}" class="btn btn-sm btn-warning">Editar</a>

                        {{-- Remover imagem (apenas se tiver) --}}
                        @if ($tarefa->imagem)
                            <form action="{{ route('tarefas.imagem.remover', $tarefa) }}" method="POST" class="inline-form"
                                  onsubmit="return confirm('Remover a imagem desta tarefa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-muted">Remover imagem</button>
                            </form>
                        @endif

                        {{-- Enviar para a lixeira --}}
                        <form action="{{ route('tarefas.excluir', $tarefa) }}" method="POST" class="inline-form"
                              onsubmit="return confirm('Mover esta tarefa para a lixeira?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

@endsection
