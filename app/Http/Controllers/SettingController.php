<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keys = $request->input('keys');
        $settings = Setting::gets($keys);
        return response()->json($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $settings = Setting::sets($request->all());
        return response()->json($settings);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $key)
    {
        $setting = Setting::get($key);
        return response()->json($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $key)
    {
        $setting = Setting::set($key, $request->input('value'));
        return response()->json($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $key)
    {
        //delete
        $setting = Setting::del($key);
        return response()->json($setting);
    }
}
