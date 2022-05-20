<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tipo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TipoRequest;

use Symfony\Component\HttpFoundation\Response;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return Tipo::all();        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoRequest $request)
    {
        return Tipo::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Tipo::find($id)){
            Tipo::find($id);
            return Tipo::find($id);  
        }else{
            return response()->json([
                'message' => 'No hay datos para mostrar'
            ], 204);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Tipo::find($id))
        {         
            $tipo = Tipo::findOrFail($id);
            $tipo->update($request->all());
        }else{
            return response()->json([
                'message' => 'No hay datos para mostrar'
            ], 204);
        }
        return $tipo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tipo  $tipo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Tipo::find($id)){
            $tipo = Tipo::find($id);
            $tipo->delete();
            response()->json([
                'message' => 'Post Deleted'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Error deleting Post'
            ], 204);
        }     
    }
}
