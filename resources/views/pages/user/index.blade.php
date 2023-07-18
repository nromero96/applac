@extends('layouts.app')


@section('content')


<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">

        <div class="row layout-spacing">
            <div class="col-lg-12 layout-top-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header pt-4">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 text-end">
                                <a href="{{ route('users.create') }}" class="btn btn-primary mb-4 ms-3 me-3">{{__("Add New")}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">{{__("Name")}}</th>
                                        <th scope="col">{{__("Role")}}</th>
                                        <th class="text-center" scope="col">{{__("Status")}}</th>
                                        <th class="text-center" scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>
                                                <div class="media">
                                                    <div class="avatar me-2">
                                                        <img alt="avatar" src="{{asset('storage/uploads/profile_images/'.$item->photo.'')}}" class="rounded-circle" />
                                                    </div>
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0 fw-bold">{{$item->name}}</h6>
                                                        <span>{{$item->email}}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(!empty($item->getRoleNames()))
                                                    @foreach($item->getRoleNames() as $v)
                                                    <p class="mb-0 fw-bold">{{ $v }}</p>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{$item->status == 'active' ? 'badge-light-success' : 'badge-light-danger'}} text-capitalize">{{$item->status}}</span>
                                            </td>
                                            <td class="text-center">
                                                <form class="deleteForm" action="{{ route('users.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="action-btns">
                                                        <a href="{{ route('users.edit', $item->id) }}" class="action-btn btn-edit bs-tooltip me-2" data-toggle="tooltip" data-placement="top" title="{{ __("Edit") }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                        </a>
                                                        <button type="button" class="action-btn border-0 bg-transparent btn-delete bs-tooltip" data-toggle="tooltip" data-placement="top" title="{{ __("Delete") }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


@endsection

<script>
// JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los formularios de eliminación
    var deleteForms = document.querySelectorAll('.deleteForm');

    // Agregar controlador de eventos de clic a cada botón de eliminación
    deleteForms.forEach(function(form) {
        var deleteButton = form.querySelector('.btn-delete');
        deleteButton.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("{{ __('Are you sure you want to delete this user?') }}")) {
                form.submit();
            }
        });
    });
});


</script>