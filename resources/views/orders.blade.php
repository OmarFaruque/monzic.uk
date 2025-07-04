@php
    $user = auth('web')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Orders - {{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}

        {{-- Login Reg --}}
        <link rel='stylesheet' id='elementor-post-1306-css' href='/uploads/elementor/css/post-1306b08c.css?ver={{config('app.version')}}' type='text/css' media='all' />
        <link rel="stylesheet" href="/plugins/elementor-pro/assets/css/widget-woocommerce.min.css?ver={{config('app.version')}}">
@endpush




@section('content')
    

<div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-5b3eada hide-logged-out elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5b3eada" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-8d5a021" data-id="8d5a021" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-2e88fe6 elementor-align-center elementor-widget__width-inherit elementor-widget-mobile__width-initial elementor-widget elementor-widget-button" data-id="2e88fe6" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-sm" href="/my-account/orders">
    <span class="elementor-button-content-wrapper">
    <span class="elementor-button-icon">
<i aria-hidden="true" class="far fa-file-alt"></i>			</span>
                <span class="elementor-button-text">View Orders</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>

<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-37347fe" data-id="37347fe" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-6a1849c elementor-align-center elementor-widget__width-inherit elementor-widget elementor-widget-button" data-id="6a1849c" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-sm" href="/my-account/edit-account">
    <span class="elementor-button-content-wrapper">
    <span class="elementor-button-icon">
<i aria-hidden="true" class="fas fa-user-circle"></i>			</span>
                <span class="elementor-button-text">Edit Account</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-82a0911" data-id="82a0911" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-e55ad50 elementor-align-center elementor-widget__width-inherit elementor-widget elementor-widget-button" data-id="e55ad50" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-sm" href="/my-account/logout">
    <span class="elementor-button-content-wrapper">
    <span class="elementor-button-icon">
<i aria-hidden="true" class="fas fa-sign-out-alt"></i>			</span>
                <span class="elementor-button-text">Log Out</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<style>
    td .btn-sm{
        background-color: #FFF;
        color: #444;
        font-weight: bold;
        border: 2px solid #387a8c;
    }
    #policyModal th{
        color: #387a8c;
    }
</style>


<div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-5b3eada hide-logged-out elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5b3eada" data-element_type="section">

        <div class="card">
            <div class="card-body table-responsive">
                @if(count($orders) == 0)
                <div class="alert alert-info py-5"> You currently have no order. <a href="/order/get-quote"> Get a price now</a> </div>
                @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{$order->policy_number}}</td>
                            <td>£{{ number_format($order->update_price, 2) }}</td>
                            <td><button onclick="showPolicyDetails({{$order->id}})"    class="btn m-1 btn-sm btn-primary">View Details</button> <a target="_blank" href="/view-order/{{$order->policy_number}}"  class="btn btn-primary m-1 btn-sm">Login</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        



    </section>

</div>




<!-- Modal -->
<div class="modal fade auth_modal" id="policyModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 950px; width: 100%;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="authModalLabel">Order Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306" data-elementor-post-type="page">
                <section class="elementor-section elementor-top-section elementor-element elementor-element-5b3eada hide-logged-out elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5b3eada" data-element_type="section">

            <div class="text-end">
            <div style="display: inline-block; width:100%; max-width:300px">
            <table class="table text-start table-sm">
                <tbody>
                    <tr><th>Subtotal:</th><td class="cpw_subtotal"></td></tr>
                    <tr><th>Discount:</th><td class="cpw_discount"></td></tr>
                    <tr><th style="border-top: 2px solid #CCC">Total:</th><td  style="border-top: 2px solid #CCC"class="cpw_total"></td></tr>
                </tbody>
            </table>
            </div>
            </div>
            <table class="table">
                <tbody>
                    <tr><th>Registration Number:</th><td id="reg_number"></td></tr>
<tr><th>Vehicle:</th><td id="vehicle_make"></td></tr>
<tr><th>Start Time:</th><td id="start_time"></td></tr>
<tr><th>End Time:</th><td id="end_time"></td></tr>
<tr><th>Reason:</th><td id="cover_reason"></td></tr>
<tr><th>Title:</th><td id="title"></td></tr>
<tr><th>First Name(s):</th><td id="first_name"></td></tr>
<tr id="tr_middle_name"><th>Middle Name(s):</th><td id="middle_name"></td></tr>
<tr><th>Last Name:</th><td id="last_name"></td></tr>
<tr><th>Date of Birth:</th><td id="date_of_birth"></td></tr>
<tr><th>Address:</th><td id="address"></td></tr>
<tr><th>Occupation:</th><td id="occupation"></td></tr>
<tr><th>Type:</th><td id="licence_type"></td></tr>
<tr><th>Held Duration:</th><td id="licence_held_duration"></td></tr>
<tr><th>Value:</th><td id="vehicle_type"></td></tr>
                </tbody>
            </table>
                </section>
            </div>
        </div>
        <div class="modal-footer ">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
@endsection('content')



@push('js')

{{-- extra jss files here --}}

@endpush
