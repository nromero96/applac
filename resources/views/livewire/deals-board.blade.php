<div class="deals" wire:loading.class="loading" wire:target="updateParent,clearFilters" x-data="{
    board_active: @entangle('board_active').defer,
    show_filters: @entangle('show_filters').defer,
    statuses: @entangle('filters_data.statuses'),
    // modal
    show_statuses: false,
    show_modal: @entangle('show_modal').defer,
    modal_deal_data: @entangle('modal_deal_data').defer,
    openDealModal(current_status, quotation_id) {
        this.show_modal = true;
        this.modal_deal_data.quotation_id = quotation_id;
        this.modal_deal_data.status = this.statuses[current_status];
        this.modal_deal_data.old_status = this.statuses[current_status];
    }
}">

    @if(\Auth::user()->hasRole('Administrator'))
        <div class="row">
            <div class="col-3">
                <label for="assignedUserId" class="form-label">User Sale</label>
                <select wire:model="assignedUserId" class="form-select" id="assignedUserId">
                    @foreach ($user_sales as $usr)
                        <option value="{{ $usr->id }}">{{ $usr->name }} {{ $usr->lastname }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="deals__actions">
        <div class="deals__actions__options">
            <button type="button" :class="board_active == 'open' ? '__active' : ''" @click="board_active = 'open'">
                Open Deals
            </button>
            <button type="button" :class="board_active == 'awaiting' ? '__active' : ''" @click="board_active = 'awaiting'">
                Awaiting Outcome
            </button>
        </div>
        <x-deal-board-filters />
    </div>

    <div class="deals__board" x-show="board_active == 'open'">
        @foreach ($statuses as $statusKey => $status)
            <livewire:deals-board-column
                type="open"
                :statusKey="$statusKey"
                :status="$status['status']"
                :label="$status['label']"
                :icon="$icons[$statusKey]"
                :filters="$filters"
                :key="$statusKey . 'column-' . $refreshKey"
                :assigned-user-id="$assignedUserId"
            />
        @endforeach
    </div>

    <div class="deals__board" x-show="board_active == 'awaiting'">
        @foreach ($results as $resultsKey => $result)
            <livewire:deals-board-column
                type="awaiting"
                :statusKey="$resultsKey"
                :result="$result['result']"
                :label="$result['label']"
                :filters="$filters"
                :key="$resultsKey . 'column-' . $refreshKey"
                :assigned-user-id="$assignedUserId"
            />
        @endforeach
    </div>
    <livewire:new-inquiry saved-route-to="deals.index" />

    <div x-show="show_modal" x-cloak>
        <x-deal-board-modal />
    </div>
</div>

@push('scripts')
<script>
    // Internal Inquiry
    jQuery('#btn-new-internal-inquiry').on('click', function(e){
        e.preventDefault();
        jQuery('#newinquiryForm').fadeIn('fast');
        jQuery('html').css('overflowY', 'hidden');
    })

    jQuery('body').on('click', '#newinquiryForm:not(.__stored)', function(e){
        e.preventDefault();
        jQuery('#newinquiryForm').fadeOut('fast');
        setTimeout(() => {
            // Livewire.emit('clean_data_after_close');
            jQuery('html').css('overflowY', 'auto');
        }, 300);
    })
    jQuery('#newinquiry__close').on('click', function(e){
        e.preventDefault();
        jQuery('#newinquiryForm').fadeOut('fast');
        setTimeout(() => {
            // Livewire.emit('clean_data_after_close');
            jQuery('html').css('overflowY', 'auto');
        }, 300);
    })

    jQuery('body').on('click', '#newinquiryForm.__stored', function(e){
        e.preventDefault();
        jQuery('#newinquiryForm.__stored').show();
        location.reload();
    })

    jQuery('#newinquiryForm .newinquiry__content').on('click', function(e){
        e.stopPropagation();
    })

    jQuery('#newinquiryForm').on('click', '.newinquiry__thanks', function(e){
        e.stopPropagation();
    })


    document.addEventListener('livewire:load', function () {
        window.livewire.on("add_stored_class_to_internal_inquiry", function(){
            jQuery('#newinquiryForm').addClass('__stored');
        })
    })
</script>
@endpush

