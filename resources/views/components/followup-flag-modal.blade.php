<div class="j-modal" x-show="ui_show_modal_flag">
    <form class="j-modal__content" wire:submit.prevent="save_flag()">
        <button class="__close" type="button" wire:click="close_modal_flag()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <h1>
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M31.6654 35L19.9987 26.6667L8.33203 35V8.33333C8.33203 7.44928 8.68322 6.60143 9.30834 5.97631C9.93346 5.35119 10.7813 5 11.6654 5H28.332C29.2161 5 30.0639 5.35119 30.6891 5.97631C31.3142 6.60143 31.6654 7.44928 31.6654 8.33333V35Z" stroke="#CC0000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ __('Flag Inquiry') }}
        </h1>
        <div class="mb-4">
            <label class="form-label">Priority</label>
            <select class="form-select" wire:model.defer="flag.priority">
                <option value="">Select</option>
                <option value="High Priority">High Priority</option>
                <option value="Medium Priority">Medium Priority</option>
                <option value="Low Priority">Low Priority</option>
            </select>
            @error('flag.priority')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Notes</label>
            <textarea rows="4" wire:model.defer="flag.notes" class="form-control"></textarea>
            @error('flag.notes')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="j-modal__actions">
            <button type="submit" class="btn__primary">{{ __('Save') }}</button>
            <button type="button" class="btn__secondary" wire:click="close_modal_flag()">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
