<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\JuradoRequest;
use App\Models\Jurado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JuradoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Jurado::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(JuradoRequest $request)
    {
        $new_jurado= new Jurado();
        $new_jurado->persona_id = $request->persona_id;
        $new_jurado->mesa_id = $request->mesa_id;
        $new_jurado->save();
        return response()->json(['ok'=>true],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jurado= Jurado::findOrFail($id);
        return $jurado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,JuradoRequest  $request)
    {

        $jurado= Jurado::findOrFail($id);
        $jurado->persona_id = $request->get('persona_id');
        $jurado->mesa_id = $request->get('mesa_id');

        $jurado->save();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jurado=Jurado::findOrFail($id);
        $jurado->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }
}
