<?php


namespace App\Helpers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadImage {
    protected $disk;
    public function __construct() {
        $this->disk = 'images';
    }

    public function upload(Request $request) {
        $image = $request->file('file0');
        $validate = \Validator::make($request->all(), array(
            'file0' => 'required|image|mimes:jpg,png,jpeg'
        ));
        if ($image && !$validate->fails()) {
            $image_path = time().str_replace(' ', '-', $image->getClientOriginalName());
            \Storage::disk($this->disk)->put($image_path, \File::get($image));
            $data = array(
                'success' => true,
                'image' => $image_path
            );
        } else {
            $data = array(
                'success' => false
            );
        }
        return $data;
    }
    public function getImage(String $filename) {
        return (\Storage::disk($this->disk)->exists($filename)) ? new Response(\Storage::disk($this->disk)->get($filename), 200) : new Response (json_encode(['message' => 'no existe la imagen'] ),404) ;
    }
}