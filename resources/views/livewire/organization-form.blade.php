<div class="widget-content widget-content-area pt-0">
    @if ($changed)
        <div class="alert alert-success" role="alert">
            Organization Updated!
        </div>
    @endif

    @if (!$showing)
        <form class="row g-3" wire:submit.prevent="{{ $org_editing ? 'update()' : 'store()' }} ">
    @else
        <div class="row g-3">
    @endif
        <div class="col-md-4">
            <label for="organization_code" class="form-label fw-bold">
                {{__("Org. Code")}} @if (!$showing) <span class="text-danger"></span> @endif
            </label>
            @if (!$showing)
                <input wire:model.defer="code" type="text" name="organization_code" class="form-control" id="organization_code" value="{{old('organization_code')}}" placeholder="{{__('Type here')}}" >
                @error('code') <span class='text-danger'>{{ $message }}</span> @enderror
            @else
                <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $code ? : '-' }}</p>
            @endif
        </div>
        <div class="col-md-4">
            <label for="organization_name" class="form-label fw-bold">
                {{__("Organization Name")}} @if (!$showing) <span class="text-danger">*</span> @endif
            </label>
            @if (!$showing)
                <input wire:model.defer="name" type="text" name="organization_name" class="form-control" id="organization_name" value="{{old('organization_name')}}" placeholder="{{__('Type here')}}" >
                @error('name') <span class='text-danger'>{{ $message }}</span> @enderror
            @else
                <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $name }}</p>
            @endif
        </div>
        <div class="col-md-4">
            <label for="organization_address" class="form-label fw-bold">
                {{__("Addresses")}}
            </label>
            @if (!$showing)
                @foreach ($addresses as $i => $address)
                    <div class="mb-2">
                        <div class="d-flex gap-1">
                            <input wire:model.defer="addresses.{{ $i }}" type="text" name="organization_address" class="form-control" placeholder="{{__('Type here')}}" >
                            <button type="button" wire:click="remove_address({{ $i }})" class="btn btn-danger p-0 btn-sm" style="border-radius: 7px; width: 40px;">X</button>
                        </div>
                        @error('addresses.'. $i) <span class='text-danger'>{{ $message }}</span> @enderror
                    </div>
                @endforeach
                <div>
                    <button type="button" class="btn btn-primary" wire:click="add_address()">
                        <span class="btn-text-inner">Add</span>
                    </button>
                </div>
            @else
                @if ($addresses)
                    @foreach ($addresses as $address)
                        <p class="form-control px-2 bg-text-control-form border-0 mb-2" style="background-color: #ebedf2">{{ $address }}</p>
                    @endforeach
                @else
                    <p>No data found</p>
                @endif
            @endif
        </div>

        <div class="col-md-8">
            <div class="row">

                <div class="col-6">
                    <label for="organization_tier" class="form-label fw-bold">
                        {{__("Tier")}} @if (!$showing) <span class="text-danger"></span> @endif
                    </label>
                    @if (!$showing)
                        <select name="organization_tier" id="organization_tier" class="form-select" wire:model.defer="tier">
                            <option value="">Select an option</option>
                            <option value="Tier 1">Tier 1</option>
                            <option value="Tier 2">Tier 2</option>
                            <option value="Tier 3">Tier 3</option>
                        </select>
                        @error('tier') <span class='text-danger'>{{ $message }}</span> @enderror
                    @else
                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $tier ? : '-' }}</p>
                    @endif
                </div>

                <div class="col-6">
                    <label for="organization_score" class="form-label fw-bold">
                        {{__("Score")}} @if (!$showing) <span class="text-danger"></span> @endif
                    </label>
                    @if (!$showing)
                        <input wire:model.defer="score" type="text" name="organization_score" class="form-control" id="organization_score" >
                        @error('score') <span class='text-danger'>{{ $message }}</span> @enderror
                    @else
                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $score ? : '-' }}</p>
                    @endif
                </div>

                <div class="col-6 mt-3">
                    <label for="organization_country_id" class="form-label fw-bold">
                        {{__("Location")}} @if (!$showing) <span class="text-danger"></span> @endif
                    </label>
                    @if (!$showing)
                        <select name="organization_country_id" id="organization_country_id" class="form-select" wire:model.defer="country_id">
                            <option value="">Select an option</option>
                            @foreach ($countries_options as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id') <span class='text-danger'>{{ $message }}</span> @enderror
                    @else
                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $location_label ? : '-' }}</p>
                    @endif
                </div>

                <div class="col-6 mt-3">
                    <label for="organization_network" class="form-label fw-bold">
                        {{__("Network")}} @if (!$showing) <span class="text-danger"></span> @endif
                    </label>
                    @if (!$showing)
                        <select name="organization_network" id="organization_network" class="form-select" wire:model.defer="network" multiple>
                            <option value="">Select an option</option>
                            @foreach ($network_options as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('network') <span class='text-danger'>{{ $message }}</span> @enderror
                    @else
                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">
                            {{ $network_label ? implode(' - ', $network_label) : '-' }}
                        </p>
                    @endif
                </div>

                <div class="col-6 mt-3">
                    <label for="organization_network" class="form-label fw-bold">
                        {{ __("Additional Information") }}
                    </label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="recovered_account" wire:model.defer="recovered_account" @if ($showing) disabled @endif>
                        <label class="form-check-label d-flex align-items-center gap-2" for="recovered_account">
                            Recovered account
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="referred_by" wire:model.defer="referred_by" @if ($showing) disabled @endif>
                        <label class="form-check-label d-flex align-items-center gap-2" for="referred_by">
                            Referred by another agent
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <label for="contact_company" class="form-label fw-bold">{{__("Contact Information")}}: @if (!$showing) <span class="text-danger">*</span>@endif</label>
            @if (!$showing)
                <button type="button" class="btn btn-primary mb-2 me-4" wire:click="add_contact()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    <span class="btn-text-inner">Add</span>
                </button>
                @error('contacts') <span class='text-danger'>{{ $message }}</span> @enderror
            @endif
            <div class="row">
                @if (!empty($contacts))
                    @foreach ($contacts as $index => $contact)
                        <div class="col-md-4 contacitem mb-3">
                            <div class="card p-1">
                                @if (!$showing)
                                    <button type="button" wire:click="remove_contact({{ $index }})" class="btn btn-danger p-0 btn-sm" style="border-radius: 7px 0 3px 1px; margin-left: -5px; margin-top: -5px; position: absolute; width: 20px;">X</button>
                                @endif
                                <div class="card-body p-2">
                                    @if (!$showing and $org_editing)
                                        <input type="hidden" wire:model="contacts.{{ $index }}.id">
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row mt-1 mb-1">
                                                <label class="col-sm-4 col-form-label col-form-label-sm pe-0">Name: @if (!$showing) <span class="text-danger">*</span>@endif</label>
                                                <div class="col-sm-8">
                                                    @if (!$showing)
                                                        <input type="text" wire:model.defer="contacts.{{ $index }}.name" class="form-control form-control-sm" placeholder="Type here" />
                                                        @error('contacts.'. $index.'.name') <span class='text-danger'>{{ $message }}</span> @enderror
                                                    @else
                                                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $contact['name'] }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label col-form-label-sm pe-0">Job Title:</label>
                                                <div class="col-sm-8">
                                                    @if (!$showing)
                                                        <input type="text" wire:model.defer="contacts.{{ $index }}.job_title" class="form-control form-control-sm" placeholder="Type here" />
                                                        @error('contacts.'. $index.'.job_title') <span class='text-danger'>{{ $message }}</span> @enderror
                                                    @else
                                                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $contact['job_title'] ? : '-' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row mb-1">
                                                <label class="col-sm-4 col-form-label col-form-label-sm pe-0">Email: @if (!$showing) <span class="text-danger">*</span>@endif</label>
                                                <div class="col-sm-8">
                                                    @if (!$showing)
                                                        <input type="text" wire:model.defer="contacts.{{ $index }}.email" class="form-control form-control-sm" placeholder="Type here" />
                                                        @error('contacts.'. $index.'.email') <span class='text-danger'>{{ $message }}</span> @enderror
                                                    @else
                                                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $contact['email'] ? : '-' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row mt-1 mb-1">
                                                <label class="col-sm-4 col-form-label col-form-label-sm pe-0">Phone: @if (!$showing) <span class="text-danger">*</span>@endif</label>
                                                <div class="col-sm-8">
                                                    @if (!$showing)
                                                        <input type="text" wire:model.defer="contacts.{{ $index }}.phone" class="form-control form-control-sm" placeholder="Type here" />
                                                        @error('contacts.'. $index.'.phone') <span class='text-danger'>{{ $message }}</span> @enderror
                                                    @else
                                                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $contact['phone'] ? : '-' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row mt-1 mb-1">
                                                <label class="col-sm-4 col-form-label col-form-label-sm pe-0">Fax:</label>
                                                <div class="col-sm-8">
                                                    @if (!$showing)
                                                        <input type="text" wire:model.defer="contacts.{{ $index }}.fax" class="form-control form-control-sm" placeholder="Type here" />
                                                        @error('contacts.'. $index.'.fax') <span class='text-danger'>{{ $message }}</span> @enderror
                                                    @else
                                                        <p class="form-control px-2 bg-text-control-form border-0 mb-0" style="background-color: #ebedf2">{{ $contact['fax'] ? : '-' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @if ($org_editing)
                        <p>No data found</p>
                    @endif
                @endif
            </div>
        </div>

        @if (!$showing)
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    {{ $org_editing ? __("Update") : __("Save") }}
                </button>
            </div>
        @endif

    @if (!$showing)
        </form>
    @else
        </div>
    @endif
</div>
