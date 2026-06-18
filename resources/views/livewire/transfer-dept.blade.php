<div 
    class="j-modal" 
    x-data="{
        showModal: @entangle('showModal').defer,
    }"
    x-show="showModal"
    @open-transfer-modal.window="showModal = true"
>
    <form class="j-modal__content" wire:submit.prevent="save()">
        <button class="__close" type="button" wire:click="close_modal()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <h1>
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M28.3333 1.66699L35 8.33366L28.3333 15.0003" stroke="#1877F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 18.333V14.9997C5 13.2316 5.70238 11.5359 6.95262 10.2856C8.20286 9.03539 9.89856 8.33301 11.6667 8.33301H35" stroke="#1877F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.6667 38.3333L5 31.6667L11.6667 25" stroke="#1877F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M35 21.667V25.0003C35 26.7684 34.2976 28.4641 33.0474 29.7144C31.7971 30.9646 30.1014 31.667 28.3333 31.667H5" stroke="#1877F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ __('Transfer Department') }}
        </h1>
        <div class="mb-4">
            <label class="form-label">Member</label>
            <select wire:model.defer="assignedUserId" class="form-select" id="assignedUserId">
                <option value="auto">Auto-assign (between departments)</option>
                @foreach ($user_sales as $dpto => $users)
                    <optgroup label="{{ $dpto }} Dept.">
                        @foreach ($users as $user)
                            <option value="{{ $user['id'] }}">{{ $user['name'] }} {{ $user['lastname'] }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('assignedUserId')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="form-label">Notes (Optional)</label>
            <textarea rows="4" wire:model.defer="notes" class="form-control"></textarea>
            @error('notes')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="j-modal__actions">
            <button type="submit" class="btn__primary">{{ __('Save') }}</button>
            <button type="button" class="btn__secondary" wire:click="close_modal()">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
