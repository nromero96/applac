<div>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 filtered-list-search layout-spacing align-self-center">
            <form class="d-flex my-2 my-lg-0" wire:submit.prevent="render()">
                <div class="w-50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" class="form-control product-search" wire:model.debounce.500ms="query" placeholder="Search Organizations...">
                </div>
                <button class="btn w-25 ms-1 btn-secondary _effect--ripple waves-effect waves-light">{{ __('Filter') }}</button>
            </form>

        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 text-sm-right text-center layout-spacing align-self-center">
            <div class="d-flex justify-content-sm-end justify-content-center">
                <a href="{{ route('organization.create') }}" class="btn btn-primary ms-3 me-3" style="line-height: 25px;">{{__("Add New")}}</a>
                <div class="switch align-self-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list view-list active-view"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3" y2="6"></line><line x1="3" y1="12" x2="3" y2="12"></line><line x1="3" y1="18" x2="3" y2="18"></line></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid view-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="searchable-items list">
        <div class="items items-header-section">
            <div class="item-content">
                <div class="d-inline-flex">
                    <h4>Code</h4>
                </div>
                <div class="user-email">
                    <h4>Name</h4>
                </div>
                <div class="user-location">
                    <h4 style="margin-left: 0;">Addresses</h4>
                </div>
                <div class="action-btn">
                </div>
            </div>
        </div>

        @if ($organizations->count())
            @foreach ($organizations as $org)
                <div class="items">
                    <div class="item-content itlist">
                        <div class="user-profile">
                            <div class="user-meta-info" style="margin-left: 16px">
                                <p class="user-work">{{ $org->code }}</p>
                            </div>
                        </div>
                        <div class="user-email">
                            <p>{{ $org->name }}</p>
                        </div>
                        <div class="user-location">
                            <ul>
                                @foreach ($org->addresses as $address)
                                    <li>{{ $address }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="action-btn">
                            <a href="{{ route('organization.show', $org->id) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye show"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                            <a href="{{ route('organization.edit', $org->id ) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </a>
                            <button style="background: none; border: none;" wire:click="destroy({{ $org->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-minus delete"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center p-3"><b>No data found</b></p>
        @endif

    </div>

    <div class="listpagination">
        {{ $organizations->links('livewire::bootstrap') }}
    </div>

</div>
