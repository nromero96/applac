<div class="newinquiry">
    <form class="newinquiry__content" wire:submit.prevent="store()">

        <div class="row">
            <div class="col-md-12">
                <h2 class="newinquiry__title">Add internal Inquiry</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="newinquiry__block mb-3">
                    <div class="w-100 position-relative">
                        <h5>Organization</h5>
                        @if ($org_selected)
                            <button type="button" class="newinquiry__reset" wire:click="reset_data()">Reset</button>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <label for="organization_code" class="form-label">
                                Organization Name
                            </label>
                            <div class="newinquiry__org-name">
                                <input type="text" class="form-control" wire:model.debounce.500ms="org_name" {{ $org_selected ? 'disabled' : '' }} />
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
                        <div class="col-md">
                            <label for="organization_code" class="form-label">
                                Org. Code
                            </label>
                            <input type="text" class="form-control" wire:model.defer="org_code" {{ $org_selected ? 'disabled' : '' }} />
                            @error('org_code') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="newinquiry__block">
                    <div class="d-flex justify-content-between">
                        <h5>Contact</h5>
                        <div class="d-flex align-items-center gap-2">
                            @if (!empty($contacts))
                                <div class="newinquiry__contacts">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.3327 14V12.6667C11.3327 11.9594 11.0517 11.2811 10.5516 10.781C10.0515 10.281 9.37326 10 8.66602 10H3.33268C2.62544 10 1.94716 10.281 1.44706 10.781C0.946967 11.2811 0.666016 11.9594 0.666016 12.6667V14" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.00065 7.33333C7.47341 7.33333 8.66732 6.13943 8.66732 4.66667C8.66732 3.19391 7.47341 2 6.00065 2C4.52789 2 3.33398 3.19391 3.33398 4.66667C3.33398 6.13943 4.52789 7.33333 6.00065 7.33333Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15.334 14V12.6667C15.3335 12.0758 15.1369 11.5019 14.7749 11.0349C14.4129 10.5679 13.9061 10.2344 13.334 10.0867" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.666 2.08667C11.2396 2.23354 11.748 2.56714 12.1111 3.03488C12.4742 3.50262 12.6712 4.07789 12.6712 4.67C12.6712 5.26212 12.4742 5.83739 12.1111 6.30513C11.748 6.77287 11.2396 7.10647 10.666 7.25334" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>{{ $contacts->count() }}</span>
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
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="organization_code" class="form-label">
                                Contact Name
                            </label>
                            @if (!empty($contacts))
                                @if (!$new_contact)
                                    <select type="text" class="form-select" wire:model="contact.id" wire:change="select_contact($event.target.value)">
                                        @foreach ($contacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" wire:model="contact.name" />
                                @endif
                            @else
                                <input type="text" class="form-control" wire:model="contact.name" />
                            @endif
                            @error('contact.name') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="organization_code" class="form-label">
                                Job Title (Optional)
                            </label>
                            <input type="text" class="form-control" wire:model="contact.job_title" {{ ($org_selected and !$new_contact) ? 'disabled' : '' }} />
                            @error('contact.job_title') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="organization_code" class="form-label">
                                Email
                            </label>
                            <input type="text" class="form-control" wire:model="contact.email"  {{ ($org_selected and !$new_contact) ? 'disabled' : '' }} />
                            @error('contact.email') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="organization_code" class="form-label">
                                Phone
                            </label>
                            <input type="text" class="form-control" wire:model="contact.phone"  {{ ($org_selected and !$new_contact) ? 'disabled' : '' }} />
                            @error('contact.phone') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="newinquiry__block">
                    <h5>Details</h5>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="organization_code" class="form-label">
                                Member
                            </label>
                            <select class="form-select" wire:model.defer="member">
                                <option value="">Select Member</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }} {{ $member->lastname }}</option>
                                @endforeach
                            </select>
                            @error('member') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-7">
                            <label for="organization_code" class="form-label">
                                Source
                            </label>
                            <div class="dropdown">
                                <button class="dropdown-toggle form-select d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {!! $source_label !!}
                                </button>
                                <input type="hidden" wire:model="source" />
                                <ul class="dropdown-menu mt-4">
                                    <li><button class="dropdown-item" type="button" wire:click="set_source('')"><b>Select Source</b></button></li>
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
                        <div class="col-md-12 mt-3">
                            <label for="organization_code" class="form-label">
                                Rating
                            </label>
                            <div class="dropdown">
                                <button class="dropdown-toggle form-select d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {!! $rating_label !!}
                                </button>
                                <input type="hidden" wire:model="rating" />
                                <ul class="dropdown-menu mt-4">
                                    <li><button class="dropdown-item" type="button" wire:click="set_rating('')"><b>Select Rating</b></button></li>
                                    <li><button class="dropdown-item" type="button" wire:click="set_rating(1)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(1) !!}</div></button></li>
                                    <li><button class="dropdown-item" type="button" wire:click="set_rating(2)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(2) !!}</div></button></li>
                                    <li><button class="dropdown-item" type="button" wire:click="set_rating(3)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(3) !!}</div></button></li>
                                    <li><button class="dropdown-item" type="button" wire:click="set_rating(4)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(4) !!}</div></button></li>
                                    <li><button class="dropdown-item" type="button" wire:click="set_rating(5)"><div class="d-flex align-items-center gap-2">{!! $this->rating_draw_stars(5) !!}</div></button></li>
                                </ul>
                            </div>
                            @error('rating') <span class='text-danger'>{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recovered-account">
                                <label class="form-check-label" for="recovered-account">
                                    Recovered account
                                </label>
                              </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <label>Additional Documentation</label>
                </div>
            </div>
        </div>

        <button type="submit" class="newinquiry__submit">Submit Inquiry</button>
    </form>
</div>
