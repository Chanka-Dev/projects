<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderPdfController extends Controller
{
    public function generate(Pedido $pedido)
    {
        // Cargar relaciones necesarias
        $pedido->load(['user', 'productos.producto']);

        $pdf = Pdf::loadView('pdf.order', compact('pedido'));

        return $pdf->download('pedido-' . $pedido->id . '.pdf');
    }
}
