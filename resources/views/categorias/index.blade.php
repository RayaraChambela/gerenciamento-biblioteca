<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-900">
                {{ __('Categorias') }}
            </h2>
            <a href="{{ route('categorias.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-md text-sm font-medium">
                {{ __('Nova categoria') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                @if ($categorias->isEmpty())
                    <p class="text-sm text-slate-600">Nenhuma categoria cadastrada ainda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nome</th>
                                    <th class="px-4 py-3 text-left">Livros</th>
                                    <th class="px-4 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($categorias as $categoria)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm text-slate-900">{{ $categoria->nome }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $categoria->livros_count }}</td>
                                        <td class="px-4 py-3 text-right text-sm space-x-3">
                                            <a href="{{ route('categorias.edit', $categoria) }}" class="text-slate-700 hover:text-slate-900 font-medium">Editar</a>
                                            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta categoria?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categorias->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
