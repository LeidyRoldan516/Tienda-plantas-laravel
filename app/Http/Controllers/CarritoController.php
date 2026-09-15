<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function index(): View
    {
        $carrito = session()->get("carrito", []);
        $viewData = [];
        $viewData["items"] = Planta::whereIn('id', array_keys($carrito))->get();
        $viewData["cantidades"] = $carrito;
        $viewData["total"] = $viewData["items"]->sum(
            fn ($planta) => $planta->getPrecio() * ($carrito[$planta->getId()] ?? 0)
        );
        return view('carrito.index')->with("viewData", $viewData);
    }

    public function agregar(Request $request): RedirectResponse
    {
        $request->validate([
            "planta_id" => "required|integer|exists:plantas,id",
            "cantidad" => "required|integer|min:1",
        ]);

        $carrito = session()->get("carrito", []);
        $id = (int) $request->input("planta_id");
        $carrito[$id] = ($carrito[$id] ?? 0) + (int) $request->input("cantidad");
        session()->put("carrito", $carrito);

        return redirect()->route('carrito.index')->with("success", "Elemento agregado al carrito");
    }

    public function eliminar(Request $request): RedirectResponse
    {
        $request->validate([
            "planta_id" => "required|integer",
        ]);

        $carrito = session()->get("carrito", []);
        $id = (int) $request->input("planta_id");
        unset($carrito[$id]);
        session()->put("carrito", $carrito);

        return back()->with("success", "Elemento eliminado del carrito");
    }

    public function vaciar(): RedirectResponse
    {
        session()->forget("carrito");
        return back()->with("success", "Carrito vaciado");
    }
}
