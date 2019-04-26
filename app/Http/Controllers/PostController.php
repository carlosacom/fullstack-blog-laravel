<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\DB;

class PostController extends Controller {

    public function __construct() {
        $this->middleware('jwtAuth', array(
            'except' => ['index', 'show', 'getPostsForCategory', 'getPostsForUser']
        ));
    }

    public function index() {
        // Con DB
        // $posts = DB::table('posts')
        // ->join('users', 'users.id', '=', 'posts.user_id')
        // ->join('categories', 'categories.id', '=', 'posts.category_id')
        // ->select('users.id as user_id', 'users.name as user', 'categories.id as category_id', 'categories.name as category', 'posts.id', 'posts.title', 'posts.content', 'posts.image', 'posts.created_at')
        // ->get();
        
        // con ORM
        $posts = Post::orderBy('id','DESC')->get()->load('category')->load('user');

        return response()->json($posts);
    }

    public function show(Int $id) {
        $post = Post::find($id);
        if ($post) {
            $response = array(
                'status' => 200,
                'response' => $post->load('category')->load('user')
            );
        } else {
            $response = array(
                'status' => 404,
                'response' => array('errors' => 'El post no existe')
            );
        }
        return response()->json($response['response'], $response['status']);
    }

    public function store(Request $request) {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                        'title' => 'required|string|min:3|max:255',
                        'content' => 'required|string|min:3|max:255',
                        'category_id' => 'required|integer|exists:categories,id',
            ));
            if (!$validate->fails()) {
                $user = $request->header('DataUser');
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $dataRequest['category_id'];
                $post->title = $dataRequest['title'];
                $post->content = $dataRequest['content'];
                $post->image = $dataRequest['image'];
                $post->save();
                $response = array(
                    'status' => 200,
                    'response' => $post
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
                'response' => array('errors' => 'hubo un error en el servidor: ' . $e->getMessage())
            );
        }
        return response()->json($response['response'], $response['status']);
    }

    public function update(Int $id, Request $request) {
        try {
            $dataRequest = array_map('trim', $request->all());
            $validate = \Validator::make($dataRequest, array(
                        'title' => 'required|string|min:3|max:255',
                        'content' => 'required|string|min:3|max:255',
                        'category_id' => 'required|integer|exists:categories,id',
            ));
            if (!$validate->fails()) {
                $user = $request->header('DataUser');
                $post = Post::where(array(
                            ['user_id', $user->sub],
                            ['id', $id],
                        ))->first();
                if ($post) {
                    $post->category_id = $dataRequest['category_id'];
                    $post->title = $dataRequest['title'];
                    $post->content = $dataRequest['content'];
                    $post->update();
                    $response = array(
                        'status' => 200,
                        'response' => array('post' => $post->load('category'))
                    );
                } else {
                    $response = array(
                        'status' => 404,
                        'response' => array('errors' => 'El post no existe')
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
                'response' => array('errors' => 'hubo un error en el servidor: ' . $e->getMessage())
            );
        }
        return response()->json($response['response'], $response['status']);
    }

    public function uploadImage(Request $request) {
        $upload = new \UploadImage();
        // $user = $request->header('DataUser');
        $responseImage = $upload->upload($request);
        if ($responseImage['success']) {
            $data = array(
                'status' => 200,
                'response' => array('image' => $responseImage['image'])
            );


            /* $post = Post::where(array(
              ['user_id', $user->sub],
              ['id', $id],
              ))->first();
              if ($post) {
              $post->image = $responseImage['image'];
              $data = array(
              'status' => 200,
              'response' => array('data' => $post->load('category'))
              );
              } else {
              $data = array(
              'status' => 404,
              'response' => array('errors' => 'El post no existe')
              );
              } */
        } else {
            $data = array(
                'status' => 400,
                'response' => array('errors' => 'Imagen no se subió al servidor')
            );
        }
        return response()->json($data['response'], $data['status']);
    }

    public function destroy(Int $id, Request $request) {
        try {
            $user = $request->header('DataUser');
            $post = Post::where(array(
                        ['user_id', $user->sub],
                        ['id', $id]
                    ))->first();
            if ($post) {
                $post->delete();
                $response = array(
                    'status' => 200,
                    'response' => array('success' => true)
                );
            } else {
                $response = array(
                    'status' => 400,
                    'response' => array('errors' => 'No existe el post a eliminar')
                );
            }
        } catch (\Exception $e) {
            $response = array(
                'status' => 500,
                'response' => array('errors' => 'hubo un error en el servidor: ' . $e->getMessage())
            );
        }
        return response()->json($response['response'], $response['status']);
    }

    public function getPostsForCategory(Int $category_id) {
        return response()->json(Post::Where('category_id', $category_id)->get()->load('user'));
    }

    public function getPostsForUser(Int $user_id) {
        return response()->json(Post::Where('user_id', $user_id)->get()->load('category'));
    }

}
