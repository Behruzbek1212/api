<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlockNumberController extends Controller
{
    public function index()
    {
        $data = [
            "refresh" => 0,
            "items" => [
                ["number" => "001", "name" => "", "firstname" => "", "lastname" => "", "phone" => "947980058", "mobile" => "947980058", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
                ["number" => "002", "name" => "", "firstname" => "", "lastname" => "", "phone" => "+998947980058", "mobile" => "+998947980058", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
                ["number" => "003", "name" => "", "firstname" => "", "lastname" => "", "phone" => "+998993960990", "mobile" => "+998993960990", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
                ["number" => "004", "name" => "", "firstname" => "", "lastname" => "", "phone" => "993960990", "mobile" => "993960990", "email" => "", "address" => "", "city" => "", "state" => "", "zip" => "", "comment" => "", "presence" => 0, "starred" => 0, "info" => ""],
            ]
        ];

        return response()->json($data);
    }
}
