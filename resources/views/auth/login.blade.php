<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Poste Queimado</title>
    @include('partials.head-assets')
</head>
<body class="font-sans text-texto">
<div class="min-h-screen flex items-center justify-center bg-azul p-6">
    <div class="bg-white rounded-2xl px-8 py-10 w-full max-w-sm shadow-auth">
        <div class="text-center mb-7">
            <div class="text-5xl text-azul mb-2"><i class="fa-solid fa-lightbulb"></i></div>
            <h1 class="text-2xl text-azul font-bold">Poste Queimado</h1>
        </div>

        @if($errors->any())
            <div class="px-4 py-3 rounded-lg mb-4 text-sm bg-red-50 text-red-900 border-l-4 border-red-600">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1 text-gray-600">E-mail</label>
                <input type="email" id="email" name="email"
                       class="w-full px-3.5 py-2.5 border border-borda rounded-lg text-[.95rem] bg-white focus:outline-none focus:border-azul focus:ring-4 focus:ring-azul/10 transition"
                       value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
            </div>
            <div class="mb-4">
                <label for="senha" class="block text-sm font-medium mb-1 text-gray-600">Senha</label>
                <input type="password" id="senha" name="senha"
                       class="w-full px-3.5 py-2.5 border border-borda rounded-lg text-[.95rem] bg-white focus:outline-none focus:border-azul focus:ring-4 focus:ring-azul/10 transition"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full bg-azul text-white py-2.5 rounded-lg font-semibold hover:opacity-90 hover:shadow-md active:scale-[.98] focus-visible:outline focus-visible:outline-2 focus-visible:outline-amarelo focus-visible:outline-offset-2 transition">
                Entrar
            </button>
        </form>

        <div class="text-center mt-5 text-sm">
            <p>Ainda não tem conta? <a href="{{ route('cadastro') }}" class="text-azul font-medium hover:underline">Cadastre-se</a></p>
        </div>

        <div class="mt-5 px-3 py-3 bg-gray-100 rounded-lg text-xs text-gray-600">
            <strong class="block mb-1">Contas de teste:</strong>
            <i class="fa-solid fa-user text-gray-500"></i> joao@email.com / user123<br>
            <i class="fa-solid fa-key text-gray-500"></i> admin@postequeimado.com / admin123
        </div>
    </div>
</div>
</body>
</html>
