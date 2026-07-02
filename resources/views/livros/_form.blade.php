@props(['livro' => null, 'categorias'])

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-input-label for="titulo" value="Título" />
        <x-text-input id="titulo" name="titulo" type="text" class="mt-1 block w-full"
            value="{{ old('titulo', $livro->titulo ?? '') }}" required autofocus />
        <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="autor" value="Autor" />
        <x-text-input id="autor" name="autor" type="text" class="mt-1 block w-full"
            value="{{ old('autor', $livro->autor ?? '') }}" required />
        <x-input-error :messages="$errors->get('autor')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="categoria_id" value="Categoria" />
        <select id="categoria_id" name="categoria_id" required
            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500">
            <option value="">Selecione...</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $livro->categoria_id ?? '') == $categoria->id)>
                    {{ $categoria->nome }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="isbn" value="ISBN (opcional)" />
        <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full"
            value="{{ old('isbn', $livro->isbn ?? '') }}" />
        <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="ano_publicacao" value="Ano de publicação (opcional)" />
        <x-text-input id="ano_publicacao" name="ano_publicacao" type="number" class="mt-1 block w-full"
            value="{{ old('ano_publicacao', $livro->ano_publicacao ?? '') }}" />
        <x-input-error :messages="$errors->get('ano_publicacao')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="quantidade_total" value="Quantidade de exemplares" />
        <x-text-input id="quantidade_total" name="quantidade_total" type="number" min="1" class="mt-1 block w-full"
            value="{{ old('quantidade_total', $livro->quantidade_total ?? 1) }}" required />
        <x-input-error :messages="$errors->get('quantidade_total')" class="mt-2" />
        @if ($livro)
            <p class="mt-1 text-xs text-slate-500">
                Atualmente {{ $livro->quantidade_total - $livro->quantidade_disponivel }} exemplar(es) emprestado(s).
            </p>
        @endif
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('livros.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium">
        Cancelar
    </a>
    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium">
        Salvar
    </button>
</div>
