@props(['categoria' => null])

<div>
    <x-input-label for="nome" value="Nome" />
    <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full"
        value="{{ old('nome', $categoria->nome ?? '') }}" required autofocus />
    <x-input-error :messages="$errors->get('nome')" class="mt-2" />
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('categorias.index') }}" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-md text-sm font-medium">
        Cancelar
    </a>
    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium">
        Salvar
    </button>
</div>
