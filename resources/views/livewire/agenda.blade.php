@if ($modal_mode)
{{-- Modal Agenda --}}
<div
    class="j-modal agenda__modal"
    x-data="{
        ui_show_agenda: @entangle('ui_show_agenda')
    }"
    x-show="ui_show_agenda"
    x-init="
        window.addEventListener('show-agenda', () => {
            ui_show_agenda = true;
        });
    "
>
    <div class="j-modal__content" @click.away="ui_show_agenda = false">
        <button class="__close" type="button" @click="ui_show_agenda = false">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <h1>
            {{ __('My Agenda') }}
        </h1>
@endif

        <div class="agenda__modal__messages">
            {{-- today --}}
            <div class="agenda__modal__group __today">
                <h2 class="agenda__modal__group__title">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_10163_10022)"><path d="M7.9987 14.6668C11.6806 14.6668 14.6654 11.6821 14.6654 8.00016C14.6654 4.31826 11.6806 1.3335 7.9987 1.3335C4.3168 1.3335 1.33203 4.31826 1.33203 8.00016C1.33203 11.6821 4.3168 14.6668 7.9987 14.6668Z" stroke="#F47E27" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 5.3335V8.00016" stroke="#F47E27" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 10.6665H8.00667" stroke="#F47E27" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_10163_10022"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>
                    {{ __('This Week') }}
                </h2>
                @if ($scheduled_today->count() > 0)
                    <div class="agenda__modal__group__items">
                        @foreach ($scheduled_today as $item)
                            <x-agenda-item-card data-type="schedule" :data-today="true" :data-info="$item" />
                        @endforeach
                    </div>
                @else
                    <div class="agenda__modal__group__nodata">
                        {{ __('no upcoming tasks for the next 7 days') }}
                    </div>
                @endif
            </div>

            {{-- flagged --}}
            <div class="agenda__modal__group __flagged">
                <h2 class="agenda__modal__group__title">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8287 1.95473L14.0453 5.17207C14.2507 5.37749 14.4043 5.62878 14.4935 5.90522C14.5828 6.18166 14.605 6.47534 14.5584 6.76207C14.5119 7.04879 14.3979 7.32035 14.2258 7.55437C14.0537 7.78839 13.8285 7.97817 13.5687 8.10807L10.322 9.7314C10.2049 9.7898 10.1154 9.89176 10.0727 10.0154L9.11267 12.7927C9.06654 12.9262 8.98723 13.0458 8.8822 13.1403C8.77716 13.2347 8.64985 13.3009 8.51221 13.3326C8.37457 13.3643 8.23113 13.3606 8.09535 13.3216C7.95957 13.2827 7.83592 13.2099 7.736 13.1101L5.66667 11.0407L2.70667 14.0001H2V13.2921L4.96 10.3334L2.89 8.26407C2.79 8.16416 2.71706 8.04046 2.67804 7.9046C2.63902 7.76874 2.6352 7.62519 2.66693 7.48745C2.69867 7.34971 2.76492 7.2223 2.85945 7.11721C2.95399 7.01213 3.0737 6.93281 3.20733 6.88673L5.98467 5.9274C6.10831 5.88464 6.21026 5.79513 6.26867 5.67807L7.892 2.4314C8.02187 2.17147 8.21167 1.94612 8.44575 1.77395C8.67982 1.60178 8.95147 1.48772 9.23829 1.44116C9.52511 1.39461 9.81889 1.4169 10.0954 1.50619C10.3719 1.59548 10.6232 1.74922 10.8287 1.95473ZM13.3387 5.87873L10.1213 2.66207C10.028 2.56867 9.91382 2.49878 9.78818 2.45816C9.66255 2.41755 9.52906 2.40737 9.39872 2.42846C9.26837 2.44956 9.14491 2.50132 9.0385 2.57949C8.93209 2.65766 8.84577 2.76 8.78667 2.87807L7.16333 6.1254C6.98785 6.47609 6.68202 6.74412 6.31133 6.87207L3.78533 7.7454L8.25533 12.2147L9.12733 9.6894C9.25529 9.31872 9.52331 9.01288 9.874 8.8374L13.122 7.2134C13.2401 7.15436 13.3425 7.06809 13.4208 6.96171C13.499 6.85533 13.5508 6.73188 13.572 6.60153C13.5931 6.47118 13.583 6.33767 13.5425 6.21201C13.5019 6.08634 13.432 5.97211 13.3387 5.87873Z" fill="#CC0000"/></svg>
                    {{ __('Pinned') }}
                </h2>
                @if ($flagged->count() > 0)
                    <div class="agenda__modal__group__items">
                        @foreach ($flagged as $item)
                            <x-agenda-item-card data-type="flag" :data-info="$item" />
                        @endforeach
                    </div>
                @else
                    <div class="agenda__modal__group__nodata">
                        {{ __('no pinned tasks') }}
                    </div>
                @endif
            </div>

            {{-- scheduled --}}
            <div class="agenda__modal__group __scheduled">
                <h2 class="agenda__modal__group__title">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5.33325C12 4.27239 11.5786 3.25497 10.8284 2.50482C10.0783 1.75468 9.06087 1.33325 8 1.33325C6.93913 1.33325 5.92172 1.75468 5.17157 2.50482C4.42143 3.25497 4 4.27239 4 5.33325C4 9.99992 2 11.3333 2 11.3333H14C14 11.3333 12 9.99992 12 5.33325Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.15432 14C9.03712 14.2021 8.86888 14.3698 8.66647 14.4864C8.46406 14.6029 8.23458 14.6643 8.00099 14.6643C7.7674 14.6643 7.53792 14.6029 7.33551 14.4864C7.13309 14.3698 6.96486 14.2021 6.84766 14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('Scheduled') }}
                </h2>
                @if ($scheduled->count() > 0)
                    <div class="agenda__modal__group__items">
                        @foreach ($scheduled as $item)
                            <x-agenda-item-card data-type="schedule" :data-info="$item" />
                        @endforeach
                    </div>
                @else
                    <div class="agenda__modal__group__nodata">
                        {{ __('no scheduled tasks') }}
                    </div>
                @endif
            </div>
        </div>

@if ($modal_mode)
    </div>
</div>
@endif
