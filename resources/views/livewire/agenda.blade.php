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
                    {{ __('For Today') }}
                </h2>
                @if ($scheduled_today->count() > 0)
                    <div class="agenda__modal__group__items">
                        @foreach ($scheduled_today as $item)
                            <x-agenda-item-card data-type="schedule" :data-today="true" :data-info="$item" />
                        @endforeach
                    </div>
                @else
                    <div class="agenda__modal__group__nodata">
                        {{ __('no scheduled tasks for today') }}
                    </div>
                @endif
            </div>

            {{-- flagged --}}
            <div class="agenda__modal__group __flagged">
                <h2 class="agenda__modal__group__title">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6654 14L7.9987 10.6667L3.33203 14V3.33333C3.33203 2.97971 3.47251 2.64057 3.72256 2.39052C3.9726 2.14048 4.31174 2 4.66536 2H11.332C11.6857 2 12.0248 2.14048 12.2748 2.39052C12.5249 2.64057 12.6654 2.97971 12.6654 3.33333V14Z" stroke="#CC0000" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('Flagged') }}
                </h2>
                @if ($flagged->count() > 0)
                    <div class="agenda__modal__group__items">
                        @foreach ($flagged as $item)
                            <x-agenda-item-card data-type="flag" :data-info="$item" />
                        @endforeach
                    </div>
                @else
                    <div class="agenda__modal__group__nodata">
                        {{ __('no flagged tasks') }}
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
