<div
    class="flag-followup text-start"
    x-data="{
        show_dropdown: false,
        ui_show_modal_flag: @entangle('ui_show_modal_flag').defer,
        ui_show_modal_schedule: @entangle('ui_show_modal_schedule').defer,
    }
">
    <div class="flag-followup__dropdown">
        @if ($isFlag)
            <button type="button" class="flag-followup__actions" @click="show_dropdown = !show_dropdown">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6654 14L7.9987 10.6667L3.33203 14V3.33333C3.33203 2.97971 3.47251 2.64057 3.72256 2.39052C3.9726 2.14048 4.31174 2 4.66536 2H11.332C11.6857 2 12.0248 2.14048 12.2748 2.39052C12.5249 2.64057 12.6654 2.97971 12.6654 3.33333V14Z" fill="#B80000" stroke="#B80000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @if ($isFlag->priority)
                    <span class="agenda__modal__item__title"><span class="__{{ $isFlag->priority_class }}">{{ $isFlag->priority }}</span></span>
                @endif
                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.5 6L8.5 10L12.5 6" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        @elseif ($isScheduled)
            <button type="button" class="flag-followup__actions" @click="show_dropdown = !show_dropdown">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5.33331C12 4.27245 11.5786 3.25503 10.8284 2.50489C10.0783 1.75474 9.06087 1.33331 8 1.33331C6.93913 1.33331 5.92172 1.75474 5.17157 2.50489C4.42143 3.25503 4 4.27245 4 5.33331C4 9.99998 2 11.3333 2 11.3333H14C14 11.3333 12 9.99998 12 5.33331Z" fill="#0A6AB7" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.15432 14C9.03712 14.2021 8.86888 14.3698 8.66647 14.4864C8.46406 14.6029 8.23458 14.6643 8.00099 14.6643C7.7674 14.6643 7.53792 14.6029 7.33551 14.4864C7.13309 14.3698 6.96486 14.2021 6.84766 14" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @if ($isScheduled->priority)
                    <span class="agenda__modal__item__title">
                        Scheduled for {{ \Carbon\Carbon::parse($isScheduled->date)->format('d/m/Y') }}
                        <span class="__{{ $isScheduled->priority_class }}">{{ $isScheduled->priority }}</span>
                    </span>
                @endif
                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.5 6L8.5 10L12.5 6" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        @else
            <button type="button" class="flag-followup__actions" @click="show_dropdown = !show_dropdown">
                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.1654 14L8.4987 10.6667L3.83203 14V3.33333C3.83203 2.97971 3.97251 2.64057 4.22256 2.39052C4.4726 2.14048 4.81174 2 5.16536 2H11.832C12.1857 2 12.5248 2.14048 12.7748 2.39052C13.0249 2.64057 13.1654 2.97971 13.1654 3.33333V14Z" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.5 6L8.5 10L12.5 6" stroke="#161515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        @endif
        <ul x-cloak x-show="show_dropdown" @click.away="show_dropdown = false">
            @if (!$isFlag and !$isScheduled)
                <li @click="show_dropdown = false" wire:click="open_modal_flag()">
                    <button type="button">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6654 14L7.9987 10.6667L3.33203 14V3.33333C3.33203 2.97971 3.47251 2.64057 3.72256 2.39052C3.9726 2.14048 4.31174 2 4.66536 2H11.332C11.6857 2 12.0248 2.14048 12.2748 2.39052C12.5249 2.64057 12.6654 2.97971 12.6654 3.33333V14Z" stroke="#CC0000" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Flag inquiry</span>
                    </button>
                </li>
                <li @click="show_dropdown = false" wire:click="open_modal_schedule()">
                    <button type="button">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5.33331C12 4.27245 11.5786 3.25503 10.8284 2.50489C10.0783 1.75474 9.06087 1.33331 8 1.33331C6.93913 1.33331 5.92172 1.75474 5.17157 2.50489C4.42143 3.25503 4 4.27245 4 5.33331C4 9.99998 2 11.3333 2 11.3333H14C14 11.3333 12 9.99998 12 5.33331Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.15432 14C9.03712 14.2021 8.86888 14.3698 8.66647 14.4864C8.46406 14.6029 8.23458 14.6643 8.00099 14.6643C7.7674 14.6643 7.53792 14.6029 7.33551 14.4864C7.13309 14.3698 6.96486 14.2021 6.84766 14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Schedule reminder</span>
                    </button>
                </li>
            @else
                <li @click="show_dropdown = false" wire:click="remove_chin('{{ $isFlag ? 'flag' : 'schedule' }}')">
                    <button type="button">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#CC0000" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#CC0000" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Remove</span>
                    </button>
                </li>
                <li @click="show_dropdown = false" wire:click="{{ $isFlag ? 'open_modal_flag(false)' : 'open_modal_schedule(false)' }}">
                    <button type="button">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 13.3334H14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 2.33328C11.2652 2.06806 11.6249 1.91907 12 1.91907C12.1857 1.91907 12.3696 1.95565 12.5412 2.02672C12.7128 2.09779 12.8687 2.20196 13 2.33328C13.1313 2.4646 13.2355 2.6205 13.3066 2.79208C13.3776 2.96367 13.4142 3.14756 13.4142 3.33328C13.4142 3.519 13.3776 3.7029 13.3066 3.87448C13.2355 4.04606 13.1313 4.20196 13 4.33328L4.66667 12.6666L2 13.3333L2.66667 10.6666L11 2.33328Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Edit</span>
                    </button>
                </li>
            @endif
        </ul>
    </div>
    <x-followup-flag-modal />
    <x-followup-schedule-modal />
</div>
