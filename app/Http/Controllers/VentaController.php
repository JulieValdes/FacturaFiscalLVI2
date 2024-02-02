<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Venta;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $k_empresa_usuario = User::join('usrs_empresas', 'users.id', '=', 'usrs_empresas.k_user')
            ->where('users.id', '=', auth()->user()->id)
            ->select('usrs_empresas.k_empresa')
            ->first();

        /*$ventas = DB::table('ventas')
            ->join('mis_datos', 'ventas.k_empresa', '=', 'mis_datos.k_empresa')
            ->where('ventas.k_empresa', '=', $k_empresa_usuario->k_empresa)
            ->select('ventas.*')
            ->get();*/

        $ventas = DB::table('ventas')
            ->join('mis_datos', 'ventas.k_empresa', '=', 'mis_datos.k_empresa')
            ->join('sujetos', 'ventas.k_sujeto', '=', 'sujetos.k_sujetos')
            ->where('ventas.k_empresa', '=', $k_empresa_usuario->k_empresa)
            ->where('sujetos.k_empresa', '=', $k_empresa_usuario->k_empresa)
            ->select('ventas.*', 'sujetos.*')
            ->get();

        $sujetos = DB::table('sujetos')
            ->where('sujetos.k_empresa', '=', $k_empresa_usuario->k_empresa)
            ->get();

        return Inertia::render('Ventas/Index' , ['ventas' => $ventas, 'sujetos' =>$sujetos]);
    }

    public function store(Request $request){
        $k_empresa_usuario = User::join('usrs_empresas', 'users.id', '=', 'usrs_empresas.k_user')
            ->where('users.id', '=', auth()->user()->id)
            ->select('usrs_empresas.k_empresa')
            ->first();
        
        $request->validate([
            'k_empresa' => 'required',
            'k_sujeto' => 'required',
            //'venta_fecha' => 'required',
            'venta_subtotal' => 'required',
            'venta_iva' => 'required',
            'venta_total' => 'required',
            'venta_formapago' => 'required',
            'venta_serie' => 'required',
            'venta_tipo' => 'required',
            'venta_metodo' => 'required',
            'venta_moneda' => 'required',
            //'venta_uso_cfdi' => 'required'    
        ]);

        $ultimaVenta = Venta::where('k_empresa', $request->k_empresa)->max('k_venta');
        $venta = new Venta();
        if ($ultimaVenta !== null) {
            $venta->k_venta = $ultimaVenta + 1;
        }
        $venta->fill($request->all());
        if ($venta->save())
        {
            return redirect('ventas');
        }
    }

    public function update(Request $request, $k_venta)
    {
        $k_empresa_usuario = User::join('usrs_empresas', 'users.id', '=', 'usrs_empresas.k_user')
            ->where('users.id', '=', auth()->user()->id)
            ->select('usrs_empresas.k_empresa')
            ->first();

        $request->validate([
            'k_sujeto' => 'required',
            //'venta_fecha' => 'required',
            'venta_subtotal' => 'required',
            'venta_iva' => 'required',
            'venta_total' => 'required',
            'venta_formapago' => 'required',
            'venta_serie' => 'required',
            'venta_tipo' => 'required',
            'venta_metodo' => 'required',
            'venta_moneda' => 'required',
            //'venta_uso_cfdi' => 'required'    
        ]);

        $result = Venta::where('k_venta', $k_venta)
               ->where('k_empresa', $k_empresa_usuario)
               ->where('k_sujeto', $request->k_sujeto)
               ->update($request->all());

        if ($result) {
            return redirect('ventas');
        }
    }

    public function destroy(Int $k_venta, Int $k_empresa)
    {
        $result = Venta::where('k_venta', $k_venta)->where('k_empresa', $k_empresa)->delete();
        if ($result) {
            return redirect('ventas');
        }
    }
}
