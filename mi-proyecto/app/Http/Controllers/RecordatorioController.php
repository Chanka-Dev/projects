<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use App\Models\Cita;
use Illuminate\Http\Request;

class RecordatorioController extends Controller
{
    public function index(Request $request)
    {
        $query = Recordatorio::with('cita.cliente');
        if ($request->has('search')) {
            $query->whereHas('cita.cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('telefono', 'like', '%' . $request->search . '%');
            })->orWhere('mensaje', 'like', '%' . $request->search . '%')
              ->orWhere('tipo', 'like', '%' . $request->search . '%');
        }
        $recordatorios = $query->paginate(10);
        return view('recordatorios.index', compact('recordatorios'));
    }

    public function create()
    {
        $citas = Cita::with('cliente')->whereDoesntHave('recordatorios')->get();
        return view('recordatorios.create', compact('citas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id|unique:recordatorios,cita_id',
            'mensaje' => 'required|string|max:255',
            'fecha_envio' => 'required|date|after_or_equal:today',
            'tipo' => 'required|string|in:email,sms,whatsapp',
            'estado' => 'required|string|in:pendiente,enviado,falido',
        ]);

        Recordatorio::create($validated);
        return redirect()->route('recordatorios.index')->with('success', 'Recordatorio registrado con éxito');
    }

    public function show(Recordatorio $recordatorio)
    {
        $recordatorio->load('cita.cliente');
        return view('recordatorios.show', compact('recordatorio'));
    }

    public function edit(Recordatorio $recordatorio)
    {
        $citas = Cita::with('cliente')->whereDoesntHave('recordatorios')->orWhere('id', $recordatorio->cita_id)->get();
        return view('recordatorios.edit', compact('recordatorio', 'citas'));
    }

    public function update(Request $request, Recordatorio $recordatorio)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id|unique:recordatorios,cita_id,' . $recordatorio->id,
            'mensaje' => 'required|string|max:255',
            'fecha_envio' => 'required|date|after_or_equal:today',
            'tipo' => 'required|string|in:email,sms,whatsapp',
            'estado' => 'required|string|in:pendiente,enviado,falido',
        ]);

        $recordatorio->update($validated);
        return redirect()->route('recordatorios.index')->with('success', 'Recordatorio actualizado con éxito');
    }

    public function destroy(Recordatorio $recordatorio)
    {
        $recordatorio->delete();
        return redirect()->route('recordatorios.index')->with('success', 'Recordatorio eliminado con éxito');
    }
}