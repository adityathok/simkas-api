<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMeta;

class UserMetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function gets(Request $request, string $user_id)
    {
        // Dapatkan usermeta berdasarkan user_id dengan kunci yang diberikan dalam permintaan 
        $keys = $request->input('meta_key');
        $usermeta = UserMeta::where('user_id', $user_id)->whereIn('meta_key', $keys)->get();

        // Hanya mengambil meta_key dan meta_value dari setiap item usermeta
        $result = [];
        foreach ($usermeta as $key => $value) {
            $result[$value->meta_key] = $value->meta_value;
        }
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
