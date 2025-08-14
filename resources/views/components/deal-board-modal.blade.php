<div class="deals__modal">
    <form class="deals__modal__content" wire:submit.prevent="save_modal_data()">
        <button type="button" class="__close" type="button" @click="show_modal = false">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="deals__modal__messages">
            <h1 class="deals__modal__heading">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26.668 30L36.668 20L26.668 10" stroke="#B80000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.332 10L3.33203 20L13.332 30" stroke="#B80000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Update Status
            </h1>
            <div class="deals__modal__fields">
                <div>
                    <label clasS="form-label">Change status</label>
                    <div class="form-select" @click.self="show_statuses = true" @click.away="show_statuses = false">
                        <span
                            type="text"
                            x-text="modal_deal_data.status?.label"
                            :style="modal_deal_data.status?.style"
                            @click.self="show_statuses = true"
                        ></span>
                        <ul x-cloak x-show="show_statuses">
                            <template x-for="status in statuses">
                                <li @click="modal_deal_data.status = status; show_statuses = false">
                                    <span :style="status.style" x-text="status.label"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    @error('modal_deal_data.status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div x-show="modal_deal_data?.status?.label != 'Unqualified'">
                    <label clasS="form-label">Notes</label>
                    <input type="text" class="form-control" x-model="modal_deal_data.notes">
                    @error('modal_deal_data.notes')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div x-show="modal_deal_data?.status?.label == 'Contacted'">
                    <label class="form-label mb-0">{{ __('Contacted via') }} <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <label class="form-check">
                            <input type="radio" name="contacted_via" class="form-check-input" value="Email" x-model="modal_deal_data.contacted_via">
                            <div class="form-check-label">Email</div>
                        </label>
                        <label class="form-check">
                            <input type="radio" name="contacted_via" class="form-check-input" value="Call" x-model="modal_deal_data.contacted_via">
                            <div class="form-check-label">Call</div>
                        </label>
                        <label class="form-check">
                            <input type="radio" name="contacted_via" class="form-check-input" value="Text" x-model="modal_deal_data.contacted_via">
                            <div class="form-check-label">Text</div>
                        </label>
                    </div>
                    @error('modal_deal_data.contacted_via')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div x-show="modal_deal_data?.status?.label == 'Unqualified'">
                    <label class="form-label mb-0">{{ __('Reason to decline') }} <span class="text-danger">*</span></label>
                    <select name="reason" class="form-select" x-model="modal_deal_data.reason">
                        <option value="">{{ __('Select and option') }}</option>
                        <option value="3PL located in CA / US quoting from CA / US" >3PL located in CA / US quoting from CA / US</option>
                        <option value="3PL located in foreign country quoting from foreign to CA / US">3PL located in foreign country quoting from foreign to CA / US</option>
                        <option value="Business requesting a quote out-of-scope">Business requesting a quote out-of-scope</option>
                        <option value="Foreign business quoting for triangular shipment">Foreign business quoting for triangular shipment</option>
                        <option value="CA/US business quoting from China to LATAM / NA (Small Qty)">CA/US business quoting from China to LATAM / NA (Small Qty)</option>
                        <option value="Personal effects / Household goods">Personal effects / Household goods</option>
                        <option value="Business consulting">Business consulting</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('modal_deal_data.reason')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="deals__modal__actions">
            <button type="submit" class="btn__primary">Save</button>
            <button type="button" class="btn__secondary" @click="show_modal = false">Cancel</button>
        </div>
    </form>
</div>
