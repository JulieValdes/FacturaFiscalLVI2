<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Articulo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    public function index()
    {
        $k_empresa_usuario = User::join('usrs_empresas', 'users.id', '=', 'usrs_empresas.k_user')
            ->where('users.id', '=', auth()->user()->id)
            ->select('usrs_empresas.k_empresa')
            ->first();
        
        $articulos = DB::table('articulos')
            ->join('mis_datos', 'articulos.k_empresa', '=', 'mis_datos.k_empresa')
            ->where('articulos.k_empresa', '=', $k_empresa_usuario->k_empresa)
            ->select('articulos.*')
            ->get();

        return Inertia::render('Articulos/Index', ['articulos' => $articulos]);
    }


    public function store(Request $request){
        $k_empresa_usuario = User::join('usrs_empresas', 'users.id', '=', 'usrs_empresas.k_user')
            ->where('users.id', '=', auth()->user()->id)
            ->select('usrs_empresas.k_empresa')
            ->first();
        
        $request->validate([
            'articulo_nombre' => 'required',
            'articulo_unidad' => 'required',    
            'articulo_precio' => 'required',
            'articulo_poriva' => 'required',
            'articulo_codigo_sat' => 'required',
            'articulo_unidad_sat' => 'required'
        ]);

        $ultimoKArticulo = DB::table('articulos')
            ->select('k_articulo')
            ->where('k_empresa', $k_empresa_usuario->k_empresa)
            ->orderByRaw('CAST(k_articulo AS UNSIGNED) DESC')
            ->limit(1)
            ->value('k_articulo');
        
        $nuevoKArticulo = $ultimoKArticulo !== null ? (int) $ultimoKArticulo + 1 : 1;
        $articulo = new Articulo();
        $articulo->fill($request->all());
        $articulo->k_articulo = $nuevoKArticulo;
        $articulo->k_empresa = $k_empresa_usuario ? $k_empresa_usuario->k_empresa : null;  
        if($articulo->save()){
            return redirect('articulos');
        }
    }

    public function update(Request $request,$id){
        $request->validate([
            'k_empresa' => 'required',
            'articulo_nombre' => 'required',
            'articulo_unidad' => 'required',    
            'articulo_precio' => 'required',
            'articulo_poriva' => 'required',
            'articulo_codigo_sat' => 'required',
            'articulo_unidad_sat' => 'required'
        ]); 
        
        $result = Articulo::where('k_articulo', $id)
                ->where('k_empresa', $request->k_empresa)
                ->update($request->all());

        if($result){
            return redirect('articulos');
        }
    }

    public function destroy(Int $k_articulo, Int $k_empresa){ 
        $result = Articulo::where('k_articulo', $k_articulo)->where('k_empresa', $k_empresa)->delete();
        if($result){
            return redirect('articulos');
        }
    }

}