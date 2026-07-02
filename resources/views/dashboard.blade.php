<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p class="text-sm text-slate-600">Olá, {{ Auth::user()->name }} ({{ Auth::user()->role->label() }}).</p>

            @if (Auth::user()->isAdmin())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Total de livros</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $metrics['total_livros'] }}</p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Exemplares disponíveis</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $metrics['exemplares_disponiveis'] }}</p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Empréstimos ativos</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ $metrics['emprestimos_ativos'] }}</p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Empréstimos atrasados</p>
                        <p class="mt-1 text-2xl font-bold text-red-600">{{ $metrics['emprestimos_atrasados'] }}</p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Últimos empréstimos</h3>

                    @if ($ultimosEmprestimos->isEmpty())
                        <p class="text-sm text-slate-600">Nenhum empréstimo registrado ainda.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Livro</th>
                                        <th class="px-4 py-3 text-left">Leitor</th>
                                        <th class="px-4 py-3 text-left">Emprestado em</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($ultimosEmprestimos as $emprestimo)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-900">{{ $emprestimo->livro->titulo }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->leitor->name }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->data_emprestimo->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-sm"><x-status-badge :status="$emprestimo->statusEfetivo()" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Meus empréstimos ativos</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">{{ $metrics['meus_emprestimos_ativos'] }}</p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Meus empréstimos atrasados</p>
                        <p class="mt-1 text-2xl font-bold text-red-600">{{ $metrics['meus_emprestimos_atrasados'] }}</p>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                        <p class="text-sm text-slate-500">Livros no acervo</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $metrics['livros_no_acervo'] }}</p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Meus últimos empréstimos</h3>

                    @if ($meusEmprestimos->isEmpty())
                        <p class="text-sm text-slate-600">Você ainda não realizou nenhum empréstimo.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Livro</th>
                                        <th class="px-4 py-3 text-left">Emprestado em</th>
                                        <th class="px-4 py-3 text-left">Previsão</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($meusEmprestimos as $emprestimo)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-slate-900">{{ $emprestimo->livro->titulo }}</td>
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
            @endif
        </div>
    </div>
</x-app-layout>
