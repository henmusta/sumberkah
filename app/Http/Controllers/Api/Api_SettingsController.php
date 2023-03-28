<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use Exception;
use Illuminate\Http\Request;

class Api_SettingsController extends Controller
{

    public function index()
    {
        $data = Settings::all();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }


    // public function create()
    // {
    //     //
    // }

    // public function store(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'username' => 'required',
    //             'address' => 'required',
    //         ]);

    //         $mahasiswa = Mahasiswa::create([
    //             'username' => $request->username,
    //             'address' => $request->address
    //         ]);

    //         $data = Mahasiswa::where('id', '=', $mahasiswa->id)->get();

    //         if ($data) {
    //             return ApiFormatter::createApi(200, 'Success', $data);
    //         } else {
    //             return ApiFormatter::createApi(400, 'Failed');
    //         }
    //     } catch (Exception $error) {
    //         return ApiFormatter::createApi(400, 'Failed');
    //     }
    // }


    // public function show($id)
    // {
    //     $data = Mahasiswa::where('id', '=', $id)->get();

    //     if ($data) {
    //         return ApiFormatter::createApi(200, 'Success', $data);
    //     } else {
    //         return ApiFormatter::createApi(400, 'Failed');
    //     }
    // }


    // public function edit($id)
    // {
    //     //
    // }


    // public function update(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'username' => 'required',
    //             'address' => 'required',
    //         ]);


    //         $mahasiswa = Mahasiswa::findOrFail($id);

    //         $mahasiswa->update([
    //             'username' => $request->username,
    //             'address' => $request->address
    //         ]);

    //         $data = Mahasiswa::where('id', '=', $mahasiswa->id)->get();

    //         if ($data) {
    //             return ApiFormatter::createApi(200, 'Success', $data);
    //         } else {
    //             return ApiFormatter::createApi(400, 'Failed');
    //         }
    //     } catch (Exception $error) {
    //         return ApiFormatter::createApi(400, 'Failed');
    //     }
    // }


    // public function destroy($id)
    // {
    //     try {
    //         $mahasiswa = Mahasiswa::findOrFail($id);

    //         $data = $mahasiswa->delete();

    //         if ($data) {
    //             return ApiFormatter::createApi(200, 'Success Destory data');
    //         } else {
    //             return ApiFormatter::createApi(400, 'Failed');
    //         }
    //     } catch (Exception $error) {
    //         return ApiFormatter::createApi(400, 'Failed');
    //     }
    // }
}
