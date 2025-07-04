@php
$user = Auth::user();  
@endphp
@extends('templates.admin')



@push('meta')
    <title>Update passsword </title>

@endpush



@section('content')
  


    
    <section>
        <div class="container pt-1 sform text-center" >

      <div class="row " style="justify-content:center">
        <div class="col-12 col-sm-9 col-md-7 col-lg-6 ">
          <div class="card dashboard_card mt-3">
            <div class="card-header">
              <h3>Update password</h3>
            </div>
            <div class="card-body">

              <form id="login_form" onsubmit="update_password(event)" class="n_form main_form mt-3 text-left" autocomplete="off">
                <input type="password" name="mm" style="display:none">

                <div class="align-self-end form-group">
                                <label style="min-width:100%; display:inline-block !important;">Old password <a
                                        href="#" onclick="toggle_pview(event)" style="float:right;"
                                        class="fa fa-eye-slash text-main"></a>
                                    <input   id="old_password" name="old_password" type="password" class="form-control" required>
                            </div>

                <div class="form-group">
                                <label style="min-width:100%; display:inline-block !important;">New password <br><small>(At least eight characters, one upper case letter, one lower case letter, one number and one special character)</small> <a href="#" onclick="toggle_pview(event)"
                                        style="float:right;" class="fa fa-eye-slash text-main"></a></label>
                                <input type="password" class="form-control" data-error=" " data-pattern="At least eight characters, one upper case letter, one lower case letter, one number and one special character" id="new_password" name="new_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])[A-Za-z\d\s\W]{8,}$" required>
                            </div>
                            <div class="align-self-end form-group">
                                <label style="min-width:100%; display:inline-block !important;">Confirm le password<a
                                        href="#" onclick="toggle_pview(event)" style="float:right;"
                                        class="fa fa-eye-slash text-main"></a>
                                    <input  data-equal_message="Passwords bot match" data-equal="password" id="confirm_password" name="confirm_password" type="password" class="form-control" required>
                            </div>



                <div id="pwd_sbutton" class="text-center">
                  <button class="btn btn-main py-2 px-5"> Update</button>
                </div>
              </form>

              
            </div>

          </div>

        </div>


      </div>


        </div>
    </section>






@endsection('content')



@push('js')

<script src="{{ asset('js/form_validator.js?ver='.config('app.version')) }}"></script>


    <script>
        function toggle_pview(event) {
            event.preventDefault();
            $(event.target).toggleClass('fa-eye fa-eye-slash');
            let type = $(event.target).closest('.form-group').find('input').attr('type');
            $(event.target).closest('.form-group').find('input').attr('type', (type == 'password') ? 'text' : 'password');
        }
    </script>

@endpush
