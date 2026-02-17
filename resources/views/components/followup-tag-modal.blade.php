<div class="j-modal" x-show="ui_show_modal_tag">
    <form class="j-modal__content" wire:submit.prevent="save_tag()">
        <button class="__close" type="button" wire:click="close_modal_tag()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <h1>
            <svg width="40" height="40" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.7267 8.93992L8.94671 13.7199C8.82288 13.8439 8.67582 13.9422 8.51396 14.0093C8.3521 14.0764 8.17859 14.111 8.00337 14.111C7.82815 14.111 7.65465 14.0764 7.49279 14.0093C7.33092 13.9422 7.18387 13.8439 7.06004 13.7199L1.33337 7.99992V1.33325H8.00004L13.7267 7.05992C13.975 7.30973 14.1144 7.64767 14.1144 7.99992C14.1144 8.35217 13.975 8.6901 13.7267 8.93992Z" stroke="#1D813A" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.66663 4.66675H4.67329" stroke="#1D813A" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ __('Tag Inquiry') }}
        </h1>
        @if (!$this->quotationPriority)
            <div class="mb-4">
                <label class="form-label">Tag</label>
                <select class="form-select" wire:model.defer="tag.priority">
                    <option value="">Select</option>
                    <option value="High Priority">High Priority</option>
                    <option value="Medium Priority">Medium Priority</option>
                    <option value="Low Priority">Low Priority</option>
                    <option value="Hot Deal">Hot Deal</option>
                    <option value="Potential Lead">Potential Lead</option>
                </select>
                @error('tag.priority')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endif
        <div class="mb-4">
            <label class="form-label">Notes</label>
            <textarea rows="4" wire:model.defer="tag.notes" class="form-control"></textarea>
            @error('tag.notes')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="j-modal__actions">
            <button type="submit" class="btn__primary">{{ __('Save') }}</button>
            <button type="button" class="btn__secondary" wire:click="close_modal_tag()">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
