<div class="newinquiry" id="newinquiryForm" wire:ignore.self>
    @php
        use App\Enums\TypeInquiry;
    @endphp
    @if (!$stored)
        <form class="newinquiry__content" wire:submit.prevent="store()">
            {{-- button close --}}
            <button type="button" class="newinquiry__close" id="newinquiry__close">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="16" fill="#F5F5F5"/>
                    <path d="M20 12L12 20" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 12L20 20" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            {{-- header --}}
            <div class="row mb-3 align-items-center">
                <div class="col-6">
                    {{-- title --}}
                    <div class="d-flex align-items-center gap-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2V8H20" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 13H8" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 17H8" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 9H9H8" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2 class="newinquiry__title">Add New Inquiry</h2>
                    </div>
                </div>
                <div class="col-6">
                    {{-- options --}}
                    <div class="row flex align-items-start">
                        <div class="col-4 d-flex align-items-center gap-2">
                            <label class="form-label flex-shrink-0">Type</label>
                            <select class="form-select" wire:model="type_inquiry">
                                @foreach ($types_inquiries as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                                @endforeach
                            </select>
                            @error('type_inquiry') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="col-8 d-flex align-items-start gap-2">
                                <label class="form-label flex-shrink-0" style="padding-top: 8px">Assigned to</label>
                                @if(\Auth::user()->hasRole('Administrator') || \Auth::user()->hasRole('Leader'))
                                <div class="w-100">
                                    <select class="form-select" wire:model.defer="member">
                                        <option value="">Select option</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }} {{ $member->lastname }}</option>
                                        @endforeach
                                    </select>
                                    @error('member') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                                @else
                                    <input type="text" value="{{ $member_sales_role }}" class="form-control" disabled />
                                @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                {{-- Organization / Contact --}}
                <div class="col-md-6 pe-4" style="border-right: 1px solid #D8D8D8;">

                    {{-- Organization --}}
                    <div class="newinquiry__block mb-3">
                        <div class="w-100 position-relative">
                            <div class="newinquiry__block__headline">
                                <h5>Organization</h5>
                                <div class="__sep"></div>
                                <button type="button" class="newinquiry__reset" wire:click="reset_data()">Reset</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="organization_code" class="form-label">
                                    Organization Name <span class="text-danger">*</span>
                                </label>
                                <div class="newinquiry__org-name">
                                    <input type="text" class="form-control text-uppercase" wire:model.debounce.500ms="org_name" {{ $org_selected ? 'disabled' : '' }} />
                                    @if (sizeof($organizations) > 0)
                                        <ul>
                                            @foreach ($organizations as $org)
                                                <li wire:click="select_org({{ $org->id }})">{{ $org->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                @error('org_name') <span class='text-danger'>{{ $message }}</span> @enderror
                            </div>

                            @if ($type_inquiry == TypeInquiry::INTERNAL->value)
                                <div class="col-5">
                                    <label for="organization_code" class="form-label">
                                        Org. Code
                                    </label>
                                    <input type="text" class="form-control text-uppercase" wire:model.defer="org_code" {{ ($org_selected and $org_code != '') ? 'disabled' : '' }} />
                                    @error('org_code') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        @if ($type_inquiry == TypeInquiry::INTERNAL_LEGACY->value or $type_inquiry == TypeInquiry::INTERNAL_OTHER_AGT->value)
                            <div class="row mt-2">
                                <div class="col-6">
                                    <label class="form-label">Location</label>
                                    <select class="form-select" wire:model.defer="location">
                                        <option value="">Select an option</option>
                                        @foreach ($location_list as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6" wire:ignore>
                                    <label class="form-label">Network</label>
                                    <select wire:model.defer="network" multiple id="select-network">
                                        @foreach ($network_options as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if ($type_inquiry == TypeInquiry::INTERNAL->value or $type_inquiry == TypeInquiry::INTERNAL_LEGACY->value)
                            <div class="row mt-2">
                                <div class="col-6">
                                    <label class="form-label">Tier</label>
                                    <select class="form-select" wire:model.defer="tier">
                                        <option value="">Select an option</option>
                                        <option value="Tier 1">Tier 1</option>
                                        <option value="Tier 2">Tier 2</option>
                                        <option value="Tier 3">Tier 3</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Score</label>
                                    <input type="text" class="form-control" placeholder="0" wire:model.defer="score">
                                    @error('score') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        @if ($type_inquiry == TypeInquiry::INTERNAL->value)
                            <div class="row mt-2">
                                <div class="col">
                                    <h6>Additional Details</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="recovered-account" wire:model.defer="recovered_account">
                                        <label class="form-check-label d-flex align-items-center gap-2" for="recovered-account">
                                            Recovered account
                                            <div wire:ignore data-toggle="tooltip" data-placement="top" title="Check this box if this inquiry is from a customer who hasn't used our services in over 6 months.">
                                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.00065 15.1667C11.6825 15.1667 14.6673 12.1819 14.6673 8.50001C14.6673 4.81811 11.6825 1.83334 8.00065 1.83334C4.31875 1.83334 1.33398 4.81811 1.33398 8.50001C1.33398 12.1819 4.31875 15.1667 8.00065 15.1667Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 11.1667V8.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 5.83334H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($type_inquiry == TypeInquiry::INTERNAL_OTHER_AGT->value)
                            <div class="row mt-2">
                                <div class="col">
                                    <h6>Additional Details</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="referred_by" wire:model.defer="referred_by">
                                        <label class="form-check-label d-flex align-items-center gap-2" for="referred_by">
                                            Referred by another agent
                                            @if (false)
                                                <div wire:ignore data-toggle="tooltip" data-placement="top" title="Check this box if this inquiry is from a customer who hasn't used our services in over 6 months.">
                                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.00065 15.1667C11.6825 15.1667 14.6673 12.1819 14.6673 8.50001C14.6673 4.81811 11.6825 1.83334 8.00065 1.83334C4.31875 1.83334 1.33398 4.81811 1.33398 8.50001C1.33398 12.1819 4.31875 15.1667 8.00065 15.1667Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M8 11.1667V8.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M8 5.83334H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($type_inquiry == TypeInquiry::INTERNAL_OTHER->value)
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="organization_code" class="form-label">
                                        Referring Source <span class="text-danger">*</span>
                                    </label>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle form-select d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {!! $source_label !!}
                                        </button>
                                        <input type="hidden" wire:model="source" />
                                        <ul class="dropdown-menu mt-4">
                                            @if (false)
                                                <li><button class="dropdown-item" type="button" wire:click="set_source('')"><b>Select Source</b></button></li>
                                            @endif
                                            @foreach ($sources_list as $count_group => $group)
                                                @foreach ($group as $source_value => $source)
                                                    <li>
                                                        <button class="dropdown-item" type="button" wire:click='set_source("{{ $source_value }}", {{ $count_group }})'>
                                                            {!! $this->source_draw_label($source_value, $count_group) !!}
                                                        </button>
                                                    </li>
                                                @endforeach
                                                @if ($count_group + 1 < sizeof($sources_list))
                                                    <div class="newinquiry__source__sep"></div>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @error('source') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Contact --}}
                    <div class="newinquiry__block mt-4">
                        <div class="d-flex justify-content-between newinquiry__block__headline">
                            <h5>Contact</h5>
                            <span class="__sep"></span>
                            @if (!empty($contacts))
                                <div class="d-flex align-items-center gap-2">
                                    @if (!empty($contacts))
                                        <div class="newinquiry__contacts">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.3327 14V12.6667C11.3327 11.9594 11.0517 11.2811 10.5516 10.781C10.0515 10.281 9.37326 10 8.66602 10H3.33268C2.62544 10 1.94716 10.281 1.44706 10.781C0.946967 11.2811 0.666016 11.9594 0.666016 12.6667V14" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6.00065 7.33333C7.47341 7.33333 8.66732 6.13943 8.66732 4.66667C8.66732 3.19391 7.47341 2 6.00065 2C4.52789 2 3.33398 3.19391 3.33398 4.66667C3.33398 6.13943 4.52789 7.33333 6.00065 7.33333Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M15.334 14V12.6667C15.3335 12.0758 15.1369 11.5019 14.7749 11.0349C14.4129 10.5679 13.9061 10.2344 13.334 10.0867" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10.666 2.08667C11.2396 2.23354 11.748 2.56714 12.1111 3.03488C12.4742 3.50262 12.6712 4.07789 12.6712 4.67C12.6712 5.26212 12.4742 5.83739 12.1111 6.30513C11.748 6.77287 11.2396 7.10647 10.666 7.25334" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span>{{ $contacts->count() + ($new_contact ? 1 : 0) }}</span>
                                        </div>
                                    @endif
                                    @if (!empty($contacts))
                                        @if (!$new_contact)
                                            <button class="newinquiry__contacts__action" wire:click="add_new_contact()" type="button">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M21.5 12C21.5 17.2467 17.2467 21.5 12 21.5C6.75329 21.5 2.5 17.2467 2.5 12C2.5 6.75329 6.75329 2.5 12 2.5C17.2467 2.5 21.5 6.75329 21.5 12Z" stroke="#2196F3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 8V16" stroke="#2196F3" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 12H16" stroke="#2196F3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button class="newinquiry__contacts__action" wire:click="cancel_new_contact()" type="button">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 9L9 15" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M9 9L15 15" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="organization_code" class="form-label">
                                    Contact Name <span class="text-danger">*</span>
                                </label>
                                @if (!empty($contacts))
                                    @if (!$new_contact)
                                        <select type="text" class="form-select" wire:model.defer="contact.id" wire:change="select_contact($event.target.value)">
                                            @foreach ($contacts as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" class="form-control" wire:model.defer="contact.name" />
                                    @endif
                                @else
                                    <input type="text" class="form-control" wire:model.defer="contact.name" />
                                @endif
                                @error('contact.name') <span class='text-danger'>{{ $message }}</span> @enderror
                            </div>
                            @if ($type_inquiry != TypeInquiry::INTERNAL_OTHER->value && $type_inquiry != TypeInquiry::INTERNAL_OTHER_AGT->value)
                                <div class="col-md-12 mt-2">
                                    <label for="organization_code" class="form-label">
                                        Job Title
                                    </label>
                                    <input type="text" class="form-control" wire:model.defer="contact.job_title" {{ ($org_selected and !$new_contact and !$update_contact) ? 'disabled' : '' }} />
                                    @error('contact.job_title') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                            @endif
                            <div class="col-md-7 mt-2">
                                <label for="organization_code" class="form-label">
                                    Email
                                </label>
                                <input type="text" class="form-control" wire:.defer="contact.email"  {{ ($org_selected and !$new_contact and !$update_contact) ? 'disabled' : '' }} />
                                @error('contact.email') <span class='text-danger'>{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-5 mt-2">
                                <label for="organization_code" class="form-label">
                                    Phone
                                </label>
                                <input type="text" class="form-control" wire:model.defer="contact.phone"  {{ ($org_selected and !$new_contact and !$update_contact) ? 'disabled' : '' }} />
                                @error('contact.phone') <span class='text-danger'>{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 ps-4">
                    <div class="newinquiry__block newinquiry__details">
                        <div class="newinquiry__block__headline">
                            <h5>Shipment Details</h5>
                            <span class="__sep"></span>
                        </div>

                        @if ($type_inquiry == TypeInquiry::INTERNAL_OTHER->value)
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="organization_code" class="form-label d-flex align-items-center justify-content-between">
                                        <span>Rating <span class="text-danger">*</span></span>
                                        <div wire:ignore data-toggle="tooltip" data-placement="top" title="How promising is this inquiry? Consider the business type, cargo value, and shipment urgency.">
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.00065 15.1667C11.6825 15.1667 14.6673 12.1819 14.6673 8.50001C14.6673 4.81811 11.6825 1.83334 8.00065 1.83334C4.31875 1.83334 1.33398 4.81811 1.33398 8.50001C1.33398 12.1819 4.31875 15.1667 8.00065 15.1667Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M8 11.1667V8.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M8 5.83334H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </label>
                                    <div class="dropdown">
                                        <button class="dropdown-toggle form-select d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {!! $rating_label !!}
                                        </button>
                                        <input type="hidden" wire:model="rating" />
                                        <ul class="dropdown-menu mt-4">
                                            <li><button class="dropdown-item" type="button" wire:click="set_rating('')"><b>Select Rating</b></button></li>
                                            <li><button class="dropdown-item" type="button" wire:click="set_rating(5)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(5) !!}</div></button></li>
                                            <li><button class="dropdown-item" type="button" wire:click="set_rating(4)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(4) !!}</div></button></li>
                                            <li><button class="dropdown-item" type="button" wire:click="set_rating(3)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(3) !!}</div></button></li>
                                            <li><button class="dropdown-item" type="button" wire:click="set_rating(2)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(2) !!}</div></button></li>
                                            <li><button class="dropdown-item" type="button" wire:click="set_rating(1)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(1) !!}</div></button></li>
                                        </ul>
                                    </div>
                                    @error('rating') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">Mode of transport <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="mode_of_transport">
                                    <option value="">Select an option</option>
                                    @foreach ($mode_of_transport_options as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('mode_of_transport') <span class='text-danger'>{{ $message }}</span> @enderror
                            </div>
                            <div class="col">
                                <label for="shipping_date" class="form-label d-flex align-items-center justify-content-between">
                                    Est. Shipping Date
                                </label>
                                <input id="shipping_date" type="text" class="form-control" autocomplete="off" wire:model="shipping_date" placeholder="Select Date">
                                @error('shipping_date') <span class='text-danger'>{{ $message }}</span> @enderror
                            </div>
                        </div>

                        @if ($type_inquiry === TypeInquiry::INTERNAL_OTHER_AGT->value)
                            <div class="row mt-2">
                                <div class="col">
                                    <h6>Cargo Details</h6>
                                    @if (sizeof($cargo_details_options) > 0)
                                        @foreach ($cargo_details_options as $i => $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="cargo_details_{{ $i + 1 }}" wire:model.defer="cargo_details" value="{{ $option }}">
                                                <label class="form-check-label" for="cargo_details_{{ $i + 1 }}">{{ $option }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col">
                                    <h6>Additional Info</h6>
                                    @foreach ($additional_info_options as $key => $option)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="additional_info_{{ $key }}" wire:model.defer="additional_info" value="{{ $key }}">
                                            <label class="form-check-label" for="additional_info_{{ $key }}">{{ $option }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row mt-2 __descr">
                            <div class="col-md-12">
                                <label for="organization_code" class="form-label d-flex align-items-center justify-content-between">
                                    Shipment Description
                                    <div wire:ignore data-toggle="tooltip" data-placement="top" title="Attach any documents relevant to this inquiry. Accepted files: EML, PDF, DOC, XLS, JPG, PNG.">
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.00065 15.1667C11.6825 15.1667 14.6673 12.1819 14.6673 8.50001C14.6673 4.81811 11.6825 1.83334 8.00065 1.83334C4.31875 1.83334 1.33398 4.81811 1.33398 8.50001C1.33398 12.1819 4.31875 15.1667 8.00065 15.1667Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 11.1667V8.5" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8 5.83334H8.00667" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-12 flex-fill">
                                <div class="form-control newinquiry__descr">
                                    <div class="__textarea">
                                        <textarea rows="3" wire:model.defer="cargo_description" class=""></textarea>
                                        @error('cargo_description') <span class='text-danger'>{{ $message }}</span> @enderror
                                    </div>

                                    <div class="newinquiry__attachments" wire:loading.class="__loading" wire:target="attach_toggleDropped">
                                        <div
                                            class="newinquiry__attachments__drop {{ $attach_dropping ? '__dropping' : '' }}"
                                            wire:dragover="attach_toggleDropping(true)"
                                            wire:dragleave="attach_toggleDropping(false)"
                                            wire:drop="attach_toggleDropped(false)"
                                        >
                                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.5 10V12.6667C14.5 13.0203 14.3595 13.3594 14.1095 13.6095C13.8594 13.8595 13.5203 14 13.1667 14H3.83333C3.47971 14 3.14057 13.8595 2.89052 13.6095C2.64048 13.3594 2.5 13.0203 2.5 12.6667V10" stroke="#1877F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M11.8333 5.33333L8.49996 2L5.16663 5.33333" stroke="#1877F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M8.5 2V10" stroke="#1877F2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>

                                            <input type="file" multiple wire:model="attachments_added" accept=".eml, .pdf, .doc, .docx, .xls, .xlsx, .jpg, .png, .jpeg">
                                            <p style="margin-bottom: 0">Drag and drop your files here or <span>click to browse</span></p>
                                        </div>
                                        @if (sizeof($attachments) > 0)
                                            <ul class="newinquiry__attachments__list">
                                                @foreach ($attachments as $index => $attach)
                                                    <li>
                                                        <div>
                                                            <span>{{ $attach->getClientOriginalName() }}</span>
                                                            <small>{{ $this->formatSizeAttachment($attach->getSize()) }}</small>
                                                        </div>
                                                        <button type="button" wire:click="attach_remove({{ $index }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(81, 82, 100, 1);transform: ;msFilter:;"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    @error('attachments.*') <span class='text-danger'>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="newinquiry__submit">Submit Inquiry</button>
        </form>
    @else
        <div class="newinquiry__thanks">
            <a href="{{ route('quotations.index') }}" class="newinquiry__close">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="16" cy="16" r="16" fill="#F5F5F5"/>
                    <path d="M20 12L12 20" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 12L20 20" stroke="#161515" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="newinquiry__thanks__content">
                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M37.1673 18.4667V20C37.1653 23.594 36.0015 27.0911 33.8495 29.9697C31.6976 32.8483 28.6728 34.9541 25.2262 35.9732C21.7797 36.9922 18.0961 36.8698 14.7248 35.6243C11.3535 34.3788 8.47508 32.0768 6.51892 29.0618C4.56276 26.0467 3.63364 22.4801 3.87011 18.8939C4.10659 15.3076 5.496 11.8939 7.83112 9.16179C10.1662 6.4297 13.322 4.52564 16.8276 3.73357C20.3333 2.94151 24.0011 3.30389 27.284 4.76667" stroke="#CC0000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M37.1667 6.66666L20.5 23.35L15.5 18.35" stroke="#CC0000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Inquiry Submitted</h2>
                <p>You can now track its progress in the system.</p>
                <a href="{{ route($savedRouteTo) }}">Okay</a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            let select_network;

            function initTomSelect() {
                if (select_network) {
                    select_network.destroy();
                }

                const el = document.querySelector('#select-network');
                if (el) {
                    select_network = new TomSelect(el, {
                        plugins: ['remove_button']
                    });
                }
            }

            initTomSelect();

            // Volver a inicializarlos cada vez que Livewire actualiza el DOM
            Livewire.hook('message.processed', (message, component) => {
                $(() => $('[data-toggle="tooltip"]').tooltip());
                initTomSelect();
            });

            $(() => $('[data-toggle="tooltip"]').tooltip())

            $('#shipping_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD-MM-YYYY',
                    firstDay: 1
                }
            });
            $('#shipping_date').on('apply.daterangepicker', function(ev, picker) {
                @this.set('shipping_date', picker.startDate.format('DD-MM-YYYY'));
            });

            Livewire.on('send-network-tom-select', (data) => {
                setTimeout(() => {
                    select_network.clear();
                    if (data.length > 0) {
                        select_network.addItems(data);
                    }
                }, 0);
            });
        });
    </script>
@endpush
