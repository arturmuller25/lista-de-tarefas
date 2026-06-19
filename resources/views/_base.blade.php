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
            <div class="logo">
                <x-icon name="logo" />
                <h1>Lista de Tarefas</h1>
            </div>
            <nav>
                <a href="{{ route('tarefas.index') }}" class="btn btn-sm btn-nav">
                    <x-icon name="list" /> Tarefas
                </a>
                <a href="{{ route('tarefas.lixeira') }}" class="btn btn-sm btn-nav">
                    <x-icon name="trash" /> Lixeira
                </a>
            </nav>
        </header>

        {{-- Mensagem de sucesso (flash) --}}
        @if (session('mensagem'))
            <div class="alert">
                <x-icon name="check" />
                <span>{{ session('mensagem') }}</span>
            </div>
        @endif

        {{-- Erros de validação --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <x-icon name="close" />
                <ul>
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('conteudo')
    </div>

    {{-- Atualiza o nome do arquivo escolhido no input personalizado --}}
    <script>
        document.querySelectorAll('.file-field input[type="file"]').forEach(function (input) {
            input.addEventListener('change', function () {
                var campo = input.closest('.file-field').querySelector('.file-name');
                campo.textContent = input.files.length ? input.files[0].name : campo.dataset.vazio;
            });
        });
    </script>
</body>
</html>
