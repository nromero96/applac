    <link href="{{ asset('layouts/vertical-light-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/vertical-light-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('layouts/vertical-light-menu/loader.js') }}"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">

    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />

    @if ($page_name != 'coming_soon' && $page_name != 'contact_us' && $page_name != 'error404' && $page_name != 'error500' && $page_name != 'error503' && $page_name != 'faq' && $page_name != 'helpdesk' && $page_name != 'maintenence' && $page_name != 'privacy' && $page_name != 'auth_boxed' && $page_name != 'auth_default')
        <link href="{{ asset('layouts/vertical-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('layouts/vertical-light-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    @endif
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @switch($page_name)
        @case('dashboard')
            {{-- Dashboard --}}
            <link href="{{ asset('plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/dashboard/dash_1.css') }}?v={{ config('app.version') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/dashboard/dash_1.css') }}?v={{ config('app.version') }}" rel="stylesheet" type="text/css" />
            @break

        @case('sales')
            {{-- Dashboard 1 --}}
            <link href="{{ asset('plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">

            <link href="{{ asset('assets/css/light/components/list-group.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/components/list-group.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/dark/dashboard/dash_2.css') }}" rel="stylesheet" type="text/css" />
            @break

        @case('users')
            {{-- Users --}}
            <link href="{{ asset('assets/css/light/users/users-list.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/users/users-list.css') }}" rel="stylesheet" type="text/css" />
            @break

        @case('myprofile')
            {{-- Users --}}

            @break

        @case('userscreate')
            {{-- Users Create --}}
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />

            @break

        @case('usersedit')
            {{-- Users Edit --}}
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            @break

        @case('rolecreate')
            {{-- Users Create --}}
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />

            @break

        @case('roleedit')
            {{-- Users Create --}}
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/elements/alert.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/elements/alert.css') }}" rel="stylesheet" type="text/css" />

            @break

        @case('quotations')
            {{-- All quotes --}}
            <link rel="stylesheet" type="text/css" href="{{asset('plugins/src/daterangepicke/daterangepicker.css')}}">
            <link rel="stylesheet" type="text/css" href="{{asset('assets/css/light/apps/invoice-list.css')}}?v={{ config('app.version') }}">

            <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dark/apps/invoice-list.css')}}?v={{ config('app.version') }}">

            {{-- swal --}}
            <link rel="stylesheet" type="text/css" href="{{asset('plugins/src/sweetalerts2/sweetalerts2.css')}}">

            @break

        @case('quotations_show')
            {{-- All quotes --}}
            <style>
                .table tbody tr td {
                    white-space: normal;
                }
                .recovered_account {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 4px;
                    background-color: #F5F5F5;
                    border-radius: 10rem;
                    font-weight: 700;
                    color: #4CBB17;
                    padding: 6px 12px;
                }
                .recovered_account img {
                    width: 18px;
                    height: 18px;
                    object-fit: contain;
                }
            </style>

            {{-- swal --}}
            <link rel="stylesheet" type="text/css" href="{{asset('plugins/src/sweetalerts2/sweetalerts2.css')}}">

            @break

        @case('suppliers')

            <link href="{{ asset('plugins/src/tagify/tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/dark/tagify/custom-tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/light/tagify/custom-tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/dark/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />

            @break

        @case('organizations')
            <link href="{{ asset('plugins/src/tagify/tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/dark/tagify/custom-tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/light/tagify/custom-tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/dark/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
        @break

        @case('suppliercreate')
            <link href="{{ asset('assets/css/light/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/src/filepond/filepond.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />

            @break

        @case('supplieredit')
            <link href="{{ asset('assets/css/light/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/src/filepond/filepond.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
            @break

        @case('suppliershow')
            <link href="{{ asset('plugins/src/tagify/tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/dark/tagify/custom-tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('plugins/css/light/tagify/custom-tagify.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/components/media_object.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/components/media_object.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/components/list-group.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/components/list-group.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/light/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('assets/css/dark/apps/suppliers.css') }}" rel="stylesheet" type="text/css" />
            @break

        @case('customers')
            <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/apps/contacts.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/dark/apps/contacts.css') }}" rel="stylesheet" type="text/css" />
            @break

        @case('calendar')
            {{-- All Calendar --}}
            <link href="{{asset('plugins/src/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />

            <link href="{{asset('plugins/css/light/fullcalendar/custom-fullcalendar.css')}}" rel="stylesheet" type="text/css" />
            <link href="{{asset('assets/css/light/components/modal.css')}}" rel="stylesheet" type="text/css">

            <link href="{{asset('plugins/css/dark/fullcalendar/custom-fullcalendar.css')}}" rel="stylesheet" type="text/css" />
            <link href="{{asset('assets/css/dark/components/modal.css')}}" rel="stylesheet" type="text/css">
            @break

        @case('notes')
            {{-- All quotes --}}
            <link href="{{ asset('assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/light/apps/notes.css') }}" rel="stylesheet" type="text/css" />

            <link href="{{ asset('assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
            <link href="{{ asset('assets/css/dark/apps/notes.css') }}" rel="stylesheet" type="text/css" />
            @break


        @default
            <script>console.log('No custom Styles available.')</script>
    @endswitch

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
