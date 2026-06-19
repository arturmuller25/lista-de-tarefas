@extends('_base')

@section('titulo', 'Lixeira')

@section('conteudo')

    <p class="subtitle">Tarefas removidas. Você pode restaurá-las ou excluí-las definitivamente.</p>

    @if ($tarefas->isEmpty())
        <div class="card empty">A lixeira está vazia. 🧹</div>
    @else
        <ul class="task-list">
            @foreach ($tarefas as $tarefa)
                <li class="task-item lixeira">

                    @if ($tarefa->imagem)
                        <img src="{{ asset('storage/' . $tarefa->imagem) }}" alt="Imagem da tarefa" class="task-thumb">
                    @endif

                    <div class="task-body">
                        <div class="task-title">{{ $tarefa->titulo }}</div>
                        @if ($tarefa->descricao)
                            <div class="task-desc">{{ $tarefa->descricao }}</div>
                        @endif
                        <div class="task-meta">Removida {{ $tarefa->deleted_at->locale('pt_BR')->diffForHumans() }}</div>
                    </div>

                    <div class="task-actions">
                        {{-- Restaurar --}}
                        <form action="{{ route('tarefas.restaurar', $tarefa) }}" method="POST" class="inline-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                        </form>

                        {{-- Excluir definitivamente --}}
                        <form action="{{ route('tarefas.destruir', $tarefa) }}" method="POST" class="inline-form"
                              onsubmit="return confirm('Excluir esta tarefa DEFINITIVAMENTE? Esta ação não pode ser desfeita.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir de vez</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

@endsection
