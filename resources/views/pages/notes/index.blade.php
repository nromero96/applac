@extends('layouts.app')


@section('content')

<div class="layout-px-spacing">

    <div class="middle-content container-xxl p-0">
        
        <div class="row app-notes layout-top-spacing" id="cancel-row">
            <div class="col-lg-12">
                <div class="app-hamburger-container">
                    <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu chat-menu d-xl-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                </div>

                <div class="app-container">
                    
                    <div class="app-note-container">

                        <div class="app-note-overlay"></div>

                        <div class="tab-title">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12 mb-5">
                                    <ul class="nav nav-pills d-block" id="pills-tab3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link list-actions active" id="all-notes"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> {{__('All Notes')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link list-actions" id="note-fav"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg> {{__('Favourites')}}</a>
                                        </li>
                                    </ul>

                                    <hr/>

                                    <p class="group-section"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7" y2="7"></line></svg> {{__('Tags')}}</p>

                                    <ul class="nav nav-pills d-block group-list" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link list-actions g-dot-primary" id="note-personal">{{__('Personal')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link list-actions g-dot-warning" id="note-work">{{__('Work')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link list-actions g-dot-success" id="note-social">{{__('Social')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link list-actions g-dot-danger" id="note-important">{{__('Important')}}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 text-center">
                                    <a id="btn-add-notes" class="btn btn-secondary w-100" href="javascript:void(0);">{{__('Add Note')}}</a>
                                </div>
                            </div>
                        </div>

                        <div id="ct" class="note-container note-grid">

                            @foreach ($notes as $note)
                            <div class="note-item all-notes {{$note->tag}} {{$note->favourites}}">
                                <div class="note-inner-content">
                                    <div class="note-content">
                                        <p class="note-title" data-noteTitle="{{$note->title}}">{{$note->title}}</p>
                                        <p class="meta-time">{{ \Carbon\Carbon::parse($note->created_at)->format('d/m/Y') }}</p>
                                        <div class="note-description-content">
                                            <p class="note-description" data-noteDescription="{{$note->description}}">
                                                {{ \Illuminate\Support\Str::limit($note->description, 50) }}
                                                @if(strlen($note->description) > 50)
                                                    <small class="read-more-link"><a href="#" class="read-more text-primary">Read more ></a></small>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="note-action" data-noteid="{{$note->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star fav-note" data-noteid="{{$note->id}}"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 delete-note" data-noteid="{{$note->id}}"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 edit-note" data-noteid="{{$note->id}}" ><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                    </div>
                                    <div class="note-footer">
                                        <div class="tags-selector btn-group">
                                            <a class="nav-link dropdown-toggle d-icon label-group" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
                                                <div class="tags">
                                                    <div class="g-dot-personal"></div>
                                                    <div class="g-dot-work"></div>
                                                    <div class="g-dot-social"></div>
                                                    <div class="g-dot-important"></div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right d-icon-menu">
                                                <a class="note-personal label-group-item label-personal dropdown-item position-relative g-dot-personal" href="javascript:void(0);" data-noteid="{{$note->id}}"> Personal</a>
                                                <a class="note-work label-group-item label-work dropdown-item position-relative g-dot-work" href="javascript:void(0);" data-noteid="{{$note->id}}"> Work</a>
                                                <a class="note-social label-group-item label-social dropdown-item position-relative g-dot-social" href="javascript:void(0);" data-noteid="{{$note->id}}"> Social</a>
                                                <a class="note-important label-group-item label-important dropdown-item position-relative g-dot-important" href="javascript:void(0);" data-noteid="{{$note->id}}"> Important</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="notesMailModal" tabindex="-1" role="dialog" aria-labelledby="notesMailModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title add-title" id="notesMailModalTitleeLabel">{{__('Add Task')}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="notes-box">
                                    <div class="notes-content">  

                                        <form action="javascript:void(0);" id="notesMailModalTitle">
                                            <input type="hidden" name="note_id" id="note_id" value="">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="d-flex note-title">
                                                        <input type="text" name="ntitle" id="n-title" class="form-control" maxlength="250" placeholder="{{__('Title')}}">
                                                    </div>
                                                    <span class="validation-text"></span>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="d-flex note-description">
                                                        <textarea name="ndescription" id="n-description" class="form-control" maxlength="1000" placeholder="{{__('Description')}}" rows="6"></textarea>
                                                    </div>
                                                    <span class="validation-text"></span>
                                                    <span class="d-inline-block mt-1 text-danger d-none" id="textmaxlen">{{__('Maximum Limit 1000 characters')}}</span>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                
                                <button class="btn" data-bs-dismiss="modal">{{__('Discard')}}</button>
                                <button id="btn-n-save" class="float-left btn btn-primary">{{__('Save')}}</button>
                                <button id="btn-n-add" class="btn btn-primary">{{__('Add')}}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal View -->
                <div class="modal fade" id="notesViewModal" tabindex="-1" role="dialog" aria-labelledby="notesViewModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title add-title" id="notesViewModalTitleeLabel">{{__('...')}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                            
                            <div class="modal-body pt-3">
                                <div class="notes-box">
                                    <div class="notes-content">  
                                        <p class="note-description-view" id="note-description-view">...</p>
                                        <small class="note-created-at-view mt-2 d-block" id="note-created-at-view">...</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

    </div>
    
</div>

@endsection