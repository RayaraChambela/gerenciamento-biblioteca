<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-900">{{ $livro->titulo }}</h2>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('livros.edit', $livro) }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium">
                    Editar
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-500">Autor</dt>
                        <dd class="text-slate-900 font-medium">{{ $livro->autor }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Categoria</dt>
                        <dd class="text-slate-900 font-medium">{{ $livro->categoria->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">ISBN</dt>
                        <dd class="text-slate-900 font-medium">{{ $livro->isbn ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Ano de publicação</dt>
                        <dd class="text-slate-900 font-medium">{{ $livro->ano_publicacao ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Exemplares disponíveis</dt>
                        <dd class="text-slate-900 font-medium">{{ $livro->quantidade_disponivel }} de {{ $livro->quantidade_total }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Histórico de empréstimos</h3>

                @if ($livro->emprestimos->isEmpty())
                    <p class="text-sm text-slate-600">Nenhum empréstimo registrado para este livro.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Leitor</th>
                                    <th class="px-4 py-3 text-left">Emprestado em</th>
                                    <th class="px-4 py-3 text-left">Previsão de devolução</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($livro->emprestimos->sortByDesc('data_emprestimo') as $emprestimo)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-slate-900">{{ $emprestimo->leitor->name }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->data_emprestimo->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->data_prevista_devolucao->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm"><x-status-badge :status="$emprestimo->statusEfetivo()" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
