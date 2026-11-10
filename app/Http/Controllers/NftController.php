<?php

namespace App\Http\Controllers;

use App\Exceptions\CatalogoNftException;
use App\Interfaces\CatalogoNftInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class NftController extends Controller
{
    public function index(Request $request, CatalogoNftInterface $catalogo): Response
    {
        $pagina = $request->query->has('page') ? $request->query('page') : 1;
        $nfts = [];
        $meta = null;
        $error = null;

        if (Validator::make(['page' => $pagina], [
            'page' => ['required', 'integer', 'min:1', 'max:1000000'],
        ])->fails()) {
            $error = __('messages.nfts_pagina_invalida');

            return response()->view('nfts.index', compact('nfts', 'meta', 'error'), 422);
        }

        try {
            $resultado = $catalogo->consultar((int) $pagina);
            $nfts = $resultado['data'];
            $meta = $resultado['meta'];
        } catch (CatalogoNftException) {
            $error = __('messages.nfts_error');
        }

        return response()->view('nfts.index', compact('nfts', 'meta', 'error'));
    }
}
