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
            $dataRequest = array_map('trim',$request->all());
            $validate = $this->validateRequest($dataRequest, null, true, true, true);
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
                'response' => array('errors' => 'hubo un error en el servidor: '.$e->getMessage())
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
        return response()->json($response['response'],$response['status']);
    }
    public function update(Request $request) {
        $dataUser = $request->header('DataUser');
        $dataRequest = array_map('trim',$request->all());
        $validate = $this->validateRequest($dataRequest, $dataUser->sub, null, true, true);
        if(!$validate->fails()) {
            unset($dataRequest['id']);
            unset($dataRequest['role']);
            unset($dataRequest['password']);
            unset($dataRequest['created_at']);
            unset($dataRequest['remenber_token']);
            $user = User::find($dataUser->sub)->update($dataRequest);
            $response = array(
                'status' => 200,
                'response' => array(
                    'user' => $dataUser,
                    'changes' => $dataRequest
                )
            );
        } else {
            $response = array(
                'status' => 400,
                'response' => array('errors' => $validate->errors())
            );
        }

        return response()->json($response['response'],$response['status']);
    }
    public function upload(Request $request) {
        $upload = new \UploadImage();
        $responseImage = $upload->upload($request);
        if ($responseImage['success']) {
            $data = array(
                'status' => 200,
                'response' => array('response' => 'algo')
            );
        } else {
            $data = array(
                'status' => 400,
                'response' => array('errors' => 'Imagen no se subió al servidor')
            );
        }
        return response()->json($data['response'],$data['status']);
    }

    public function validateRequest(Array $array, Int $id = null, $password = null, $name = null, $surname = null ) {
        $rules = array(
            'name'      => ($name) ?'required|string|min:3|max:255' : 'nulleable',
            'email'     => ($id) ? 'required|email|min:3|max:255|unique:users,email,'.$id :  'required|email|min:3|max:255|unique:users',
            'surname'   => ($surname)? 'required|string|min:3|max:255': 'nulleable',
            'password'  => ($password)? 'required|string|min:8|max:255' : 'nulleable',
        );
        $validate = \Validator::make($array, $rules);
        return $validate;
    }
}
