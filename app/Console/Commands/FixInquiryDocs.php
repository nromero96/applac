<?php

namespace App\Console\Commands;

use App\Models\QuotationAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FixInquiryDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:fix-docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix filenames with invalid characters and update DB JSON';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando reparación...');

        $records = QuotationAttachment::where('file_paths', 'like', '%#%')->get();

        foreach ($records as $record) {
            $paths = $record->file_paths;
            if (!is_array($paths)) continue;
            $updatedPaths = [];
            foreach ($paths as $file) {
                if (!str_contains($file, '#')) {
                    $updatedPaths[] = $file;
                    continue;
                }

                $oldPath = 'public/uploads/inquiry_notes/' . $file;

                if (!Storage::disk('public')->exists($oldPath)) {
                    $this->warn("No existe: $oldPath");
                    continue;
                }

                $info = pathinfo($file);

                $cleanName = str_replace('#', '', $info['filename']);
                $newFileName = $cleanName . '.' . $info['extension'];

                $newPath = 'public/uploads/inquiry_notes/' . $newFileName;

                Storage::disk('public')->move($oldPath, $newPath);
                $this->info("Renombrado: $file -> $newFileName");

                $updatedPaths[] = $newFileName;
            }

            // actualizar BD
            QuotationAttachment::where('id', $record->id)
                ->update([
                    'file_paths' => $updatedPaths
                ]);
        }

        $this->info('Proceso terminado ✅');
    }
}
