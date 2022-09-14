<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DiariosController extends Controller
{
    public function consultaDeBlueUno(Request $request){
        $start = request()->dateStart;
       if(request()->has('dateEnd') && request()->dateEnd != ''){
        $end = request()->dateEnd;
       }
       $fondostring = request()->fondo;
       $fondo = str_split($fondostring, 2);
       $user = Auth::user();

       $response = Http::timeout(60)->get('http://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php', [
        'bunit' => $user->bunit_account,
        'fondo' => $fondo[0],
        'start' => $start,
        'end' => $end,
        'id_proc' => "getDiarios"
        ]); 
        error_log($response);
        $user = Auth::user();
        $response = Http::post('http://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php', [
            'bunit' => $user->bunit_account,
            'email' => $user->email,
            'id_proc' => "getFondos"
        ]);

        $fondos = $response->json();
        return view('diarios.consultaFacturas',['fondos' => $fondos,'diarios' => $response]);
        
    }

   
}
