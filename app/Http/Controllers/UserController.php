<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index(Request $request) {
        return 'pruebas';
    }
    public function store(Request $request) {
        try {
            // dd($request->all());
            $dataRequest = array_map('trim',$request->all());
            $validate = \Validator::make($dataRequest, array(
                'name'      => 'required|string|min:3|max:255',
                'email'     => 'required|email|min:3|max:255|unique:users',
                'surname'   => 'required|string|min:3|max:255',
                'password'  => 'required|string|min:8|max:255',
            ));
            if (!$validate->fails()) {
                $user = new User();
                $user->role_id = 2;
                $user->name = $dataRequest['name'];
                $user->email = $dataRequest['email'];
                $user->surname = $dataRequest['surname'];
                $user->password = Hash::make($dataRequest['password'],['rounds' => 12]);
                $user->save();
                $response = array(
                    'status' => 200,
                    'response' => array('user' => $user),
                );
            } else {
                $response = array(
                    'status' => 400,
                    'response' => array('errors' => $validate->errors())
                );
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => 500,
                'response' => array('errors' => 'hubo un error en el servidor')
            );
        }
        return response()->json($response['response'],$response['status']);
    }
    public function login(Request $request) {
        $jwtAuth = new \JwtAuth();
        $dataRequest = array_map('trim',$request->all());
        $validate = \Validator::make($dataRequest, array(
            'email'     => 'required|string|email|exists:users',
            'password'  => 'required|string|min:8|max:255'
        ));
        if (!$validate->fails()) {
            $response = $jwtAuth->signup($dataRequest['email'], $dataRequest['password']);
        } else {
            $response = array(
                'status' => 400,
                'response' => array('errors' => $validate->errors())
            );
        }
        return response()->json($response);
    }
    public function update(Int $id,Request $request)
    {
        $user = $request->header('DataUser');
        dd($user->sub);
    }
}
