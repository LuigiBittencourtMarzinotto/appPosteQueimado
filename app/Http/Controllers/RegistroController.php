<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\Foto;

class RegistroController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $registros = Registro::with('fotos')
            ->where('id_user', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('registros.index', compact('registros'));
    }

    public function create()
    {
        return view('registros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'          => 'required|max:200',
            'descricao'       => 'required',
            'endereco_texto'  => 'required|max:255',
            'lat'             => 'required|numeric',
            'lng'             => 'required_with:lat|numeric',
            'foto'            => 'nullable|image|max:5120',
        ], [
            'titulo.required'         => 'O título é obrigatório.',
            'descricao.required'      => 'A descrição é obrigatória.',
            'endereco_texto.required' => 'O endereço é obrigatório.',
            'lat.required'            => 'Selecione um local no mapa.',
            'foto.image'              => 'O arquivo deve ser uma imagem.',
            'foto.max'                => 'A imagem deve ter no máximo 5MB.',
        ]);

        $registro = Registro::create([
            'titulo'         => $request->titulo,
            'descricao'      => $request->descricao,
            'endereco_texto' => $request->endereco_texto,
            'lat'            => $request->lat,
            'lng'            => $request->lng,
            'status'         => 'PENDENTE',
            'id_user'        => auth()->id(),
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos', 'public');
            $mime = $request->file('foto')->getMimeType();

            Foto::create([
                'id_registro'     => $registro->id,
                'caminho_arquivo' => $path,
                'mime'            => $mime,
            ]);
        }

        return redirect()->route('registros.index')->with('sucesso', 'Registro enviado com sucesso!');
    }

    public function show($id)
    {
        $registro = Registro::with('fotos', 'usuario')
            ->where('id_user', auth()->id())
            ->findOrFail($id);

        return view('registros.show', compact('registro'));
    }
}
