<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Genre;

class GenreController extends Controller
{
    public function index()
    {
        //
        return response()->json(['success' => Genre::all()]);
    }
    public function store(Request $request)
    {
        //
        $genre = Genre::create($request->all());
        return response()->json($genre, 201);
    }
    public function show(Genre $genre)
    {
        //
        return response()->json($genre->load('books', 'magazines'), 200);//polimorph???
    }
    public function update(Request $request, Genre $genre)
    {
        //
        $genre->update($request->all());
        return response()->json($genre);
    }
    public function destroy(Request $request, Genre $genre)
    {
        //
        return response()->json(['success' => $genre->delete()], 200);
    }
}
