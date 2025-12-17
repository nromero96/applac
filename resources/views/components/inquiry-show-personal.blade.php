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
                <span><label class="fw-bold">Total Weight: </label> {{ $quotation->total_actualweight }}</span>
                <span><label class="fw-bold">Total CBM: </label> {{ $quotation->total_volum_weight }} m<sup>3</sup></span>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card py-2 px-2">
            @if ($quotation_documents->count() > 0)
                <label for="ctdocuments" class="fw-bold mb-0">{{ __('Attachments') }}:</label>
                <ul class="mb-0 ps-3" id="ctdocuments">
                    @foreach ($quotation_documents as $document)
                        <li><a href="{{ asset('storage/uploads/quotation_documents').'/'. $document->document_path }}" class="text-info" target="_blank">{{ $document->document_path }}</a></li>
                    @endforeach
                </ul>
            @else
                <label for="ctdocuments" class="fw-bold mb-0">{{ __('Documents') }}:</label>
                <span>{{ __('No documents') }}</span>
            @endif
        </div>
    </div>
</div>
