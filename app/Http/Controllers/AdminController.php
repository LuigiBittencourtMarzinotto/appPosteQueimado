<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\LogStatus;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totais = [
            'pendente'     => Registro::where('status', 'PENDENTE')->count(),
            'em_andamento' => Registro::where('status', 'EM_ANDAMENTO')->count(),
            'resolvido'    => Registro::where('status', 'RESOLVIDO')->count(),
            'total'        => Registro::count(),
        ];

        return view('admin.dashboard', compact('totais'));
    }

    public function lista()
    {
        $registros = Registro::with('fotos', 'usuario')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.lista', compact('registros'));
    }

    public function mapa()
    {
        $registros = Registro::with('fotos')
            ->get(['id', 'titulo', 'endereco_texto', 'lat', 'lng', 'status']);

        return view('admin.mapa', compact('registros'));
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

        $registro->status = $request->status;
        $registro->save();

        LogStatus::create([
            'id_registro'     => $registro->id,
            'status_anterior' => $statusAnterior,
            'status_novo'     => $request->status,
            'id_admin'        => auth()->id(),
        ]);

        return redirect()->back()->with('sucesso', 'Status atualizado com sucesso!');
    }
}
