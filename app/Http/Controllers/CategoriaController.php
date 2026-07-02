<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categorias = Categoria::withCount('livros')
            ->orderBy('nome')
            ->paginate(10);

        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request): RedirectResponse
    {
        Categoria::create($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria): View
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $categoria->update($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->livros()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'Não é possível excluir uma categoria que possui livros vinculados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
