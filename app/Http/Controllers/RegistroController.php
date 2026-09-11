<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\Foto;

class RegistroController extends Controller
{
    /** Status válidos, na ordem em que aparecem na interface. */
    private const STATUS = ['PENDENTE', 'EM_ANDAMENTO', 'RESOLVIDO'];

    public function home()
    {
        $contagens = $this->contagens();

        $recentes = Registro::with('fotos')
            ->where('id_user', auth()->id())
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('home', compact('contagens', 'recentes'));
    }

    public function index(Request $request)
    {
        $status = in_array($request->status, self::STATUS, true) ? $request->status : null;
        $busca  = trim((string) $request->busca);

        $registros = Registro::with('fotos')
            ->where('id_user', auth()->id())
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($busca !== '', fn ($q) => $q->where(function ($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                  ->orWhere('endereco_texto', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%");
            }))
            ->orderByDesc('created_at')
            ->get();

        return view('registros.index', [
            'registros' => $registros,
            'contagens' => $this->contagens(),
            'status'    => $status,
            'busca'     => $busca,
        ]);
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

        return redirect()->route('registros.show', $registro->id)
            ->with('sucesso', 'Registro enviado! Acompanhe o andamento por aqui.');
    }

    public function show($id)
    {
        $registro = Registro::with('fotos', 'usuario', 'logs')
            ->where('id_user', auth()->id())
            ->findOrFail($id);

        return view('registros.show', compact('registro'));
    }

    /** Quantidade de registros do usuário por status, para os filtros e cartões. */
    private function contagens(): array
    {
        $porStatus = Registro::where('id_user', auth()->id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total'        => (int) $porStatus->sum(),
            'PENDENTE'     => (int) ($porStatus['PENDENTE']     ?? 0),
            'EM_ANDAMENTO' => (int) ($porStatus['EM_ANDAMENTO'] ?? 0),
            'RESOLVIDO'    => (int) ($porStatus['RESOLVIDO']    ?? 0),
        ];
    }
}
