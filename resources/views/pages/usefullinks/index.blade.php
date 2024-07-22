@extends('layouts.app')


@section('content')

<div class="layout-px-spacing">

        <div class="middle-content container-xxl p-0">

            <div class="row layout-top-spacing">
                <div class="col-md-12">
                    
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <a href="https://www.latinamericancargo.com/new-11-incoterms-2020-rules/" target="_blank" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/incoterms-icon.png')}}" alt="Incoterms Guide">
                        <span>{{__("Incoterms Guide")}}</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="https://www.latinamericancargo.com/ocean-shipping-containers-types-sizes-dimensions/" target="_blank" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/containers-icon.png')}}" alt="Container Types and Sizes">
                        <span>{{__("Container Types and Sizes")}}</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="#" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/IMO-Classification-icon.png')}}" alt="IMO Classification">
                        <span>{{__("IMO Classification")}}</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="https://www.latinamericancargo.com/glossary/" target="_blank" class="usr-link mb-3 w-100">
                        <img src="{{asset('assets/img/glossary-icon.png')}}" alt="Freight Glossary">
                        <span>{{__("Freight Glossary")}}</span>
                    </a>
                </div>
            </div>
        
        </div>

</div>

@endsection