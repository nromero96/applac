    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script> var baseurl="{{url('')}}"; </script>
    <script src="{{ asset('plugins/src/global/vendors.min.js')}} "></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    @if ($page_name != 'coming_soon' && $page_name != 'contact_us' && $page_name != 'error404' && $page_name != 'error500' && $page_name != 'error503' && $page_name != 'faq' && $page_name != 'helpdesk' && $page_name != 'maintenence' && $page_name != 'privacy' && $page_name != 'auth_boxed' && $page_name != 'auth_default')
        <script src="{{ asset('plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('plugins/src/mousetrap/mousetrap.min.js') }}"></script>
        <script src="{{ asset('plugins/src/waves/waves.min.js') }}"></script>
        <script src="{{ asset('layouts/vertical-light-menu/app.js') }}"></script>
        <script src="{{  asset('assets/js/custom.js') }}"></script>
    @endif
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    @switch($page_name)
        @case('dashboard')
            <script src="{{ asset('plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
            <script>
                $(() => $('[data-toggle="tooltip"]').tooltip())
            </script>
            {{-- Dashboard --}}
            {{-- <script src="{{ asset('plugins/src/apex/apexcharts.min.js') }}"></script> --}}
            <script src="{{ asset('assets/js/dashboard/dash_1.js') }}?v={{ config('app.version') }}"></script>
            @break
        @case('users')
            {{-- Users --}}

            @break
        @case('quotations')
            {{-- All quotes --}}

            {{-- swal --}}
            <script src="{{asset('plugins/src/sweetalerts2/sweetalerts2.min.js')}}"></script>
            <script src="{{asset('plugins/src/daterangepicke/moment.min.js')}}"></script>
            <script src="{{asset('plugins/src/daterangepicke/daterangepicker.js')}}"></script>
            <script src="{{asset('assets/js/apps/quotations/commercial/list.js')}}?v={{ config('app.version') }}"></script>
            <script>
                $(() => $('[data-toggle="tooltip"]').tooltip())
            </script>
            @break
        @case('quotations_show')
            {{-- All quotes --}}
            <script src="{{asset('plugins/src/html2canvas/html2canvas.min.js')}}"></script>
            <script src="{{asset('plugins/src/print-js/print.min.js')}}"></script>
            {{-- swal --}}
            <script src="{{asset('plugins/src/sweetalerts2/sweetalerts2.min.js')}}"></script>

            <script src="{{asset('plugins/src/daterangepicke/moment.min.js')}}"></script>
            <script src="{{asset('plugins/src/daterangepicke/daterangepicker.js')}}"></script>

            <script src="{{asset('assets/js/apps/quotations/commercial/show.js')}}?v={{ config('app.version') }}"></script>

            <script>
                //inicar tooltips
                $(() => $('[data-toggle="tooltip"]').tooltip())
            </script>

            @break
        @case('suppliers')
            <script src="{{ asset('plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
            <script src="{{ asset('plugins/src/tagify/tagify.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/supplier.js') }}?v={{ config('app.version') }}"></script>
            @break

        @case('suppliercreate')
            <script src="{{ asset('plugins/src/filepond/filepond.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/supplier-create.js') }}"></script>
            @break

        @case('supplieredit')
            <script src="{{ asset('plugins/src/filepond/filepond.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
            <script src="{{ asset('plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/supplier-edit.js') }}"></script>
            @break

        @case('suppliershow')
            <script src="{{asset('plugins/src/html2canvas/html2canvas.min.js')}}"></script>
            <script src="{{asset('plugins/src/print-js/print.min.js')}}"></script>
            <script src="{{ asset('plugins/src/tagify/tagify.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/supplier-show.js') }}"></script>
            @break

        @case('organizations')
            <script src="{{ asset('plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
            <script src="{{ asset('plugins/src/tagify/tagify.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/organization.js') }}?v={{ config('app.version') }}"></script>
            @break

        @case('organizationsshow')
            <script src="{{asset('plugins/src/html2canvas/html2canvas.min.js')}}"></script>
            <script src="{{asset('plugins/src/print-js/print.min.js')}}"></script>
            <script src="{{ asset('plugins/src/tagify/tagify.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/organization.js') }}?v={{ config('app.version') }}"></script>
            @break

        @case('customers')
            <script src="{{ asset('plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/contact.js') }}?v={{ config('app.version') }}"></script>
            @break

        @case('calendar')
            {{-- All Calendar --}}
            <script src="{{asset('plugins/src/fullcalendar/fullcalendar.min.js')}}"></script>
            <script src="{{asset('plugins/src/uuid/uuid4.min.js')}}"></script>

            <script src="{{asset('plugins/src/fullcalendar/custom-fullcalendar.js')}}"></script>
            @break

        @case('notes')
            {{-- All notes --}}
            <script src="{{asset('assets/js/apps/notes.js')}}?v={{ config('app.version') }}"></script>
            @break

        @default
            <script>console.log('No custom script available.')</script>
    @endswitch

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
