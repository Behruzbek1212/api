<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => ['required']
        ]);

        $name = $request->get('name');
        $image = $request->get('image');

        $name = $name == '' ?
            Random::generate(5, 'a-z') :
            str_replace(' ', '_', $name);

        preg_match('/^data:image\/(\w+);/m', $image, $mimetype);
        $mimetype = '.' . $mimetype[1];

        $image = explode(',', $image)[1];
        $image = str_replace(' ', '+', $image);

        $image_directory =
            '/uploads/image/avatars/' . $name . '-' . Random::generate() . $mimetype;

        File::put(base_path('public') . $image_directory, base64_decode($image));

        return response()->json([
            'status' => true,
            'image' => 'https://static.jobo.uz' . $image_directory
        ]);
    }
}
