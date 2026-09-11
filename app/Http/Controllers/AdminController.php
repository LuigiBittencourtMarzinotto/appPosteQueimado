<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\LogStatus;

class AdminController extends Controller
{
    /** Status válidos, na ordem em que aparecem na interface. */
    private const STATUS = ['PENDENTE', 'EM_ANDAMENTO', 'RESOLVIDO'];

    public function dashboard()
    {
        $contagens = $this->contagens();

        $recentes = Registro::with('usuario')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $ultimasAcoes = LogStatus::with('admin', 'registro')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('contagens', 'recentes', 'ultimasAcoes'));
    }

    public function lista(Request $request)
    {
        $status = in_array($request->status, self::STATUS, true) ? $request->status : null;
        $busca  = trim((string) $request->busca);
        $ordem  = $request->ordem === 'antigos' ? 'asc' : 'desc';

        $registros = Registro::with('fotos', 'usuario')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($busca !== '', fn ($q) => $q->where(function ($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                  ->orWhere('endereco_texto', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%")
                  ->orWhereHas('usuario', fn ($u) => $u->where('nome', 'like', "%{$busca}%"));
            }))
            ->orderBy('created_at', $ordem)
            ->get();

        return view('admin.lista', [
            'registros' => $registros,
            'contagens' => $this->contagens(),
            'status'    => $status,
            'busca'     => $busca,
            'ordem'     => $request->ordem === 'antigos' ? 'antigos' : 'recentes',
        ]);
    }

    public function mapa()
    {
        $registros = Registro::get(['id', 'titulo', 'endereco_texto', 'lat', 'lng', 'status', 'created_at']);

        return view('admin.mapa', [
            'registros' => $registros,
            'contagens' => $this->contagens(),
        ]);
    }

    public function show($id)
    {
        $registro = Registro::with('fotos', 'usuario', 'logs.admin')->findOrFail($id);
        return view('admin.show', compact('registro'));
    }

    public function atualizarStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PENDENTE,EM_ANDAMENTO,RESOLVIDO',
        ]);

        $registro = Registro::findOrFail($id);
        $statusAnterior = $registro->status;

        // Nada a fazer se o status escolhido já é o atual — evita log duplicado.
        if ($statusAnterior === $request->status) {
            return redirect()->back()->with('erro', 'Este registro já está com o status selecionado.');
        }

        $registro->status = $request->status;
        $registro->save();

        LogStatus::create([
            'id_registro'     => $registro->id,
            'status_anterior' => $statusAnterior,
            'status_novo'     => $request->status,
            'id_admin'        => auth()->id(),
        ]);

        $rotulos = ['PENDENTE' => 'Pendente', 'EM_ANDAMENTO' => 'Em andamento', 'RESOLVIDO' => 'Resolvido'];

        return redirect()->back()->with(
            'sucesso',
            "\"{$registro->titulo}\" agora está como {$rotulos[$request->status]}."
        );
    }

    /** Totais gerais por status, para cartões, filtros e legenda do mapa. */
    private function contagens(): array
    {
        $porStatus = Registro::selectRaw('status, COUNT(*) as total')
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
