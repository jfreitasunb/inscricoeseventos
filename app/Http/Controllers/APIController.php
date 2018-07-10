<?php
namespace InscricoesEventosMat\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;

class APIController extends Controller
{
    public function index()
    {
        return DB::table("paises")->pluck("name","id")->all();
        
    }
    public function getStateList(Request $request)
    {
        $estados = DB::table("estados")
                    ->where("country_id",$request->country_id)
                    ->pluck("name","id_state");
        return response()->json($estados);
    }
    public function getCityList(Request $request)
    {
        $cidades = DB::table("cidades")
                    ->where("state_id",$request->state_id)
                    ->pluck("name","id");
        return response()->json($cidades);
    }
}