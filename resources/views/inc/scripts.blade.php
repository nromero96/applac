    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script> var baseurl="{{url('')}}"; </script>
    <script src="{{ asset('plugins/src/global/vendors.min.js')}} "></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

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
            {{-- Dashboard --}}
            <script src="{{ asset('plugins/src/apex/apexcharts.min.js') }}"></script>
            <script src="{{ asset('assets/js/dashboard/dash_1.js') }}"></script>
            @break
        @case('users')
            {{-- Users --}}

            @break
        @case('quotations')
            {{-- All quotes --}}
            <script src="{{asset('plugins/src/table/datatable/datatables.js')}}"></script>

            {{-- colvis --}}
            <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>

            <script src="{{asset('plugins/src/table/datatable/button-ext/dataTables.buttons.min.js')}}"></script>
            {{-- tomSelect --}}
            <script src="{{asset('plugins/src/tomSelect/tom-select.base.js')}}"></script>
            
            <script src="{{asset('plugins/src/tomSelect/custom-tom-select.js')}}"></script>

            <script src="{{asset('assets/js/apps/quotations/commercial/list.js')}}"></script>
            @break
        @case('quotationspersonal')
            {{-- All quotes --}}
            <script src="{{asset('plugins/src/table/datatable/datatables.js')}}"></script>
            <script src="{{asset('plugins/src/table/datatable/button-ext/dataTables.buttons.min.js')}}"></script>
            <script src="{{asset('assets/js/apps/quotations/personal/list.js')}}"></script>
            @break

        @case('suppliers')
            <script src="{{ asset('plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
            <script src="{{ asset('plugins/src/tagify/tagify.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/supplier.js') }}"></script>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
            <script src="{{ asset('assets/js/apps/supplier-edit.js') }}"></script>
            @break

        @case('suppliershow')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
            <script src="{{ asset('plugins/src/tagify/tagify.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/supplier-show.js') }}"></script>
            @break

        @case('customers')
            <script src="{{ asset('plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
            <script src="{{ asset('assets/js/apps/contact.js') }}"></script>
            @break

        @case('calendar')
            {{-- All Calendar --}}
            <script src="{{asset('plugins/src/fullcalendar/fullcalendar.min.js')}}"></script>
            <script src="{{asset('plugins/src/uuid/uuid4.min.js')}}"></script>

            <script src="{{asset('plugins/src/fullcalendar/custom-fullcalendar.js')}}"></script>
            @break

        @case('notes')
            {{-- All notes --}}
            <script src="{{asset('assets/js/apps/notes.js')}}"></script>
            @break

        @default
            <script>console.log('No custom script available.')</script>
    @endswitch

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->