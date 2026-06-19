<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Lista de Tarefas')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <header class="topo">
            <h1>📝 Lista de Tarefas</h1>
            <nav>
                <a href="{{ route('tarefas.index') }}" class="btn btn-sm btn-muted">Tarefas</a>
                <a href="{{ route('tarefas.lixeira') }}" class="btn btn-sm btn-muted">🗑️ Lixeira</a>
            </nav>
        </header>

        {{-- Mensagem de sucesso (flash) --}}
        @if (session('mensagem'))
            <div class="alert">{{ session('mensagem') }}</div>
        @endif

        {{-- Erros de validação --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('conteudo')
    </div>
</body>
</html>
