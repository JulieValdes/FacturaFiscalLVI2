<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {

        $ventas = DB::table('ventas')
            ->join('mis_datos', 'ventas.k_empresa', '=', 'mis_datos.k_empresa')
            ->join('sujetos', 'ventas.k_sujeto', '=', 'sujetos.k_sujetos')
            ->where('ventas.k_empresa', '=', Session()->get('SelectedEnterprise'))
            ->where('sujetos.k_empresa', '=', Session()->get('SelectedEnterprise'))
            ->select('ventas.*', 'sujetos.*')
            ->orderBy('ventas.venta_fecha', 'desc')
            ->paginate(10);

        $sujetos = DB::table('sujetos')
            ->where('sujetos.k_empresa', '=', Session()->get('SelectedEnterprise'))
            ->get();
        

        $series = DB::table('series')
            ->join('mis_datos', 'series.k_empresa', '=', 'mis_datos.k_empresa')
            ->where('series.k_empresa', '=', Session()->get('SelectedEnterprise'))
            ->select('series.serie')
            ->get();

        
        return Inertia::render('Ventas/Index' , ['ventas' => $ventas, 'sujetos' => $sujetos, 'series' => $series]);
    }

    public function store(Request $request){
        DD($request);
        
        $k_empresa = Session()->get('SelectedEnterprise');
        
        $request->validate([
            'k_sujeto' => 'required',
            'venta_subtotal' => 'required',
            'venta_iva' => 'required',
            'venta_total' => 'required',
            'venta_formapago' => 'required',
            'venta_serie' => 'required',
            'venta_tipo' => 'required',
            'venta_metodo' => 'required',
            'venta_moneda' => 'required' 
        ]);

        $ultimaVenta = Venta::where('k_empresa', $request->k_empresa)->max('k_venta');
        $venta = new Venta();
        if ($ultimaVenta !== null) {
            $venta->k_venta = $ultimaVenta + 1;
        }
        $venta->fill($request->all());
        $venta->k_empresa = $k_empresa;

        dd($k_empresa);
        if ($venta->save())
        {
            return redirect('Ventas/Modificar');
        }
    }

    public function update(Request $request, $k_venta)
    {

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
               ->where('k_empresa', Session()->get('SelectedEnterprise'))
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
