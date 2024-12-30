<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(50);
        $users->withPath('/users');
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate
        $request->validate([
            'name'      => 'required|min:3',
            'email'     => 'required|email|unique:users,email',
            'type'      => 'required|min:3',
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->password = $request->password;
        $user->save();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'      => 'required|min:3',
            'email'     => 'required|min:10',
        ]);

        $user = User::find($id);

        //update
        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
        ]);
    }

    /**
     * Update password users.
     */
    public function update_password(Request $request, string $id)
    {

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find($id);

        //update
        $user->update([
            'password' => Hash::make($request->string('password')),
        ]);

        return response()->json($user);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $name = $user->name;
        $user->delete();
        return response()->json([
            'message' => 'User ' . $name . ' berhasil dihapus'
        ]);
    }

    public function show_user(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(), // Mendapatkan daftar role
        ]);
    }

    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Update avatar users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    /******  73d7fd5c-63f9-48c3-b089-786e80956a9f  *******/
    public function update_avatar(Request $request, string $id)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::find($id);

        //hapus avatar sebelumnya
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        //upload di folder avatar
        $avatarPath = $request->file('avatar')->store('avatar/' . date('Y/m'), 'public');

        $user->avatar = $avatarPath;
        $user->save();

        return response()->json([
            'avatar' => $avatarPath
        ]);
    }
}
