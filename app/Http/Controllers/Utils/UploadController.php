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
        $name = $name == '' ?
            Random::generate(5, 'a-z') :
            str_replace(' ', '_', $name);

        $image = $request->get('image');
        $image = str_replace('data:image/webp;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $image_directory = '/uploads/image/avatars/' . $name . '-' . Random::generate() . '.webp';

        File::put(base_path('public') . $image_directory, base64_decode($image));

        return response()->json([
            'status' => true,
            'image' => 'https://static.jobo.uz' . $image_directory
        ]);
    }
}
