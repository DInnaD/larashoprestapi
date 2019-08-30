<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Faundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        //
        return response()->json(['success' => User::all()]);
    }
    public function store(Request $request)
    {
        //
        $user= User::create($request->all());
        return response()->json($user, 201);
    }
    public function show(User $user)
    {
        //
        return response()->json($user->load('books', 'magazines', 'purchases', 'orders'), 200);
    }
    public function update(Request $request, User $user)
    {
        //
        $user->update($request->all());
        return response()->json($user);
    }
    public function destroy(Request $request,User $user)
    {
        //
        return response()->json(['success' => $user->delete()], 200);
    }
    public function toggleAdmin($id)
    {
        $user = User::find($id);
        $user->getSwitcherAdminAttribute();

        return redirect()->back();
    }
    public function toggleBan($id)
    {
        $user = User::find($id);
        $user->getSwitcherBanAttribute();

        return redirect()->back();
    }
}
