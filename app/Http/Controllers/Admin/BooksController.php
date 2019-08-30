<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;


class BooksController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = \App\Http\Resources\BooksCollection::make(Book::all());
        return response()->json($books,200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $book = Book::create($request->all()->validated());
        $book = Book::uploadImage($request->file('img'));
        return response()->json($book, 201);
    }
    // $day = Book::create($request->validated());
    //      return $day;
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->all());
        $book->uploadImage($request->file('img'));
        return response()->json($book, 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null, 200);
    }
    
    public function addGenre(Book $book, Genre $genre)
    {
        //dd($genre);
        $book->genres()->attach($genre->id);
        return response()->json($book->load('genres'), 200);
    }
     public function toggleSetPublished($id)
    {
        $book = Book::find($id);
        $book->toggleStatusDraft();        

        return response()->json($book,200);
    }
    public function toggleBookFormat()($id)
    {
        $book = Book::find($id);
        $book->toggleStatusBookFormat();        

        return response()->json($book,200);
    }
    public function toggleDiscontGlB($id)
    {
        $book = Book::find($id);
        $book->toggleStatusVisibleGl();        

        return response()->json($book,200);
    }  
    //admin on/off global discont 
    public function toggleVisibleGlBAll()
    {
        $user_id = \Auth::user()->id;
        $books = \App\Http\Resources\BooksCollection::make(Book::where('user_id', $user_id)->get());
        foreach($books as $book)
            {
                $book->toggleStatusVisibleGl();

        }         

        return response()->json($books,200);
        
    }
    public function toggleDiscontIdB($id)
    {
        $book = Book::find($id);
        $book->toggleStatusVisibleId();        

        return response()->json($book,200);
    }  
    //admin on/off global discont 
    public function toggleVisibleIdBAll()
    {
        $user_id = \Auth::user()->id;
        $books = \App\Http\Resources\BooksCollection::make(Book::where('user_id', $user_id)->get());
        foreach($books as $book)
            {
                $book->toggleStatusVisibleId();

        }         

        return response()->json($books,200);
        
    }
}    