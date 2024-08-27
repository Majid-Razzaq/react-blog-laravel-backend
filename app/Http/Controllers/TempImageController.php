<?php

namespace App\Http\Controllers;

use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempImageController extends Controller
{
    // Apply validation
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'image' => 'required|image',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fix errors',
                'errors' => $validator->errors(),
            ]);
        }
        // Upload image here
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imgName = time().'.'.$ext;
        // store image info in db
        $tempImage = new TempImage();
        $tempImage->name = $imgName;
        $tempImage->save();


        // move image in temp directory
        $image->move(public_path('uploads/temp'),$imgName);
        return response()->json([
            'status' => true,
            'message' => 'Image uploaded successfully',
            'image' => $tempImage,
        ]);
        
    }
}
