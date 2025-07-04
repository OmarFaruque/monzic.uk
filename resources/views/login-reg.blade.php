@php
    
    $isCheckoutPage = true;

@endphp


@extends('templates.page')


@push('meta')
    <title>Login or Signup - {{ config('app.name') }}</title>

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
<div class="d-flex" style="flex-direction:column; height: 100%;">

<h2>Login</h2>

<form onsubmit="loginForm(event)" class="woocommerce-form woocommerce-form-login login" method="post" style="flex:1">


<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="username">Email address&nbsp;<span class="required">*</span></label>
<input type="text" type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="">			</p>
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="password">Password&nbsp;<span class="required">*</span></label>
<span class="password-input"><input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password"><span class="show-password-input"></span></span>
</p>


<p class="form-row">
<input type="hidden" id="woocommerce-login-nonce" name="woocommerce-login-nonce" value="bcc86bed46"><input type="hidden" name="_wp_http_referer" value="/my-account/">				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="Log in">Log in</button>


<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme ms-3">
  <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever"> <span>Remember me</span>
  </label>

</p>
<p class="woocommerce-LostPassword lost_password py-3">
<a href="" data-bs-toggle="modal" data-bs-target="#fgetModal">Lost your password?</a>
</p><br><br>

</form>

</div>

</div>

<div class="u-column2 col-12 col-md-6 mt-4 mt-md-0">

  <div class="d-flex" style="flex-direction:column; height: 100%;">
<h2>Register</h2>

<form onsubmit="registerForm(event)" method="post" class="woocommerce-form woocommerce-form-register register" style="flex:1">



<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="reg_first_name">First Name&nbsp;<span class="required">*</span></label>
<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="reg_first_name"  value="" required>				</p>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="reg_last_name">Last Name&nbsp;<span class="required">*</span></label>
    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="reg_last_name"  value="" required>				</p>
    

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="" required>			</p>


<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="reg_password">Password&nbsp;<span class="required">*</span></label>
<span class="password-input"><input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" required autocomplete="new-password"><span class="show-password-input"></span></span>
</p>


<div class="woocommerce-privacy-policy-text"><p>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our <a href="/privacy-policy" class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.</p>
</div>
<p class="woocommerce-form-row form-row">
<input type="hidden" id="woocommerce-register-nonce" name="woocommerce-register-nonce" value="b239a860bd"><input type="hidden" name="_wp_http_referer" value="/my-account/">
<div class="sbutton"><button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="Register">Register</button></div>
</p><br>


</form>
  </div>
</div>

</div>

</div>		</div>
</div>
</div>

</div>
</div>
</div>
</section>
<section class="elementor-section elementor-top-section elementor-element elementor-element-3a49f5c elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="3a49f5c" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-ed52f2e" data-id="ed52f2e" data-element_type="column">
<div class="elementor-widget-wrap">
        </div>
</div>
</div>
</section>
</div>




<!-- FORGET Modal -->
<div class="modal fade" id="fgetModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="authModalLabel">Forgot Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">We will send a password reset link to your email address</div>
            <form onsubmit="forgotForm(event)">
                <div class="mb-3">
                  <label for="fgt_email" class="form-label">Email Address</label>
                  <input  class="form-control" name="email" id="fgt_email" required>
                </div>
                <div class="sbutton"><button type="submit" class="btn btn-primary w-100 py-3 pay_btn">Submit</button></div>
              </form>
            
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
