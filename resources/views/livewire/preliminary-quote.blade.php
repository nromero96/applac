<div class="quote-generate mt-4 mb-3" x-data="{
    ui_show_details: @entangle('ui_show_details'),
}">

    <div class="quote-generate__header" @click="ui_show_details = !ui_show_details">
        <h4>
            <img src="{{ asset('assets/img/icon-invoice.png') }}" alt="">
            {{ __('Create Preliminary Quote') }}
        </h4>
        <button type="button" :class="ui_show_details ? '__reverse' : ''">
            <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 2L8 8L14 2" stroke="#CC0000" stroke-width="2" stroke-linecap="square"/></svg>
        </button>
    </div>

    <div class="quote-generate__body" x-show="ui_show_details">
        <div class="quote-generate__body__details">
            @foreach ($details as $index => $detail)
                <div class="quote-generate__body__detail">
                    {{-- index --}}
                    <div class="__index">{{ $index + 1 }}</div>

                    {{-- form --}}
                    <form class="__form row">

                        {{-- form part 1 --}}
                        <div class="__form__col col-md-6">
                            <div class="__form__group">
                                <h5 class="__form__subtitle">{{ __('Quote Details') }}</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Mode <span class="text-primary">*</span></label>
                                        <select class="form-select" wire:model="details.{{ $index }}.mode">
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach ($details[$index]['predata']['mode'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @error('details.' . $index . '.mode')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Service Type <span class="text-primary">*</span></label>
                                        <select class="form-select" wire:model.defer="details.{{ $index }}.service_type">
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach ($details[$index]['predata']['service_type'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @error('details.' . $index . '.service_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Origin Type <span class="text-primary">*</span></label>
                                        <select class="form-select" wire:model.defer="details.{{ $index }}.origin_type">
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach ($details[$index]['predata']['origin_type'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @error('details.' . $index . '.origin_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Destination Type <span class="text-primary">*</span></label>
                                        <select class="form-select" wire:model.defer="details.{{ $index }}.destination_type">
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach ($details[$index]['predata']['destination_type'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @error('details.' . $index . '.destination_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Origin Address/Location <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="details.{{ $index }}.origin">
                                        @error('details.' . $index . '.origin')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Destination Address/Location <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="details.{{ $index }}.destination">
                                        @error('details.' . $index . '.destination')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label class="form-label">Commodity <span class="text-primary">*</span></label>
                                        <div class="d-flex flex-wrap" style="gap: 0 1.5rem;">
                                            @foreach ($details[$index]['predata']['commodity'] as $i => $item)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="commodity-{{ $i }}-{{ $index }}" wire:model.defer="details.{{ $index }}.commodity" value="{{ $item }}">
                                                    <label for="commodity-{{ $i }}-{{ $index }}" class="form-check-label">{{ $item }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('details.' . $index . '.commodity')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label class="form-label">Cargo Details <span class="text-primary">*</span></label>
                                        <textarea rows="6" wire:model.defer="details.{{ $index }}.cargo_details" class="form-control"></textarea>
                                        @error('details.' . $index . '.cargo_details')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label class="form-label">Special Notes (Optional)</label>
                                        <input type="text" class="form-control" wire:model.defer="details.{{ $index }}.special_notes">
                                        @error('details.' . $index . '.special_notes')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb2">
                                        <label class="form-label">Transit Time <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="details.{{ $index }}.transit_time">
                                        @error('details.' . $index . '.transit_time')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb2">
                                        <label class="form-label">Validity <span class="text-primary">*</span></label>
                                        <select class="form-select" wire:model.defer="details.{{ $index }}.validity">
                                            <option value="">{{ __('Select an option') }}</option>
                                            @foreach ($details[$index]['predata']['validity'] as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        @error('details.' . $index . '.validity')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- form part 2 --}}
                        <div class="__form__col col-md-6">
                            <div class="__form__group">
                                <h5 class="__form__subtitle">{{ __('Contact Info') }}</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Contact Name <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="details.{{ $index }}.contact_name">
                                        @error('details.' . $index . '.contact_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Business Email <span class="text-primary">*</span></label>
                                        <input type="text" class="form-control" wire:model.defer="details.{{ $index }}.business_email">
                                        @error('details.' . $index . '.business_email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="__form__group mt-4">
                                <h5 class="__form__subtitle">{{ __('Pricing Estimate (USD)') }}</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Freight <span class="text-primary">*</span></label>
                                        <input type="text" placeholder="US$ 0" class="form-control" wire:model.defer="details.{{ $index }}.freight">
                                        @error('details.' . $index . '.freight')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Insurance (Optional)</label>
                                        <input type="text" placeholder="US$ 0" class="form-control" wire:model.defer="details.{{ $index }}.insurance">
                                        @error('details.' . $index . '.insurance')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Concepts --}}
                                    <div class="col">
                                        @if (sizeof($detail['concepts']) > 0)
                                            <div class="__concepts">
                                                @foreach ($detail['concepts'] as $index_concept => $concept)
                                                    <div class="row">
                                                        <div class="col __concepts__remove">
                                                            <button type="button" wire:click="removeDetailConcept({{ $index }}, {{ $index_concept }})">
                                                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.33203 8.5H12.6654" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                            </button>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">Concept <span class="text-primary">*</span></label>
                                                            <select class="form-select" wire:model.defer="details.{{ $index }}.concepts.{{ $index_concept }}.concept">
                                                                <option value="">Select an option</option>
                                                                @foreach ($details[$index]['predata']['concepts'] as $item)
                                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('details.' . $index . '.concepts.' . $index_concept . '.concept')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">Price <span class="text-primary">*</span></label>
                                                            <input type="text" class="form-control" placeholder="US$ 0" wire:model.defer="details.{{ $index }}.concepts.{{ $index_concept }}.price">
                                                            @error('details.' . $index . '.concepts.' . $index_concept . '.price')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <button type="button" wire:click="addDetailConcept({{ $index }})" class="__concepts__add mt-1">Add Concept</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    {{-- actions --}}
                    <div class="__actions">
                        <button type="button" wire:click="addDetail()" class="__add">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 3.83325V13.1666" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.33203 8.5H12.6654" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        @if (sizeof($details) > 1)
                            <button type="button" wire:click="removeDetail({{ $index }})" class="__remove">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 4.5H3.33333H14" stroke="#B80000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.6654 4.49992V13.8333C12.6654 14.1869 12.5249 14.526 12.2748 14.7761C12.0248 15.0261 11.6857 15.1666 11.332 15.1666H4.66536C4.31174 15.1666 3.9726 15.0261 3.72256 14.7761C3.47251 14.526 3.33203 14.1869 3.33203 13.8333V4.49992M5.33203 4.49992V3.16659C5.33203 2.81296 5.47251 2.47382 5.72256 2.22378C5.9726 1.97373 6.31174 1.83325 6.66536 1.83325H9.33203C9.68565 1.83325 10.0248 1.97373 10.2748 2.22378C10.5249 2.47382 10.6654 2.81296 10.6654 3.16659V4.49992" stroke="#B80000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- convert --}}
        <div class="quote-generate__confirm">
            <div class="notice __blue">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_9369_7381)"><path d="M10.0013 18.3334C14.6037 18.3334 18.3346 14.6025 18.3346 10.0001C18.3346 5.39771 14.6037 1.66675 10.0013 1.66675C5.39893 1.66675 1.66797 5.39771 1.66797 10.0001C1.66797 14.6025 5.39893 18.3334 10.0013 18.3334Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 6.66675V10.0001" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 13.3333H10.0083" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_9369_7381"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>
                <p>{{ __('Please remember to double-check your quote details before sending a proposal') }}</p>
            </div>
            <div class="__actions">
                <button type="button" class="__email" wire:click="processDetails('send_email')">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_10343_9932)"><path d="M18.3346 1.66675L9.16797 10.8334" stroke="#6200EE" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.3346 1.66675L12.5013 18.3334L9.16797 10.8334L1.66797 7.50008L18.3346 1.66675Z" stroke="#6200EE" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_10343_9932"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>
                    <span>{{ __('Send by Email') }}</span>
                </button>
                <button type="button" class="__pdf" wire:click="processDetails('download_pdf')">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 12.5V15.8333C17.5 16.2754 17.3244 16.6993 17.0118 17.0118C16.6993 17.3244 16.2754 17.5 15.8333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V12.5" stroke="#B80000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.83203 8.33325L9.9987 12.4999L14.1654 8.33325" stroke="#B80000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 12.5V2.5" stroke="#B80000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span>{{ __('Download PDF') }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- modal --}}
    @if ($ui_show_modal_email)
        <div class="j-modal">
            <div class="j-modal__content">
                <button wire:click="uiCloseModalEmail()" class="__close" type="button">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L4 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4L12 12" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <h1>
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M36.6654 3.33334L18.332 21.6667" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M36.6654 3.33334L24.9987 36.6667L18.332 21.6667L3.33203 15L36.6654 3.33334Z" stroke="#6200EE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('Send by Email') }}
                </h1>
                <div class="mb-4">
                    <label class="form-label">Your Message</label>
                    <textarea rows="6" wire:model.defer="message_email" class="form-control"></textarea>
                    @error('message_email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="notice __blue">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_9369_7381)"><path d="M10.0013 18.3334C14.6037 18.3334 18.3346 14.6025 18.3346 10.0001C18.3346 5.39771 14.6037 1.66675 10.0013 1.66675C5.39893 1.66675 1.66797 5.39771 1.66797 10.0001C1.66797 14.6025 5.39893 18.3334 10.0013 18.3334Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 6.66675V10.0001" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 13.3333H10.0083" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_9369_7381"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>
                    <p>{{ __('Please remember to double-check your quote details before sending a proposal') }}</p>
                </div>
                <div class="j-modal__actions">
                    <button type="button" class="btn__primary" wire:click="send_email()">{{ __('Send') }}</button>
                    <button type="button" class="btn__secondary" wire:click="uiCloseModalEmail()">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    @endif

</div>
