<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMeta;

class UserMetaController extends Controller
{
    public function gets(Request $request, string $user_id)
    {
        // Dapatkan usermeta berdasarkan user_id dengan kunci yang diberikan dalam permintaan 
        $keys = $request->input('meta_key');
        $result = UserMeta::getMetaValues($user_id, $keys);
        return response()->json($result);
    }

    public function saves(Request $request, string $user_id)
    {
        // update usermeta
        $metas = UserMeta::updateUserMetas($user_id, $request->input());
        return response()->json($metas);
    }
}
