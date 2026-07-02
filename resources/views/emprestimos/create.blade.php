<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-slate-900">{{ __('Registrar empréstimo') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
                @if ($livros->isEmpty())
                    <p class="text-sm text-slate-600">Não há livros com exemplares disponíveis no momento.</p>
                @else
                    <form method="POST" action="{{ route('emprestimos.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="livro_id" value="Livro" />
                            <select id="livro_id" name="livro_id" required
                                class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                <option value="">Selecione...</option>
                                @foreach ($livros as $livro)
                                    <option value="{{ $livro->id }}" @selected(old('livro_id') == $livro->id)>
                                        {{ $livro->titulo }} ({{ $livro->quantidade_disponivel }} disponível(is))
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('livro_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="user_id" value="Leitor" />
                            <select id="user_id" name="user_id" required
                                class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                <option value="">Selecione...</option>
                                @foreach ($leitores as $leitor)
                                    <option value="{{ $leitor->id }}" @selected(old('user_id') == $leitor->id)>
                                        {{ $leitor->name }} ({{ $leitor->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <p class="mt-4 text-xs text-slate-500">
                            A data de empréstimo será registrada como hoje, com previsão de devolução em 14 dias.
                        </p>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('emprestimos.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Registrar
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
