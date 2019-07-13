<?php

namespace arsatapi\Http\Controllers;

use arsatapi\TypeFile;
use Illuminate\Http\Request;

class TypeFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $typeFile = TypeFile::all();
         return json_encode($typeFile);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \arsatapi\TypeFile  $typeFile
     * @return \Illuminate\Http\Response
     */
    public function show(TypeFile $typeFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \arsatapi\TypeFile  $typeFile
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeFile $typeFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \arsatapi\TypeFile  $typeFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeFile $typeFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \arsatapi\TypeFile  $typeFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeFile $typeFile)
    {
        //
    }
}
