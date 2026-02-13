<div class="row mt-2">
    <div class="col-md-3">
        <h6 class="text-primary mb-3">{{ __('Contact Info') }}</h6>
        <div><label class="fw-bold">{{__("Contact Name")}}:</label> {{ $quotation->customer_name }} {{ $quotation->customer_lastname }}</div>
        <div><label class="fw-bold">{{__("Email")}}:</label> {{ $quotation->customer_email }}</div>
        <div><label class="fw-bold">{{__("Phone")}}:</label> +{{ $quotation->customer_phone_code }} {{ $quotation->customer_phone }}</div>
        <div><label class="fw-bold">{{__("Location")}}:</label> {{ $quotation->customer_country_name }}</div>
        <div>
            <label class="fw-bold">{{__("Source")}}:</label>
            @switch($quotation->customer_source)
                @case('agt') {{ __('Agent') }} @break
                @default {{ $quotation->customer_source }}
            @endswitch
        </div>

        <hr>

        <div class="">
            <label for="ctdocuments" class="fw-bold mb-0 flex align-items-center gap-2 d-block">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.2934 7.36678L8.1667 13.4934C7.41613 14.244 6.39815 14.6657 5.3367 14.6657C4.27524 14.6657 3.25726 14.244 2.5067 13.4934C1.75613 12.7429 1.33447 11.7249 1.33447 10.6634C1.33447 9.60199 1.75613 8.58401 2.5067 7.83344L8.63336 1.70678C9.13374 1.2064 9.81239 0.925293 10.52 0.925293C11.2277 0.925293 11.9063 1.2064 12.4067 1.70678C12.9071 2.20715 13.1882 2.88581 13.1882 3.59344C13.1882 4.30108 12.9071 4.97973 12.4067 5.48011L6.27336 11.6068C6.02318 11.857 5.68385 11.9975 5.33003 11.9975C4.97621 11.9975 4.63688 11.857 4.3867 11.6068C4.13651 11.3566 3.99596 11.0173 3.99596 10.6634C3.99596 10.3096 4.13651 9.9703 4.3867 9.72011L10.0467 4.06678" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ __('Attachments') }}:
            </label>
            @if ($quotation_documents->count() > 0)
                <ul class="mb-0 ps-3" id="ctdocuments">
                    @foreach ($quotation_documents as $document)
                        <li><a href="{{ asset('storage/uploads/quotation_documents').'/'. $document->document_path }}" class="text-info" target="_blank">{{ $document->document_path }}</a></li>
                    @endforeach
                </ul>
            @else
                <span>{{ __('No documents') }}</span>
            @endif
        </div>
    </div>

    <div class="col-md-6" style="border-left: 1px solid #D8D8D8; padding-left: 2rem;">
        <h6 class="text-primary mb-3">{{ __('Shipment Info') }}</h6>

        <div class="d-flex gap-2 align-items-center mb-2">
            <div><label class="fw-bold mb-0">Origin:</label> {{ $quotation->origin_country }} - {{ $quotation->origin_airportorport }}</div>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 15L12.5 10L7.5 5" stroke="#D8D8D8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <div><label class="fw-bold mb-0">Destination:</label> {{ $quotation->destination_country }} - {{ $quotation->destination_airportorport }}</div>
        </div>

        <div class="d-flex gap-4 align-items-center mb-2">
            <div><label class="fw-bold mb-0">Mode of transport:</label> {{ $quotation->modeOfTransportLabel() }}</div>
            <div><label class="fw-bold mb-0">Service Type:</label> {{ $quotation->service_type }}</div>
        </div>

        <div class="d-flex gap-4 align-items-center mb-2">
            <div><label class="fw-bold mb-0">Declared value:</label> {{ number_format($quotation->declared_value, 2) }} {{ $quotation->currency }}</div>
            <div><label class="fw-bold mb-0">Insurance required:</label> {{ ucfirst($quotation->insurance_required) }}</div>
        </div>

        <div class="d-flex gap-4 align-items-center mb-2">
            <div><label class="fw-bold mb-0">Shipping date:</label> {{ ($quotation->shipping_date == '') ? 'Not specified' : $quotation->shipping_date }}</div>
        </div>

        <div class="d-flex gap-4 align-items-center mb-2">
            <label class="fw-bold mb-0">Cargo Details:</label>
        </div>

        <div class="col-md-12 mt-0">
            <div class="cargo__details">
                @foreach ($cargo_details as $index => $item)
                    <div class="cargo__details__item">
                        <div class="__counter">#{{ $index + 1 }}</div>
                        <div class="__content">
                            <div class="__row">
                                <label><strong>Vehicle Details</strong></label>
                                <div class="__info">
                                    <div><strong>Type:</strong> {{ $item->package_type }}</div>
                                    <div><strong>Description:</strong> {{ $item->cargo_description }}</div>
                                    <div><strong>Quantity:</strong> 1</div>
                                </div>
                            </div>
                            <div class="__row">
                                <label><strong>Dimensions</strong></label>
                                <div class="__info">
                                    <div><strong>Length:</strong> {{ $item->length }} {{ $item->dimensions_unit }}</div>
                                    <div><strong>Width:</strong> {{ $item->width }} {{ $item->dimensions_unit }}</div>
                                    <div><strong>Height:</strong> {{ $item->height }} {{ $item->dimensions_unit }}</div>
                                </div>
                            </div>
                            <div class="__row __multi">
                                <div>
                                    <label><strong>Weight</strong></label>
                                    <div class="__info">
                                        <div><strong>Per piece:</strong> {{ $item->per_piece }} {{ $item->weight_unit }}</div>
                                    </div>
                                </div>
                                <div>
                                    <label><strong>Volume</strong></label>
                                    <div class="__info">
                                        <div><strong>CBM:</strong> {{ number_format($item->item_total_volume_weight_cubic_meter, 2) }} m<sup>3</sup></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="cargo__totals">
            <label class="fw-bold mb-0 cargo__totals__title">Calculated total</label>
            <div class="cargo__totals__content">
                <span><label class="fw-bold">Items: </label> {{ $quotation->total_qty }}</span>
                <span><label class="fw-bold">Total Weight: </label> {{ $quotation->total_actualweight }} Kg</span>
                <span><label class="fw-bold">Total CBM: </label> {{ $quotation->total_volum_weight }} m<sup>3</sup></span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <livewire:inquiry-note :quotation="$quotation" />
    </div>
</div>
