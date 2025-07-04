@php
    $user = auth('web')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>My Account - {{ config('app.name') }}</title>

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
                <span class="elementor-button-text">View orders</span>
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


<section class="elementor-section elementor-top-section elementor-element elementor-element-2fd6917c elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="2fd6917c" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-14915c4c" data-id="14915c4c" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-ba34c8c e-my-account-tabs-horizontal elementor-widget elementor-widget-woocommerce-my-account" data-id="ba34c8c" data-element_type="widget" data-settings="{&quot;customize_dashboard_check&quot;:&quot;yes&quot;}" data-widget_type="woocommerce-my-account.default">
<div class="elementor-widget-container">


    <div class="e-my-account-tab e-my-account-tab__dashboard ">		
<div class="woocommerce">			




<div class="woocommerce-MyAccount-content">
<div class="woocommerce-MyAccount-content-wrapper"><div class="woocommerce-notices-wrapper"></div>
<p>
Hello <strong>{{$user->first_name}} {{$user->last_name}}</strong> (not <strong>{{$user->first_name}} {{$user->last_name}}</strong>? <a href="/logout">Log out</a>)
</p>

<p>
From your account dashboard you can view your <a href="/my-account/orders">recent orders</a> and <a href="/my-account/edit-account/">edit your password and account details</a>.</p>

</div></div>
</div>		</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>


@endsection('content')



@push('js')

{{-- extra jss files here --}}

@endpush
