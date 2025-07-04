@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Reset Password - {{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}

        {{-- Login Reg --}}
        <link rel='stylesheet' id='elementor-post-1306-css' href='/uploads/elementor/css/post-1306b08c.css?ver={{config('app.version')}}' type='text/css' media='all' />
        <link rel="stylesheet" href="/plugins/elementor-pro/assets/css/widget-woocommerce.min.css?ver={{config('app.version')}}">
@endpush




@section('content')
    


<div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306" data-elementor-post-type="page">
  
<section class="elementor-section elementor-top-section elementor-element elementor-element-2fd6917c elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="2fd6917c" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-14915c4c" data-id="14915c4c" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-ba34c8c e-my-account-tabs-horizontal elementor-widget elementor-widget-woocommerce-my-account" data-id="ba34c8c" data-element_type="widget" data-settings="{&quot;customize_dashboard_check&quot;:&quot;yes&quot;}" data-widget_type="woocommerce-my-account.default">
<div class="elementor-widget-container">
<link rel="stylesheet" href="/plugins/elementor-pro/assets/css/widget-woocommerce.min.css?ver={{config('app.version')}}"><div class="e-my-account-tab e-my-account-tab__dashboard ">			<span class="elementor-hidden">[woocommerce_my_account]</span>
<div class="woocommerce"><div class="woocommerce-notices-wrapper"></div>

<div class="u-columns col2-set row" id="customer_login">

<div class="u-column1 col-12 col-md-6">


<h2>Reset Password</h2>

<form onsubmit="resetForm(event)" class="woocommerce-form woocommerce-form-login login" method="post" style="">

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="password">Password&nbsp;<span class="required">*</span></label>
<span class="password-input"><input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password"><span class="show-password-input"></span></span>
</p>
<input type="hidden" name="token" id="token" value="{!! $token !!}">
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
  <label for="password">Confirm Password&nbsp;<span class="required">*</span></label>
  <span class="password-input"><input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="confirm_password" id="confirm_password"><span class="show-password-input"></span></span>
  </p>



<p class="form-row">
<input type="hidden" id="woocommerce-login-nonce" name="woocommerce-login-nonce" value="bcc86bed46"><input type="hidden" name="_wp_http_referer" value="/my-account/">				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="Reset">Reset</button>
</p>


</form>


</div>


</div>

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
