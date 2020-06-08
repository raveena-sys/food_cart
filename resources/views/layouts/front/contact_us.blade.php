@extends('layouts.app')

@section('content')
<section class="home_bg" style= 'background-image: url("{{asset(isset($cms->background_image)?'/uploads/cms/'.$cms->background_image:'/img/home_bg.jpg')}}") !important;'>>
    <div class="container">

        <div class="contact_us">
            <div class="conact_hd">
                <img src="img/send_ico.png">
                <h2>{!!isset($cms->page_title)?ucfirst($cms->page_title):'Contact Us'!!}</h2>
                {!!isset($cms->page_content)?'<p>'.ucfirst($cms->page_content).'</p>':''!!} 
                <div class="clearfix"></div>
            </div>
            <form id="add_contactus_form" method="POST" class="needs-validation" enctype="multipart/form-data" action="{{URL::To('add_contactus')}}">
                {{csrf_field()}}
                <div class="contact_inner">
                    <li>
                        <input type="text" placeholder="First Name" name="first_name">
                    </li>
                    <li>
                        <input type="text" placeholder="Last Name" name="last_name">
                    </li>
                    <li>
                        <input type="text" placeholder="Company Name" name="company_name">
                    </li>
                    <li>
                        <input type="text" placeholder="Email" name="email">
                    </li>
                    <li>
                        <input type="text" placeholder="Contact Number" name="phone_number">
                    </li>
                    <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" type="submit">Send<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>
                </div>
            </form>
            {!! JsValidator::formRequest('App\Http\Requests\ContactUsRequest','#add_contactus_form')
            !!}
        </div>

        <div class="clearfix"></div>
    </div>
</section>
<div class="clearfix"></div>
@endsection
