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
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8287 1.95467L14.0453 5.17201C14.2507 5.37743 14.4043 5.62872 14.4935 5.90516C14.5828 6.1816 14.605 6.47528 14.5584 6.76201C14.5119 7.04873 14.3979 7.32029 14.2258 7.55431C14.0537 7.78833 13.8285 7.97811 13.5687 8.10801L10.322 9.73134C10.2049 9.78974 10.1154 9.8917 10.0727 10.0153L9.11267 12.7927C9.06654 12.9262 8.98723 13.0458 8.8822 13.1402C8.77716 13.2346 8.64985 13.3008 8.51221 13.3326C8.37457 13.3643 8.23112 13.3605 8.09535 13.3216C7.95957 13.2826 7.83592 13.2098 7.736 13.11L5.66667 11.0407L2.70667 14H2V13.292L4.96 10.3333L2.89 8.26401C2.79 8.1641 2.71706 8.0404 2.67804 7.90454C2.63902 7.76868 2.6352 7.62513 2.66693 7.48739C2.69867 7.34965 2.76492 7.22224 2.85945 7.11715C2.95399 7.01207 3.0737 6.93275 3.20733 6.88667L5.98467 5.92734C6.10831 5.88458 6.21026 5.79507 6.26867 5.67801L7.892 2.43134C8.02187 2.1714 8.21167 1.94606 8.44575 1.77389C8.67982 1.60172 8.95147 1.48766 9.23829 1.4411C9.52511 1.39455 9.81889 1.41684 10.0954 1.50613C10.3719 1.59542 10.6232 1.74916 10.8287 1.95467ZM13.3387 5.87867L10.1213 2.66201C10.028 2.56861 9.91382 2.49872 9.78818 2.4581C9.66255 2.41749 9.52906 2.40731 9.39872 2.4284C9.26837 2.4495 9.14491 2.50126 9.0385 2.57943C8.93209 2.6576 8.84577 2.75994 8.78667 2.87801L7.16333 6.12534C6.98785 6.47603 6.68202 6.74405 6.31133 6.87201L3.78533 7.74534L8.25533 12.2147L9.12733 9.68934C9.25528 9.31866 9.52331 9.01282 9.874 8.83734L13.122 7.21334C13.2401 7.1543 13.3425 7.06803 13.4208 6.96165C13.499 6.85527 13.5508 6.73182 13.572 6.60147C13.5931 6.47112 13.583 6.33761 13.5425 6.21194C13.5019 6.08628 13.432 5.97205 13.3387 5.87867Z" fill="#CC0000"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10.1213 2.66201L13.3387 5.87867C13.432 5.97205 13.5019 6.08628 13.5425 6.21194C13.583 6.33761 13.5931 6.47112 13.572 6.60147C13.5508 6.73182 13.499 6.85527 13.4208 6.96165C13.3425 7.06803 13.2401 7.1543 13.122 7.21334L9.874 8.83734C9.52331 9.01282 9.25528 9.31866 9.12733 9.68934L8.25533 12.2147L3.78533 7.74534L6.31133 6.87201C6.68202 6.74405 6.98785 6.47603 7.16333 6.12534L8.78667 2.87801C8.84577 2.75994 8.93209 2.6576 9.0385 2.57943C9.14491 2.50126 9.26837 2.4495 9.39872 2.4284C9.52906 2.40731 9.66255 2.41749 9.78818 2.4581C9.91382 2.49872 10.028 2.56861 10.1213 2.66201Z" fill="#CC0000"/></svg>
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
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8287 1.95467L14.0453 5.17201C14.2507 5.37743 14.4043 5.62872 14.4935 5.90516C14.5828 6.1816 14.605 6.47528 14.5584 6.76201C14.5119 7.04873 14.3979 7.32029 14.2258 7.55431C14.0537 7.78833 13.8285 7.97811 13.5687 8.10801L10.322 9.73134C10.2049 9.78974 10.1154 9.8917 10.0727 10.0153L9.11267 12.7927C9.06654 12.9262 8.98723 13.0458 8.8822 13.1402C8.77716 13.2346 8.64985 13.3008 8.51221 13.3326C8.37457 13.3643 8.23112 13.3605 8.09535 13.3216C7.95957 13.2826 7.83592 13.2098 7.736 13.11L5.66667 11.0407L2.70667 14H2V13.292L4.96 10.3333L2.89 8.26401C2.79 8.1641 2.71706 8.0404 2.67804 7.90454C2.63902 7.76868 2.6352 7.62513 2.66693 7.48739C2.69867 7.34965 2.76492 7.22224 2.85945 7.11715C2.95399 7.01207 3.0737 6.93275 3.20733 6.88667L5.98467 5.92734C6.10831 5.88458 6.21026 5.79507 6.26867 5.67801L7.892 2.43134C8.02187 2.1714 8.21167 1.94606 8.44575 1.77389C8.67982 1.60172 8.95147 1.48766 9.23829 1.4411C9.52511 1.39455 9.81889 1.41684 10.0954 1.50613C10.3719 1.59542 10.6232 1.74916 10.8287 1.95467ZM13.3387 5.87867L10.1213 2.66201C10.028 2.56861 9.91382 2.49872 9.78818 2.4581C9.66255 2.41749 9.52906 2.40731 9.39872 2.4284C9.26837 2.4495 9.14491 2.50126 9.0385 2.57943C8.93209 2.6576 8.84577 2.75994 8.78667 2.87801L7.16333 6.12534C6.98785 6.47603 6.68202 6.74405 6.31133 6.87201L3.78533 7.74534L8.25533 12.2147L9.12733 9.68934C9.25528 9.31866 9.52331 9.01282 9.874 8.83734L13.122 7.21334C13.2401 7.1543 13.3425 7.06803 13.4208 6.96165C13.499 6.85527 13.5508 6.73182 13.572 6.60147C13.5931 6.47112 13.583 6.33761 13.5425 6.21194C13.5019 6.08628 13.432 5.97205 13.3387 5.87867Z" fill="#CC0000"/></svg>
                        <span>Pin inquiry</span>
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
