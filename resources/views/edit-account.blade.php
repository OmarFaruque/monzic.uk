@php
    $user = auth('web')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Edit Account - {{ config('app.name') }}</title>

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
   
   .edform .form-group{
    margin-bottom: 30px;

   }
   .edform .blank{
    font-size: 14px;
   }
   .edform .require{
    color: red;
    font-size: 18px;
    margin-left: 7px;
   }
   .form-control{
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-family: 'Roboto', sans-serif;
    font-size: 16px;
    color: #333;
    background-color: #f4f4f4;
    transition: border-color 0.3s, box-shadow 0.3s;

   }

</style>


<div data-elementor-type="wp-page" data-elementor-id="1306" class="elementor elementor-1306" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-5b3eada hide-logged-out elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5b3eada" data-element_type="section">

        <div class="card">
            <div class="card-body table-responsive edform py-4">
          
                <form onsubmit="updateProfile(event)" autocomplete="off">
                    <div class="row">
                        <div class="col-12 col-md-6 form-group">
                            <label for="first_name">First Name <span class="require">*</span></label>
                            <input value="{{$user->first_name}}" name="first_name" id="first_name" class="form-control" required>
                        </div>

                        <div class="col-12 col-md-6 form-group">
                            <label for="last_name">Last Name <span class="require">*</span></label>
                            <input value="{{$user->last_name}}" name="last_name" id="last_name" class="form-control" required>
                        </div>

                        <div class="col-12 col-md-6 form-group">
                            <label for="email">Email Address <span class="require">*</span></label>
                            <input value="{{$user->email}}" name="email" id="email" type="email" class="form-control" required>
                        </div>

                        <div class="col-12 mt-4 mb-3">
                        <hr>
                        <h4><b>Password Change</b></h4>
                        </div>
<input type="password" style="display: none">
                        <div class="col-12 col-md-6 form-group">
                            <label for="current_password">Current Password <span class="blank">(Leave blank to leave unchanged)</span></label>
                            <input name="current_password" id="current_password" type="password" class="form-control">
                        </div><div class="col-12 col-md-6"></div>
                        <div class="col-12 col-md-6 form-group">
                            <label for="new_password">New Password <span class="blank">(Leave blank to leave unchanged)</span></label>
                            <input name="new_password" id="new_password" type="password" class="form-control">
                        </div><div class="col-12 col-md-6"></div>
                        <div class="col-12 col-md-6 form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input name="confirm_password" id="confirm_password" type="password" class="form-control">
                        </div>


                    </div>

                    <div class="sbutton mb-5"><button class="btn btn-primary btn-lg px-5">Save Changes</button></div>


                </form>


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

            <div class="text-end"><h4>Total Price:  <span class="ms-3 me-3">£<span id="update_price"></span></span></h4></div>
            <table class="table">
                <tbody>
                    <tr><th>Registration Number:</th><td id="reg_number"></td></tr>
<tr><th>Vehicle Make:</th><td id="vehicle_make"></td></tr>
<tr><th>Vehicle Model:</th><td id="vehicle_model"></td></tr>
<tr><th>Engine CC:</th><td id="engine_cc"></td></tr>
<tr><th>Start Date:</th><td id="start_date"></td></tr>
<tr><th>Start Time:</th><td id="start_time"></td></tr>
<tr><th>End Date:</th><td id="end_date"></td></tr>
<tr><th>End Time:</th><td id="end_time"></td></tr>
<tr><th>Date of Birth:</th><td id="date_of_birth"></td></tr>
<tr><th>First Name(s):</th><td id="first_name"></td></tr>
<tr><th>Last Name:</th><td id="last_name"></td></tr>
<tr><th>Licence Type:</th><td id="licence_type"></td></tr>
<tr><th>Licence Held Duration:</th><td id="licence_held_duration"></td></tr>
<tr><th>Vehicle Value:</th><td id="vehicle_type"></td></tr>
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
