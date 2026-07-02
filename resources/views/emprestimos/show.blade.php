<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900">{{ __('Detalhes do empréstimo') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="sm:col-span-2">
                        <dt class="text-slate-500">Livro</dt>
                        <dd class="text-slate-900 font-medium">
                            <a href="{{ route('livros.show', $emprestimo->livro) }}" class="hover:text-amber-600">{{ $emprestimo->livro->titulo }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Leitor</dt>
                        <dd class="text-slate-900 font-medium">{{ $emprestimo->leitor->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Status</dt>
                        <dd><x-status-badge :status="$emprestimo->statusEfetivo()" /></dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Data do empréstimo</dt>
                        <dd class="text-slate-900 font-medium">{{ $emprestimo->data_emprestimo->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Previsão de devolução</dt>
                        <dd class="text-slate-900 font-medium">{{ $emprestimo->data_prevista_devolucao->format('d/m/Y') }}</dd>
                    </div>
                    @if ($emprestimo->data_devolucao)
                        <div>
                            <dt class="text-slate-500">Devolvido em</dt>
                            <dd class="text-slate-900 font-medium">{{ $emprestimo->data_devolucao->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('emprestimos.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium">
                        Voltar
                    </a>
                    @if (Auth::user()->isAdmin() && $emprestimo->status !== \App\Enums\EmprestimoStatus::Devolvido)
                        <form action="{{ route('emprestimos.devolver', $emprestimo) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Registrar devolução
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
