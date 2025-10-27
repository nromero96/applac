<div class="j-modal" x-show="ui_show_modal_flag">
    <form class="j-modal__content" wire:submit.prevent="save_flag()">
        <button class="__close" type="button" wire:click="close_modal_flag()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <h1>
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M27.0717 4.88665L35.1133 12.93C35.6268 13.4435 36.0108 14.0718 36.2338 14.7629C36.4569 15.454 36.5125 16.1882 36.3961 16.905C36.2797 17.6218 35.9947 18.3007 35.5645 18.8857C35.1343 19.4708 34.5712 19.9452 33.9217 20.27L25.805 24.3283C25.5123 24.4743 25.2886 24.7292 25.1817 25.0383L22.7817 31.9817C22.6663 32.3154 22.4681 32.6144 22.2055 32.8505C21.9429 33.0866 21.6246 33.2521 21.2805 33.3314C20.9364 33.4107 20.5778 33.4012 20.2384 33.3039C19.8989 33.2066 19.5898 33.0246 19.34 32.775L14.1667 27.6017L6.76667 35H5V33.23L12.4 25.8333L7.225 20.66C6.97501 20.4102 6.79266 20.101 6.6951 19.7613C6.59755 19.4217 6.58799 19.0628 6.66733 18.7184C6.74667 18.3741 6.91229 18.0556 7.14863 17.7929C7.38497 17.5301 7.68426 17.3318 8.01833 17.2167L14.9617 14.8183C15.2708 14.7114 15.5257 14.4876 15.6717 14.195L19.73 6.07832C20.0547 5.42848 20.5292 4.86512 21.1144 4.4347C21.6996 4.00428 22.3787 3.71912 23.0957 3.60273C23.8128 3.48634 24.5472 3.54206 25.2385 3.76529C25.9298 3.98852 26.5581 4.37287 27.0717 4.88665ZM33.3467 14.6967L25.3033 6.65498C25.07 6.42148 24.7845 6.24676 24.4705 6.14523C24.1564 6.04369 23.8226 6.01824 23.4968 6.07098C23.1709 6.12371 22.8623 6.25312 22.5962 6.44855C22.3302 6.64397 22.1144 6.89981 21.9667 7.19498L17.9083 15.3133C17.4696 16.19 16.705 16.8601 15.7783 17.18L9.46333 19.3633L20.6383 30.5367L22.8183 24.2233C23.1382 23.2966 23.8083 22.532 24.685 22.0933L32.805 18.0333C33.1003 17.8857 33.3563 17.6701 33.5519 17.4041C33.7475 17.1381 33.8771 16.8295 33.93 16.5036C33.9829 16.1778 33.9576 15.844 33.8561 15.5298C33.7547 15.2157 33.5801 14.9301 33.3467 14.6967Z" fill="#CC0000"/></svg>
            {{ __('Pin Inquiry') }}
        </h1>
        @if (!$this->quotationPriority)
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
        @endif
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
