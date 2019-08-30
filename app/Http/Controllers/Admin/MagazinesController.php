<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Magazine;
use App\Http\Requests\MagazineRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class MagazinesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Magazine::class, 'magazine');
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $magazines = \App\Http\Resources\MagazinesCollection::make(Magazine::all());
        return response()->json($magazines,200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MagazineRequest $request)
    {
        $magazine = Magazine::create($request->all()->validated());
        $magazine = Magazine::uploadImage($request->file('img'));
        return response()->json($magazine, 201);
    }    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MagazineRequest $request, Magazine $magazine)
    {
        $magazine->update($request->all());
        $magazine->uploadImage($request->file('img'));
        return response()->json($magazine, 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Magazine $magazine)
    {
        $magazine->delete();
        return response()->json(null, 200);
    }
    
    public function addGenre(Magazine $magazine, Genre $genre)
    {
        //dd($genre);
        $magazine->genres()->attach($genre->id);
        return response()->json($magazine->load('genres'), 200);
    }
    public function toggleSetPublished($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusDraft();

        return redirect()->back();
    }

    public function toggleDiscontGlM($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusVisibleGl();//dd($user->status_discont_id);

        return response()->json($magazine,200);
    }    
 
    public function toggleVisibleGlMAll()
    {
        $user_id = \Auth::user()->id;
        $magazines = \App\Http\Resources\PurchasesCollection::make(Magazine::where('user_id', $user_id)->get());
        foreach($magazines as $magazine)
            {
                $magazine->toggleStatusVisibleGl();

        } 
       return response()->json($magazines,200);
        
    }  

    public function toggleDiscontIdM($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusVisibleId();//dd($user->status_discont_id);

        return response()->json($magazine,200);
    }    
 
    public function toggleVisibleIdMAll()
    {
        $user_id = \Auth::user()->id;
        $magazines = \App\Http\Resources\MagazinesCollection::make(Magazin::where('user_id', $user_id)->get());
        foreach($magazines as $magazine)
            {
                $magazine->toggleStatusVisibleId();

        } 
       return response()->json($magazines,200));
        
    }

     public function toggleSubPrice($id)
    {
        $magazine = Magazine::find($id);
        $magazine->toggleStatusSubPrice();

        return response()->json($magazine,200);
    }
}
