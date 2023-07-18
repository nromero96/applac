<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;

class UploadController extends Controller
{
    public function store(Request $request){

        //save document_one or document_two or document_three
        if($request->hasFile('document_one')){
            $file = $request->file('document_one');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = $originalFilename.uniqid().'.'.$extension;
            $folder = uniqid().'-'.now()->timestamp;
            $file->storeAs('public/uploads/tmp/'.$folder, $filename);

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);

            return $folder;
        }

        if($request->hasFile('document_two')){
            $file = $request->file('document_two');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = $originalFilename.uniqid().'.'.$extension;
            $folder = uniqid().'-'.now()->timestamp;
            $file->storeAs('public/uploads/tmp/'.$folder, $filename);
            
            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);

            return $folder;
        }

        if($request->hasFile('document_three')){
            $file = $request->file('document_three');
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = $originalFilename.uniqid().'.'.$extension;
            $folder = uniqid().'-'.now()->timestamp;
            $file->storeAs('public/uploads/tmp/'.$folder, $filename);
            
            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);
            
            return $folder;
        }

        return '';


    }
}
