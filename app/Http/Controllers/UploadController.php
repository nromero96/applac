<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;

class UploadController extends Controller
{
    public function store(Request $request){

        $documentFields = ['document_one', 'document_two', 'document_three'];
        $uploadedFolder = '';

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = $originalFilename . uniqid() . '.' . $extension;
                $folder = uniqid() . '-' . now()->timestamp;
                $file->storeAs('public/uploads/tmp/' . $folder, $filename);

                TemporaryFile::create([
                    'folder' => $folder,
                    'filename' => $filename,
                ]);

                $uploadedFolder = $folder;
                break;
            }
        }

        return $uploadedFolder;



    }
}
