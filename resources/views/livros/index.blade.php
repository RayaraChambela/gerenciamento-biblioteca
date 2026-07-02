<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-900">
                {{ __('Livros') }}
            </h2>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('livros.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-md text-sm font-medium">
                    {{ __('Novo livro') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-4">
                <form method="GET" action="{{ route('livros.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar por título ou autor"
                        class="flex-1 border-slate-300 rounded-md shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500" />

                    <select name="categoria_id" class="border-slate-300 rounded-md shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="">Todas as categorias</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Filtrar
                    </button>
                    @if (request('busca') || request('categoria_id'))
                        <a href="{{ route('livros.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium text-center">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                @if ($livros->isEmpty())
                    <p class="text-sm text-slate-600">Nenhum livro encontrado.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Título</th>
                                    <th class="px-4 py-3 text-left">Autor</th>
                                    <th class="px-4 py-3 text-left">Categoria</th>
                                    <th class="px-4 py-3 text-left">Disponibilidade</th>
                                    <th class="px-4 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($livros as $livro)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm text-slate-900">
                                            <a href="{{ route('livros.show', $livro) }}" class="hover:text-amber-600 font-medium">{{ $livro->titulo }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $livro->autor }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $livro->categoria->nome }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $livro->quantidade_disponivel }} / {{ $livro->quantidade_total }}</td>
                                        <td class="px-4 py-3 text-right text-sm space-x-3">
                                            <a href="{{ route('livros.show', $livro) }}" class="text-slate-700 hover:text-slate-900 font-medium">Ver</a>
                                            @if (Auth::user()->isAdmin())
                                                <a href="{{ route('livros.edit', $livro) }}" class="text-slate-700 hover:text-slate-900 font-medium">Editar</a>
                                                <form action="{{ route('livros.destroy', $livro) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este livro?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Excluir</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $livros->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
