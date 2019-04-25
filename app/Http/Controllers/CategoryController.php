<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtAuth',array(
            'except' => ['index','show']
        ));
    }
    public function index()
    {
        return response()->json(Category::all());
    }
    public function show(Int $id)
    {
        $category = Category::find($id);
        if ($category) {
            $response = array(
                'status' => 200,
                'response' => array('category' => $category)
            );
        } else {
            $response = array(
                'status' => 404,
                'response' => array('errors' => 'La categoria no existe')
            );
        }
        return response()->json($response['response'],$response['status']);
    }
    public function store(Request $request)
    {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                'name' => 'required|min:3|max:255|unique:categories'
            ));
            if(!$validate->fails()) {
                    $category = new Category();
                    $category->name = $dataRequest['name'];
                    $category->save();
                    $response = array(
                        'status' => 200,
                        'response' => $category
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
    public function update(Int $id, Request $request)
    {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                'name' => 'required|min:3|max:255|unique:categories,name,'.$id
            ));
            if (!$validate->fails()) {
                $category = Category::find($id);
                if ($category) {
                    $category->name = $dataRequest['name'];
                    $category->update();
                    $response = array(
                        'status' => 200,
                        'response' => array('category' => $category)
                    );
                } else {
                    $response = array(
                        'status' => 404,
                        'response' => array('errors' => 'La categoria no existe')
                    );
                }
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
}
