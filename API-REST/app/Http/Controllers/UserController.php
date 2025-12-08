<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\PartialUpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        if($users->isEmpty()) {
            return response()->json([],204);
        }

        return response()->json($users,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
      
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request->password), // Es una clase de Laravel que se usa para encriptar la contraseña en la base de dats
        ]);

        return response()->json($user,201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id); // pongo el OrFail para que si el id es null, no me mande el 200 y mande el 404.

        return response()->json($user,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
           'name'  => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 200);

    }

    public function partial(PartialUpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'password']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

         $user->update($data);

         return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id); 
        $user->delete();

        return response()->json(null, 204);
    }
}
