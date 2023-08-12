<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Helpers\FileUpload;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class Api_UsersController extends Controller
{

    public function index(Request $request)
    {
        $data = User::all();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data, true);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    public function store(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|between:6,255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
        //    'active' => 'required|between:0,1',
        ]);
        // dd($request->image);
        if($validator->passes()){
            if(isset($request->image)){
                $decodedImage = base64_decode($request->image);
                $imagename = time().'-'.$request->username.'.jpg';


                file_put_contents(storage_path('app/public/images').'/thumbnail/'. $imagename, $decodedImage);
            }else{
                $imagename = null;
            }


            // dd( $imagename);
            // $dimensions = [array('300', '300', 'thumbnail')];
            try {
              //  $img = isset($request->image) && !empty($request->image) ? FileUpload::uploadImage('image', $dimensions) : NULL;

                $user = User::create([
                    'name' => ucwords($request['name']),
                    'image' =>  $imagename,
                    'password' => Hash::make($request['password']),
                    'email' => $request->email,
                    'username' => $request->username
                ]);

                $data = User::where('id', '=', $user->id)->get();

                if ($data) {
                    return ApiFormatter::createApi(200, 'Success', $data);
                } else {
                    return ApiFormatter::createApi(200, 'Failed');
                }
            } catch (Exception $error) {
                return ApiFormatter::createApi(200, 'Failed');
            }
        }else{
            return ApiFormatter::createApi(200, $validator->errors()->all());
        }

    }


    public function show($id)
    {
        $data[] =  User::findOrFail($id);

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data, true);
        } else {
            return ApiFormatter::createApi(400, 'Failed', null, false);
        }
    }



    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            // // 'password' => 'required|between:6,255',
            // 'email' => 'required',
            // 'username' => 'required',
        //    'active' => 'required|between:0,1',
        ]);
        if($validator->passes()){
            try {
                $users = User::findOrFail($request->id);

                $users->update([
                    'name' => ucwords($request['name']),
                    'email' => $request->email,
                    'username' => $request->username
                ]);

                $data = User::where('id', '=', $users->id)->get();

                if ($data) {
                    return ApiFormatter::createApi(200, 'Success', $data);
                } else {
                    return ApiFormatter::createApi(200, 'Failed');
                }
            } catch (Exception $error) {
                return ApiFormatter::createApi(200, $request['name']);
            }
        }else{
            return ApiFormatter::createApi(200, 'Tai');
        }

    }

    // public function destroy($id)
    // {
    //   $data = User::find($id);
    //   $response = response()->json($this->responseDelete(true));
    //   if ($data->delete()) {
    //     File::delete(["images/original/$data->image", "images/thumbnail/$data->image"]);
    //     $response = response()->json($this->responseDelete(true));
    //   }
    //   return $response;
    // }


    public function destroy($id)
    {
         try {
            $user = User::findOrFail($id);
            File::delete( (storage_path('app/public/images').'/thumbnail/' . $user->image));
           if ( $user->delete()) {
                if ($user->delete()) {
                    return ApiFormatter::createApi(200, 'Success Destory data', null, true);
                }else{
                    return ApiFormatter::createApi(400, 'Failed', '');
                }
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'Failed');
        }




    }
}
