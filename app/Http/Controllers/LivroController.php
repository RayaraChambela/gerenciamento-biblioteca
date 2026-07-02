<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Categoria;
use App\Models\Livro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $livros = Livro::with('categoria')
            ->when($request->string('busca')->trim()->toString(), function ($query, string $busca) {
                $query->where(function ($query) use ($busca) {
                    $query->where('titulo', 'like', "%{$busca}%")
                        ->orWhere('autor', 'like', "%{$busca}%");
                });
            })
            ->when($request->integer('categoria_id'), fn ($query, int $categoriaId) => $query->where('categoria_id', $categoriaId))
            ->orderBy('titulo')
            ->paginate(10)
            ->withQueryString();

        $categorias = Categoria::orderBy('nome')->get();

        return view('livros.index', compact('livros', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('livros.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLivroRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['quantidade_disponivel'] = $data['quantidade_total'];

        Livro::create($data);

        return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro): View
    {
        $livro->load('categoria', 'emprestimos.leitor');

        return view('livros.show', compact('livro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livro $livro): View
    {
        $categorias = Categoria::orderBy('nome')->get();

        return view('livros.edit', compact('livro', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLivroRequest $request, Livro $livro): RedirectResponse
    {
        $emprestados = $livro->quantidade_total - $livro->quantidade_disponivel;

        $data = $request->validated();
        $data['quantidade_disponivel'] = $data['quantidade_total'] - $emprestados;

        $livro->update($data);

        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro): RedirectResponse
    {
        if ($livro->emprestimos()->whereNull('data_devolucao')->exists()) {
            return redirect()->route('livros.index')
                ->with('error', 'Não é possível excluir um livro com empréstimos em aberto.');
        }

        $livro->delete();

        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso.');
    }
}
