@extends('_base')

@section('titulo', 'Lista de Tarefas')

@section('conteudo')

    <p class="subtitle">Organize as suas tarefas. As concluídas vão para o final da lista.</p>

    {{-- Formulário de criação --}}
    <div class="card">
        <h2><x-icon name="plus" /> Nova tarefa</h2>
        <form action="{{ route('tarefas.gravar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" placeholder="O que precisa ser feito?" required minlength="3" maxlength="255">

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" placeholder="Detalhes (opcional)">{{ old('descricao') }}</textarea>

            <label>Imagem (opcional)</label>
            <div class="file-field">
                <label class="file-button" for="imagem">
                    <x-icon name="photo" />
                    <span>Escolher imagem</span>
                </label>
                <input type="file" name="imagem" id="imagem" accept="image/*">
                <span class="file-name" data-vazio="Nenhuma imagem selecionada">Nenhuma imagem selecionada</span>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <x-icon name="plus" /> Adicionar tarefa
            </button>
        </form>
    </div>

    {{-- Listagem --}}
    @if ($tarefas->isEmpty())
        <div class="card empty">
            <x-icon name="inbox" />
            <p>Nenhuma tarefa cadastrada ainda. Adicione a primeira.</p>
        </div>
    @else
        <ul class="task-list">
            @foreach ($tarefas as $tarefa)
                <li class="task-item {{ $tarefa->concluida ? 'concluida' : '' }}">

                    @if ($tarefa->imagem)
                        <img src="{{ asset('storage/' . $tarefa->imagem) }}" alt="Imagem da tarefa" class="task-thumb">
                    @endif

                    <div class="task-body">
                        <div class="task-title">
                            {{ $tarefa->titulo }}
                            @if ($tarefa->concluida)
                                <span class="badge badge-done"><x-icon name="check-mini" /> Concluída</span>
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
                        {{-- Concluir / reabrir --}}
                        <form action="{{ route('tarefas.concluir', $tarefa) }}" method="POST" class="inline-form">
                            @csrf
                            @method('PATCH')
                            @if ($tarefa->concluida)
                                <button type="submit" class="btn btn-icon" title="Reabrir tarefa" aria-label="Reabrir tarefa">
                                    <x-icon name="undo" />
                                </button>
                            @else
                                <button type="submit" class="btn btn-icon is-done" title="Concluir tarefa" aria-label="Concluir tarefa">
                                    <x-icon name="check" />
                                </button>
                            @endif
                        </form>

                        {{-- Editar --}}
                        <a href="{{ route('tarefas.editar', $tarefa) }}" class="btn btn-icon is-primary" title="Editar tarefa" aria-label="Editar tarefa">
                            <x-icon name="pencil" />
                        </a>

                        {{-- Remover imagem (apenas se tiver) --}}
                        @if ($tarefa->imagem)
                            <form action="{{ route('tarefas.imagem.remover', $tarefa) }}" method="POST" class="inline-form"
                                  onsubmit="return confirm('Remover a imagem desta tarefa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon" title="Remover imagem" aria-label="Remover imagem">
                                    <x-icon name="image-off" />
                                </button>
                            </form>
                        @endif

                        {{-- Enviar para a lixeira --}}
                        <form action="{{ route('tarefas.excluir', $tarefa) }}" method="POST" class="inline-form"
                              onsubmit="return confirm('Mover esta tarefa para a lixeira?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon is-danger" title="Excluir tarefa" aria-label="Excluir tarefa">
                                <x-icon name="trash" />
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

@endsection
