<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-900">
                {{ Auth::user()->isAdmin() ? __('Empréstimos') : __('Meus empréstimos') }}
            </h2>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('emprestimos.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-md text-sm font-medium">
                    {{ __('Registrar empréstimo') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-4">
                <form method="GET" action="{{ route('emprestimos.index') }}" class="flex gap-3">
                    <select name="status" class="border-slate-300 rounded-md shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500" onchange="this.form.submit()">
                        <option value="">Todos os status</option>
                        <option value="emprestado" @selected($status === 'emprestado')>Emprestado</option>
                        <option value="devolvido" @selected($status === 'devolvido')>Devolvido</option>
                        <option value="atrasado" @selected($status === 'atrasado')>Atrasado</option>
                    </select>
                </form>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                @if ($emprestimos->isEmpty())
                    <p class="text-sm text-slate-600">Nenhum empréstimo encontrado.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 text-left">Livro</th>
                                    @if (Auth::user()->isAdmin())
                                        <th class="px-4 py-3 text-left">Leitor</th>
                                    @endif
                                    <th class="px-4 py-3 text-left">Emprestado em</th>
                                    <th class="px-4 py-3 text-left">Previsão</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($emprestimos as $emprestimo)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm text-slate-900">
                                            <a href="{{ route('emprestimos.show', $emprestimo) }}" class="hover:text-amber-600 font-medium">{{ $emprestimo->livro->titulo }}</a>
                                        </td>
                                        @if (Auth::user()->isAdmin())
                                            <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->leitor->name }}</td>
                                        @endif
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->data_emprestimo->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-600">{{ $emprestimo->data_prevista_devolucao->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm"><x-status-badge :status="$emprestimo->statusEfetivo()" /></td>
                                        <td class="px-4 py-3 text-right text-sm space-x-3">
                                            <a href="{{ route('emprestimos.show', $emprestimo) }}" class="text-slate-700 hover:text-slate-900 font-medium">Ver</a>
                                            @if (Auth::user()->isAdmin() && $emprestimo->status !== \App\Enums\EmprestimoStatus::Devolvido)
                                                <form action="{{ route('emprestimos.devolver', $emprestimo) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-emerald-700 hover:text-emerald-900 font-medium">Registrar devolução</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $emprestimos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
