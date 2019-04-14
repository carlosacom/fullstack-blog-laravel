<?php


namespace App\Helpers;

use Illuminate\Http\Request;

class UploadImage {
    protected $disk;
    public function __construct() {
        $this->disk = 'images';
    }
    public function upload(Request $request) {
        $image = $request->file('file0');
        if ($image) {
            $image_path = time().str_replace(' ', '-', $image->getClientOriginalName());
            \Storage::disk('images')->put($image_path, \File::get($image));
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
}