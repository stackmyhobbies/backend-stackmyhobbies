<?php

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-cloudinary', function () {
    // Check if the file exists
    $filePath = public_path('test.png');
    if (!file_exists($filePath)) {
        return "Error: The file 'test.png' does not exist in the public directory.";
    }

    $result = Cloudinary::uploadApi()->upload($filePath);
    return response()->json($result->getArrayCopy());
});