<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use App\User;
use Illuminate\Support\Facades\Hash;

class JwtAuth {
      private $key;
      public function __construct() {
            $this->key = 'Clave-secreta-12324-53553535343123123';
      }
      public function signup(String $email, String $password)
      {
            $user = User::where([
                  ['email', $email],
            ])->first();
            if ($user) {
                  if (Hash::check($password,$user->password)) {
                        $dataToken = array(
                              'sub' => $user->id,
                              'email' => $user->email,
                              'name' => $user->name,
                              'surname' => $user->surname,
                              'role' => $user->role->name,
                              'iat' => time(),
                              'exp' => time() + (60 * 60 * 24 * 7)
                        );
                        $token = JWT::encode($dataToken, $this->key, 'HS256');
                        $response= array(
                              'status' => 200,
                              'response' => array('user' => $dataToken, 'token' => $token),
                        );
                  } else {
                        $response = array(
                              'status' => 404,
                              'response' => array('error' => 'Usuario o contraseña incorrecto'),
                        );
                  }
            } else {
                  $response = array(
                        'status' => 404,
                        'response' => array('error' => 'Usuario o contraseña incorrecto'),
                  );
            }
            return $response;
      }
      public function checkToken(String $token)
      {
            try {
                  $decoded = JWT::decode($token, $this->key, ['HS256']);
                  $response = ($decoded) ? $decoded : false;
            } catch(\UnexpectedValueException $e) {
                  $response = false;
            } catch(\DomainException $e) {
                  $response = false;
            }
            return $response;
      }
}