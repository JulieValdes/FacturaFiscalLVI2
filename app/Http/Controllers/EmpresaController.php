<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    
    public function index()
    {
        //$empresas = Empresa::paginate(10);
        //$empresas = DB::table('mis_datos')->select('mis_datos.k_empresa, mis_datos.mis_datos_nombre');
        $empresas = Empresa::OrderBy('k_empresa', 'asc')->get();
        return Inertia::render('Empresas/Index', ['empresas' => $empresas]);
    }

    public function create()
    {
        return Inertia::render('Empresas/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'mis_datos_nombre' => 'required',
            'mis_datos_razon_social' => 'required',
            'mis_datos_rfc' => 'required',
            'mis_datos_calle' => 'required',
            'mis_datos_no_ext' => 'required',
            'mis_datos_colonia' => 'required',
            'mis_datos_localidad' => 'required',
            'mis_datos_municipio' => 'required',
            'mis_datos_estado' => 'required',
            'mis_datos_pais' => 'required',
            'mis_datos_cp' => 'required',
            'mis_datos_regimen' => 'required',
            'mis_datos_email' => 'required',
        ]);

        $ultimoKEmpresa = Empresa::max('k_empresa');
        $empresa = new Empresa();
        $id = $ultimoKEmpresa + 1;
        $empresa->k_empresa = $ultimoKEmpresa + 1;
        $empresa->fill($request->all());
        if($empresa->save()){
            return redirect('Empresas');
        }
    }

    public function edit(Int $id)
    {
        $empresa = Empresa::find($id);
        return Inertia::render('Empresas/Edit', ['empresa' => $empresa]);
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'mis_datos_nombre' => 'required',
            'mis_datos_razon_social' => 'required',
            'mis_datos_rfc' => 'required',
            'mis_datos_calle' => 'required',
            'mis_datos_no_ext' => 'required',
            'mis_datos_colonia' => 'required',
            'mis_datos_localidad' => 'required',
            'mis_datos_municipio' => 'required',
            'mis_datos_estado' => 'required',
            'mis_datos_pais' => 'required',
            'mis_datos_cp' => 'required',
            'mis_datos_regimen' => 'required',
            'mis_datos_email' => 'required',
        ]);

        $empresa->update($request->all());
    }

    public function destroy(Int $id)
    {
        $empresa = Empresa::find($id);
        if($empresa->delete()){
            return redirect('empresas/index');
        }
    }
}
