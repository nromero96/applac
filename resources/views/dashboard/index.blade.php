@extends('layouts.app')


@section('content')

<div class="layout-px-spacing">

    @if(\Auth::user()->hasRole('Administrator'))
        <div class="middle-content container-xxl p-0">

            <div class="row layout-top-spacing">
                <div class="col-md-12">
                    <h4>{{__("Hi")}} <b>{{\Auth::user()->name}}</b> 👋</h5>
                    <p>{{__("Welcome to Latin American Cargo (LAC)")}}</p>
                </div>
            </div>

            @if($usersQuotes)
            
                <div class="row layout top-spacing">
                    <div class="col-md-12">
                        <h5>{{__("Users Quotes")}}</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Processing</th>
                                        <th>Attended</th>
                                        <th>Quote Sent</th>
                                        <th>Total</th>
                                        <th class="text-center py-0 px-0">4 stars<small class="d-block">(total)</small></th>
                                        <th class="text-center py-0 px-0">4 stars<small class="d-block">(assigned 24h)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usersQuotes as $userQuote)
                                        <tr>
                                            <td>{{ $userQuote['name'] }} {{ $userQuote['lastname'] }}</td>
                                            <td>{{ $userQuote['quotes']['Processing'] }}</td>
                                            <td>{{ $userQuote['quotes']['Attended'] }}</td>
                                            <td>{{ $userQuote['quotes']['Quote Sent'] }}</td>
                                            <td>{{ $userQuote['quotes']['Total'] }}</td>
                                            <td class="text-center py-0 px-0">{{ $userQuote['quotes']['4 stars'] }}</td>
                                            <td class="text-center py-0 px-0">{{ $userQuote['quotes']['4 stars last day'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            @endif

            <div class="row">

                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-six">
                        <div class="widget-heading">
                            <h6 class="">{{__("Statistics")}}</h6>
                            <div class="task-action">
                                <div class="dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" id="statistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </a>

                                    <div class="dropdown-menu left" aria-labelledby="statistics" style="will-change: transform;">
                                        <a class="dropdown-item" href="javascript:void(0);">{{__("View")}}</a>
                                        <a class="dropdown-item" href="javascript:void(0);">{{__("Download")}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-chart">
                            <div class="w-chart-section">
                                <div class="w-detail">
                                    <p class="w-title">{{__("Total Requests")}}</p>
                                    <p class="w-stats">423,964</p>
                                </div>
                                <div class="w-chart-render-one">
                                    <div id="total-users"></div>
                                </div>
                            </div>

                            <div class="w-chart-section">
                                <div class="w-detail">
                                    <p class="w-title">{{__("Completed")}}</p>
                                    <p class="w-stats">7,929</p>
                                </div>
                                <div class="w-chart-render-one">
                                    <div id="paid-visits"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="">{{__("Requests")}}</h6>
                                </div>
                                <div class="task-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="expenses" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu left" aria-labelledby="expenses" style="will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0);">{{__("This Week")}}</a>
                                            <a class="dropdown-item" href="javascript:void(0);">{{__("Last Week")}}</a>
                                            <a class="dropdown-item" href="javascript:void(0);">{{__("Last Month")}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-content">

                                <div class="w-info">
                                    <p class="value">141 <span>{{__('this week')}}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                                </div>
                                
                            </div>

                            <div class="w-progress-stats">                                            
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="">
                                    <div class="w-icon">
                                        <p>57%</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-three">
                        <div class="widget-heading">
                            <div class="">
                                <h5 class="">{{__("Requests")}}</h5>
                            </div>

                            <div class="task-action">
                                <div class="dropdown ">
                                    <a class="dropdown-toggle" href="#" role="button" id="uniqueVisitors" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </a>

                                    <div class="dropdown-menu left" aria-labelledby="uniqueVisitors">
                                        <a class="dropdown-item" href="javascript:void(0);">{{__("View")}}</a>
                                        <a class="dropdown-item" href="javascript:void(0);">{{__("Update")}}</a>
                                        <a class="dropdown-item" href="javascript:void(0);">{{__("Download")}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget-content">
                            <div id="uniqueVisits"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget-four">
                        <div class="widget-heading">
                            <h5 class="">{{__("Ratings")}}</h5>
                        </div>
                        <div class="widget-content">
                            <div class="vistorsBrowser">

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    </div>
                                    <div class="w-browser-details">
                                        <div class="w-browser-info">
                                            <h6>5 {{__("stars")}}</h6>
                                            <p class="browser-count">65%</p>
                                        </div>
                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 65%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    </div>
                                    <div class="w-browser-details">
                                        
                                        <div class="w-browser-info">
                                            <h6>4 {{__("stars")}}</h6>
                                            <p class="browser-count">25%</p>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 35%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    </div>
                                    <div class="w-browser-details">

                                        <div class="w-browser-info">
                                            <h6>3 {{__("stars")}}</h6>
                                            <p class="browser-count">20%</p>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    </div>
                                    <div class="w-browser-details">

                                        <div class="w-browser-info">
                                            <h6>2 {{__("stars")}}</h6>
                                            <p class="browser-count">15%</p>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    </div>
                                    <div class="w-browser-details">

                                        <div class="w-browser-info">
                                            <h6>1 {{__("star")}}</h6>
                                            <p class="browser-count">10%</p>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
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
    @else

        <div class="middle-content container-xxl p-0">

            <div class="row layout-top-spacing">
                <div class="col-md-12">
                    <h4>{{__("Hi")}} <b>{{\Auth::user()->name}}</b> 👋</h5>
                    <p>{{__("Welcome to Latin American Cargo (LAC)")}}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('quotations.index') }}" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/my-quotes-46535656.png')}}" alt="My Quotations">
                        <span>{{__("My Quotations")}}</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('usefullinks.index') }}" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/useful-links-34587.png')}}" alt="Useful Links">
                        <span>{{__("Useful Links")}}</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('notes.index') }}" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/my-notes-453566.png')}}" alt="My Notes">
                        <span>{{__("My Notes")}}</span>
                    </a>
                </div>
            </div>
        
        </div>
    @endif

</div>

@endsection