<div>
    <div class="card py-2 px-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-1">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_13360_351)"><path d="M7.33325 2.66675H2.66659C2.31296 2.66675 1.97382 2.80722 1.72378 3.05727C1.47373 3.30732 1.33325 3.64646 1.33325 4.00008V13.3334C1.33325 13.687 1.47373 14.0262 1.72378 14.2762C1.97382 14.5263 2.31296 14.6667 2.66659 14.6667H11.9999C12.3535 14.6667 12.6927 14.5263 12.9427 14.2762C13.1928 14.0262 13.3333 13.687 13.3333 13.3334V8.66675" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.3333 1.66665C12.5985 1.40144 12.9582 1.25244 13.3333 1.25244C13.7083 1.25244 14.068 1.40144 14.3333 1.66665C14.5985 1.93187 14.7475 2.29158 14.7475 2.66665C14.7475 3.04173 14.5985 3.40144 14.3333 3.66665L7.99992 9.99999L5.33325 10.6667L5.99992 7.99999L12.3333 1.66665Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_13360_351"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>
                <label class="fw-bold mb-0">{{ __('Notes & Docs') }}:</label>
            </div>
            <button type="button" class="notes__btn" wire:click="add_note()">Add new</button>
        </div>

        @if (!isset($quotation->attachments) || $quotation->attachments->count() == 0)
            <div class="notes__empty">
                - No notes -
            </div>
        @else
            <div class="notes__list">
                @foreach ($quotation->attachments->reverse() as $attachment)
                    <div
                        wire:key="attachment-{{ $attachment->id }}"
                        class="notes__list__item"
                        x-data="{
                            expanded: false,
                            text: @js($attachment->description),
                            limit: 100,
                            get shortText() {
                                return this.text.substring(0, this.limit) + '...';
                            }
                        }"
                    >
                        <div class="notes__list__item__text">
                            <p>
                                <span x-text="expanded || text.length <= limit ? text : shortText"></span>
                                <button
                                    x-show="text.length > limit"
                                    @click="expanded = !expanded"
                                    x-text="expanded ? 'Show less' : 'Show more'"
                                    x-cloak
                                ></button>
                            </p>
                            <button type="button" class="__delete" wire:click="remove_note({{ $attachment }})">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.5 3H2.5H10.5" stroke="#595959" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.5 3V10C9.5 10.2652 9.39464 10.5196 9.20711 10.7071C9.01957 10.8946 8.76522 11 8.5 11H3.5C3.23478 11 2.98043 10.8946 2.79289 10.7071C2.60536 10.5196 2.5 10.2652 2.5 10V3M4 3V2C4 1.73478 4.10536 1.48043 4.29289 1.29289C4.48043 1.10536 4.73478 1 5 1H7C7.26522 1 7.51957 1.10536 7.70711 1.29289C7.89464 1.48043 8 1.73478 8 2V3" stroke="#595959" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 5.5V8.5" stroke="#595959" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 5.5V8.5" stroke="#595959" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>

                        @if (!empty($attachment->file_paths))
                            <ul class="notes__list__item__files">
                                @foreach ($attachment->file_paths as $file)
                                    <li>
                                        <a href="{{ asset('storage/uploads/inquiry_notes') . '/' . $file }}" target="_blank">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.6665 1.33325H3.99984C3.64622 1.33325 3.30708 1.47373 3.05703 1.72378C2.80698 1.97382 2.6665 2.31296 2.6665 2.66659V13.3333C2.6665 13.6869 2.80698 14.026 3.05703 14.2761C3.30708 14.5261 3.64622 14.6666 3.99984 14.6666H11.9998C12.3535 14.6666 12.6926 14.5261 12.9426 14.2761C13.1927 14.026 13.3332 13.6869 13.3332 13.3333V5.99992L8.6665 1.33325Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.6665 1.33325V5.99992H13.3332" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ \Illuminate\Support\Str::after($file, '_') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="notes__list__item__obs">
                            <div class="__important">
                                @if ($attachment->is_important)
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.14489 1.9301L0.909888 9.0001C0.822572 9.15131 0.776372 9.32275 0.775883 9.49736C0.775394 9.67197 0.820634 9.84367 0.907102 9.99537C0.993569 10.1471 1.11825 10.2735 1.26874 10.362C1.41923 10.4506 1.59029 10.4982 1.76489 10.5001H10.2349C10.4095 10.4982 10.5805 10.4506 10.731 10.362C10.8815 10.2735 11.0062 10.1471 11.0927 9.99537C11.1791 9.84367 11.2244 9.67197 11.2239 9.49736C11.2234 9.32275 11.1772 9.15131 11.0899 9.0001L6.85489 1.9301C6.76575 1.78316 6.64025 1.66166 6.49049 1.57734C6.34072 1.49303 6.17176 1.44873 5.99989 1.44873C5.82802 1.44873 5.65905 1.49303 5.50929 1.57734C5.35953 1.66166 5.23402 1.78316 5.14489 1.9301Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 4.5V6.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 8.5H6.005" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Important
                                @endif
                            </div>
                            <div class="__author">
                                <span class="author__name">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 10.5V9.5C10 8.96957 9.78929 8.46086 9.41421 8.08579C9.03914 7.71071 8.53043 7.5 8 7.5H4C3.46957 7.5 2.96086 7.71071 2.58579 8.08579C2.21071 8.46086 2 8.96957 2 9.5V10.5" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 5.5C7.10457 5.5 8 4.60457 8 3.5C8 2.39543 7.10457 1.5 6 1.5C4.89543 1.5 4 2.39543 4 3.5C4 4.60457 4.89543 5.5 6 5.5Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ $attachment->user->name }}
                                </span>
                                <span class="__author__date">- {{ $attachment->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    @if ($show_modal_add)
        <div class="box__modal">
            <div class="box__modal__content notes__modal__add">
                <button type="button" class="box__modal__close" wire:click="cancel_save_note()">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div class="d-flex align-items-center justify-content-between gap-4 mb-4">
                    <h4 class="d-flex align-items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.49998C18.8978 2.10216 19.4374 1.87866 20 1.87866C20.5626 1.87866 21.1022 2.10216 21.5 2.49998C21.8978 2.89781 22.1213 3.43737 22.1213 3.99998C22.1213 4.56259 21.8978 5.10216 21.5 5.49998L12 15L8 16L9 12L18.5 2.49998Z" stroke="#0A6AB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Add Notes & Docs
                    </h4>
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model.defer="attachment_form.is_important">
                        <div class="form-check-label">Mark as important</div>
                    </label>
                </div>

                <label class="form-label d-flex align-items-center justify-content-between">
                    Notes
                </label>
                <div class="col-md-12 flex-fill">
                    <div class="form-control newinquiry__descr">
                        <div class="__textarea">
                            <textarea rows="10" wire:model.defer="attachment_form.description" class=""></textarea>
                            @error('attachment_form.description') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="newinquiry__attachments" wire:loading.class="__loading" wire:target="attach_toggleDropped">
                            <div
                                class="newinquiry__attachments__drop {{ $attach_dropping ? '__dropping' : '' }}"
                                wire:dragover="attach_toggleDropping(true)"
                                wire:dragleave="attach_toggleDropping(false)"
                                wire:drop="attach_toggleDropped(false)"
                            >
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.5 10V12.6667C14.5 13.0203 14.3595 13.3594 14.1095 13.6095C13.8594 13.8595 13.5203 14 13.1667 14H3.83333C3.47971 14 3.14057 13.8595 2.89052 13.6095C2.64048 13.3594 2.5 13.0203 2.5 12.6667V10" stroke="#1877F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.8333 5.33333L8.49996 2L5.16663 5.33333" stroke="#1877F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.5 2V10" stroke="#1877F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <input type="file" multiple wire:model="attachments_added" accept=".eml, .pdf, .doc, .docx, .xls, .xlsx, .jpg, .png, .jpeg">
                                <p style="margin-bottom: 0">Drag and drop your files here or <span>click to browse</span></p>
                            </div>
                            @if (sizeof($attachments) > 0)
                                <ul class="newinquiry__attachments__list">
                                    @foreach ($attachments as $index => $attach)
                                        <li>
                                            <div>
                                                <span>{{ $attach->getClientOriginalName() }}</span>
                                                <small>{{ $this->formatSizeAttachment($attach->getSize()) }}</small>
                                            </div>
                                            <button type="button" wire:click="attach_remove({{ $index }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(81, 82, 100, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @error('attachments.*') <span class='text-danger'>{{ $message }}</span> @enderror
                    </div>
                </div>
                <ul class="box__modal__actions mt-2">
                    <li><button type="button" wire:click="save_note()" class="btn__primary">Save</button></li>
                    <li><button type="button" wire:click="cancel_save_note()" class="btn__secondary">Cancel</button></li>
                </ul>
            </div>
        </div>
    @endif

    @if ($show_modal_delete)
        <div class="box__modal">
            <div class="box__modal__content">
                <button type="button" class="box__modal__close" wire:click="cancel_delete_note()">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div class="notes__modal__delete">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 10H8.33333H35" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M31.6668 9.99992V33.3333C31.6668 34.2173 31.3156 35.0652 30.6905 35.6903C30.0654 36.3154 29.2176 36.6666 28.3335 36.6666H11.6668C10.7828 36.6666 9.93493 36.3154 9.30981 35.6903C8.68469 35.0652 8.3335 34.2173 8.3335 33.3333V9.99992M13.3335 9.99992V6.66659C13.3335 5.78253 13.6847 4.93468 14.3098 4.30956C14.9349 3.68444 15.7828 3.33325 16.6668 3.33325H23.3335C24.2176 3.33325 25.0654 3.68444 25.6905 4.30956C26.3156 4.93468 26.6668 5.78253 26.6668 6.66659V9.99992" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.6665 18.3333V28.3333" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.3335 18.3333V28.3333" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <h3>Delete Note</h3>
                    <p>Are you sure you want to delete this note? <br> You can't undo this action.</p>
                    <ul class="box__modal__actions">
                        <li><button type="button" wire:click="cancel_delete_note()" class="btn__secondary">Cancel</button></li>
                        <li><button type="button" wire:click="delete_note()" class="btn__primary">Delete</button></li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>


