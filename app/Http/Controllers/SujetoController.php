<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sujeto;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class SujetoController extends Controller
{
    public function index()
    {
        $sujetos = Sujeto::join('mis_datos', 'sujetos.k_empresa', '=', 'mis_datos.k_empresa')->select('sujetos.*')->paginate(10);
        $empresas = Empresa::all();

        return Inertia::render('Sujetos/Index', ['sujetos' => $sujetos, 'empresas' => $empresas]);	
    }

    public function store(Request $request){
        $request->validate([
            'k_empresa' => 'required',
            'sujetos_nombre' => 'required',
            'sujetos_alias' => 'required',
            'sujetos_regimen' => 'required',
        ]);

        $ultimoSujeto = Sujeto::where('k_empresa', $request->k_empresa)->max('k_sujetos');
        $ultimoSujeto = $ultimoSujeto + 1;
        $sujeto = new Sujeto();
        $sujeto->fill($request->all());
        if($sujeto->save()){
            return redirect('Sujetos');
        }
    }

    public function update(Request $request, Sujeto $sujeto){
        $request->validate([
            'k_empresa' => 'required',
            'sujetos_nombre' => 'required',
            'sujetos_alias' => 'required',
            'sujetos_regimen' => 'required',
        ]);

        $sujeto->fill($request->all());
        if($sujeto->update()){
            return redirect('Sujetos');
        }
    }

    public function destroy(Sujeto $sujeto){
        $sujeto->delete();
        return redirect('Sujetos');
    }

   
}
