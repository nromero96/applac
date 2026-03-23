<?php

namespace App\Http\Livewire;

use App\Models\Quotation;
use App\Models\QuotationAttachment;
use App\Models\QuotationNote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class InquiryNote extends Component
{
    use WithFileUploads;

    public Quotation $quotation;

    // add
    public $show_modal_add = false;
    public $attachment_form = [
        'description' => '',
        'is_important' => false,
        'file_paths' => [],
    ];

    // attachments
    public $attach_dropping = false;
    public $attachments = [];
    public $attachments_added = [];

    // delete
    public $note_to_delete = null;
    public $show_modal_delete = false;

    public function render() {
        return view('livewire.inquiry-note');
    }

    /**
     * ADD
     */

    public function add_note() {
        $this->show_modal_add = true;
    }

    public function cancel_save_note() {
        $this->reset('show_modal_add', 'attachment_form', 'attachments', 'attachments_added');
        $this->resetErrorBag();
    }

    public function save_note() {
        $this->validate(
            [
                'attachments.*' => 'max:2048',
            ],
            [
                'attachments.*.max' => 'The attachments must not be greater than 2MB.'
            ],
            [
                // 'attachment_form.description' => 'description',
                'attachments.*' => 'attachments',
            ]
        );

        if ($this->attachment_form['description'] != '' || count($this->attachments) > 0) {
            $this->attachment_form['user_id'] = auth()->user()->id;

            $action = 'Note and file(s) added'; // agrega nota y archivos
            if ($this->attachment_form['description'] != '' && count($this->attachments) == 0) { // solo agrega nota
                $action = 'Note added';
            } elseif ($this->attachment_form['description'] == '' && count($this->attachments) > 0) { // solo agrega archivos
                $action = 'File(s) added';
            }

            $files_array = [];
            $files_array_show = [];
            foreach ($this->attachments ?? [] as $attach) {

                $originalName = pathinfo($attach->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $attach->getClientOriginalExtension();

                // 🔥 limpiar nombre (clave)
                $cleanName = str_replace('#', '', $originalName);

                // fallback por si queda vacío
                if (empty($cleanName)) {
                    $cleanName = 'file';
                }
                $filename = uniqid() . '_' . $cleanName . '.' . $extension;

                $attach->storeAs('public/uploads/inquiry_notes', $filename);
                $files_array[] = $filename;
                $files_array_show[] = $originalName . '.' . $extension;
            }

            $this->attachment_form['file_paths'] = $files_array;
            $this->quotation->attachments()->create($this->attachment_form);

            // log
            QuotationNote::create([
                'quotation_id' => $this->quotation->id,
                'type' => 'docs',
                'note' => json_encode([
                    'files' => $files_array_show,
                    'priority' => $this->attachment_form['is_important'],
                ]),
                'user_id' => auth()->id(),
                'action' => $action,
                'update_type' => 'added',
            ]);

            $this->reset('show_modal_add', 'attachment_form', 'attachments', 'attachments_added');
            $this->quotation->refresh();

            $this->dispatchBrowserEvent('update-activity-log');

        } else {
            $this->reset('show_modal_add', 'attachment_form', 'attachments', 'attachments_added');
        }
    }

    /**
     * REMOVE
     */

    public function remove_note(QuotationAttachment $attachment) {
        $this->note_to_delete = $attachment;
        $this->show_modal_delete = true;
    }

    public function delete_note() {
        if ($this->note_to_delete) {
            // get action
            $action = 'Note and file(s) deleted'; // quita nota y archivos
            if ($this->note_to_delete->description != '' && count($this->note_to_delete->file_paths) == 0) { // quita nota
                $action = 'Note deleted';
            } elseif ($this->note_to_delete->description == '' && count($this->note_to_delete->file_paths) > 0) { // quita files
                $action = 'File(s) deleted';
            }

            // delete files
            foreach ($this->note_to_delete->file_paths ?? [] as $file) {
                $filePath = 'public/uploads/inquiry_notes/' . $file;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            $this->note_to_delete->delete();

            // log
            QuotationNote::create([
                'quotation_id' => $this->quotation->id,
                'type' => 'docs',
                'user_id' => auth()->id(),
                'action' => $action,
                'update_type' => 'deleted',
            ]);

            $this->reset('note_to_delete', 'show_modal_delete');
            $this->quotation->refresh();
            $this->dispatchBrowserEvent('update-activity-log');
        }
    }

    public function cancel_delete_note() {
        $this->reset('note_to_delete', 'show_modal_delete');
    }

    /**
     * Attachments
     */

    public function updatedAttachmentsAdded(){
        $this->attachments = array_merge($this->attachments, $this->attachments_added);
    }

    public function formatSizeAttachment($size) {
        if ($size < 1024) {
            return $size . ' bytes';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    public function attach_toggleDropping($value) {
        $this->attach_dropping = $value;
    }

    public function attach_toggleDropped($value) {
        $this->attach_dropping = $value;
    }

    public function attach_remove($index) {
        array_splice($this->attachments, $index, 1);
    }
}
