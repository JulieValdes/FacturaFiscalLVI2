<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sujeto;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SujetoController extends Controller
{

    //DESPUES DE PRUEBAS ESPECIFICAR EN LA CONSULTA SI ES USUARIO O PROVEEDOR
    public function index()
    {
        // Obtén el ID de la empresa del usuario logueado
        $k_empresa_usuario = User::join('usrs_empresas', 'users.id', '=', 'usrs_empresas.k_user')
            ->where('users.id', '=', auth()->user()->id)
            ->select('usrs_empresas.k_empresa')
            ->first();


        // Obtén los sujetos que pertenecen a la empresa del usuario logueado
        $sujetos = DB::table('sujetos')
            ->join('mis_datos', 'sujetos.k_empresa', '=', 'mis_datos.k_empresa')
            ->where('sujetos.k_empresa', '=', $k_empresa_usuario->k_empresa)
            ->select('sujetos.*')
            ->get();


        return Inertia::render('Sujetos/Index', ['sujetos' => $sujetos]);    
    }

    public function store(Request $request){
        $request->validate([
            'k_empresa' => 'required',
            'sujetos_nombre' => 'required',
            'sujetos_alias' => 'required',
            'sujetos_regimen' => 'required',
        ]);

        $sujeto = new Sujeto();
        $sujeto->fill($request->all());
        if($sujeto->save()){
            return redirect('sujetos');
        }
    }

    public function update(Sujeto $sujeto){
        $request->validate([
            'k_empresa' => 'required',
            'sujetos_nombre' => 'required',
            'sujetos_alias' => 'required',
            'sujetos_regimen' => 'required',
        ]);

        $result = Sujeto::where('k_sujetos', $id)
                ->where('k_empresa', $request->k_empresa)
                ->update($request->all());

        if($result){
            return redirect('sujetos');
        }
    }

    public function destroy(Sujeto $sujeto, Empresa $empresa){
        $result = Sujeto::where('k_sujetos', $empresa)->where('k_empresa', $request->k_empresa)->delete();
        if($result){
            return redirect('sujetos');
        }
    }

   
}
