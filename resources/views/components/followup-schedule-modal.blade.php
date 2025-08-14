<div class="j-modal" x-show="ui_show_modal_schedule">
    <form class="j-modal__content" wire:submit.prevent="save_schedule()">
        <button class="__close" type="button" wire:click="close_modal_schedule()">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <h1>
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M30 13.3333C30 10.6812 28.9464 8.13764 27.0711 6.26228C25.1957 4.38691 22.6522 3.33334 20 3.33334C17.3478 3.33334 14.8043 4.38691 12.9289 6.26228C11.0536 8.13764 10 10.6812 10 13.3333C10 25 5 28.3333 5 28.3333H35C35 28.3333 30 25 30 13.3333Z" stroke="#0A6AB7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.8839 35C22.5908 35.5051 22.1703 35.9244 21.6642 36.2159C21.1582 36.5073 20.5845 36.6608 20.0005 36.6608C19.4166 36.6608 18.8428 36.5073 18.3368 36.2159C17.8308 35.9244 17.4102 35.5051 17.1172 35" stroke="#0A6AB7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ __('Schedule Reminder') }}
        </h1>
        <div class="mb-4 d-flex gap-4">
            <div class="flex-grow-1">
                <label class="form-label">Date *</label>
                <div wire:ignore>
                    <input type="text" class="form-control" id="schedule-date" wire:model.defer="schedule.date" placeholder="dd-mm-yyyy">
                </div>
                @error('schedule.date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex-grow-1">
                <label class="form-label">Priority</label>
                <select class="form-select" wire:model.defer="schedule.priority">
                    <option value="">Select</option>
                    <option value="High Priority">High Priority</option>
                    <option value="Medium Priority">Medium Priority</option>
                    <option value="Low Priority">Low Priority</option>
                </select>
                @error('schedule.priority')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label">Notes</label>
            <textarea rows="4" wire:model.defer="schedule.notes" class="form-control"></textarea>
            @error('schedule.notes')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="j-modal__actions">
            <button type="submit" class="btn__primary">{{ __('Save') }}</button>
            <button type="button" class="btn__secondary" wire:click="close_modal_schedule()">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            $('#schedule-date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY',
                    firstDay: 1
                }
            });
            $('#schedule-date').on('apply.daterangepicker', function(ev, picker) {
                @this.set('schedule.date', picker.startDate.format('DD-MM-YYYY'));
            });
        });
    </script>
@endpush
