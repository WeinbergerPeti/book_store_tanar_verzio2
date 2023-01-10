<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        return view("FileUpload");
    }

    public function store(Request $request)
    {
        $request->validate(
        [
            "file"=> "required|mimes:txt,pdf,xlx,csv|max:2048",
        ]);
    }
}
