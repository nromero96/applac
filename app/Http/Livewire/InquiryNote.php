<?php

namespace App\Http\Livewire;

use App\Models\Quotation;
use App\Models\QuotationAttachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
                'attachments' => 'required',
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
        $this->attachment_form['user_id'] = auth()->user()->id;

        $files_array = [];
        foreach ($this->attachments ?? [] as $attach) {
            $filename = uniqid() . '_' . $attach->getClientOriginalName();
            $attach->storeAs('public/uploads/inquiry_notes', $filename);
            $files_array[] = $filename;
        }

        $this->attachment_form['file_paths'] = $files_array;
        $this->quotation->attachments()->create($this->attachment_form);
        $this->reset('show_modal_add', 'attachment_form', 'attachments', 'attachments_added');
        $this->quotation->refresh();
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
            // delete files
            foreach ($this->note_to_delete->file_paths ?? [] as $file) {
                $filePath = 'public/uploads/inquiry_notes/' . $file;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            $this->note_to_delete->delete();
            $this->reset('note_to_delete', 'show_modal_delete');
            $this->quotation->refresh();
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
