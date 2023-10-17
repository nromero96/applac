<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemporaryFile;

//log
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{

    public function store(Request $request){
        $uploadedFolders = []; // Utilizamos un arreglo para almacenar las carpetas de archivos subidos
        
        // Campos de archivo único
        $singleFileFields = ['document_one', 'document_two', 'document_three'];
    
        foreach ($singleFileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $uploadedFolders[] = $this->processFile($file);
            }
        }
    
        // Campo de archivo múltiple 'quotation_documents'
        if ($request->hasFile('quotation_documents')) {
            $files = $request->file('quotation_documents');
    
            foreach ($files as $file) {
                $uploadedFolders[] = $this->processFile($file);
            }
        }
    
        return $uploadedFolders;
    }
    
    private function processFile($file) {
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
    
        // Genera un identificador único
        $uniqueIdentifier = uniqid();
    
        // Obtiene el nombre del archivo sin la extensión
        $filenameWithoutExtension = pathinfo($originalFilename, PATHINFO_FILENAME);
    
        // Construye el nombre del archivo con el nombre y el identificador único
        $filename = $filenameWithoutExtension . '-' . $uniqueIdentifier . '.' . $extension;
    
        $folder = uniqid() . '-' . now()->timestamp;
    
        $file->storeAs('public/uploads/tmp/' . $folder, $filename);
    
        TemporaryFile::create([
            'folder' => $folder,
            'filename' => $filename,
        ]);
    
        return $folder;
    }
    


    public function deleteFile(Request $request)
    {
        $folder = $request->input('folder'); // Obtén el nombre de la carpeta del archivo a eliminar

        // Elimina el archivo del almacenamiento
        \Storage::deleteDirectory('public/uploads/tmp/' . $folder);

        // Elimina la entrada de la base de datos
        TemporaryFile::where('folder', $folder)->delete();

        return response()->json(['message' => 'Archivo eliminado con éxito']);
    }

    

}
