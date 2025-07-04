@php
    
    $adminQuotePage = true;
    
@endphp
@extends('templates.page')


@push('meta')
    <title>Order - {{ config('app.name') }}</title>

@endpush




@push('css')
 {{-- Extra css files here --}}

 <link rel='stylesheet' href='/css/selectize.css?ver={{config('app.version')}}' type='text/css'  />
 {{-- <link rel='stylesheet' href='/css/selectize.default.css?ver={{config('app.version')}}' type='text/css'  /> --}}
 <link rel='stylesheet' href='/css/selectize.bootstrap5.css?ver={{config('app.version')}}' type='text/css'  />

 <link rel='stylesheet' href='/css/zebra_datepicker.css?ver={{config('app.version')}}' type='text/css'  />

 
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">


 

 <style>

body.single-product .title-bar {
  display: none !important;
}

/* Define some CSS variables for consistent theming */
:root {
    --input-height: 50px;
    --input-bg-color: #fff;
    --input-border-color: #ddd;
    --input-border-radius: 8px; /* Rounded corners for modern look */
    --input-transition: all 0.3s ease-in-out; /* Smooth transitions */
}

/* Simplify and group your selectors */
form.cart .thwepo-extra-options input,
form.cart .thwepo-extra-options select,
form.cart .thwepo-extra-options textarea,
.thwepo-extra-options input,
.thwepo-extra-options textarea,
.thwepo-extra-options select,
.woocommerce form .password-input input[type="password"],
.woocommerce-page form .password-input input[type="password"] {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    height: var(--input-height);
    line-height: var(--input-height);
    padding: 10px;
    background-color: var(--input-bg-color);
    border: 1px solid var(--input-border-color);
    border-radius: var(--input-border-radius); /* Soft rounded corners */
    transition: var(--input-transition); /* Smooth transitions on hover or focus */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Modern font */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Add hover and focus effects for better user interaction */
.thwepo-extra-options input:hover,
.thwepo-extra-options textarea:hover,
.thwepo-extra-options select:hover,
.woocommerce form .password-input input[type="password"]:hover,
.woocommerce-page form .password-input input[type="password"]:hover,
.thwepo-extra-options input:focus,
.thwepo-extra-options textarea:focus,
.thwepo-extra-options select:focus,
.woocommerce form .password-input input[type="password"]:focus,
.woocommerce-page form .password-input input[type="password"]:focus {
    border-color: #aaa; /* Darken border on hover/focus */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Increase shadow depth on hover/focus */
}

/* Container styling */
.cpw {
    font-family: 'Arial', sans-serif; /* Modern font */
    padding: 20px;
    width: 300px; /* Adjust width as needed */
    margin: 0 auto; /* Center the container */
    text-align: center; /* Center the inner content */
}

/* Label and currency symbol styling */
.cpw-input-wrapper label {
    font-size: 1.2em;
    font-weight: bold;
    color: #333;
    display: block;
    margin-bottom: 10px;
}

.woocommerce-Price-currencySymbol {
    font-size: 1em;
    color: #555;
}

/* Input field styling */
.cpw-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
    color: #333;
    transition: border-color 0.3s ease;
}

.cpw-input:focus {
    border-color: #007acc; /* Blue color when focused */
    outline: none;
}

/* Error message styling */
.woocommerce-cpw-message {
    color: #f44336; /* Red color for error */
    padding: 10px;
    font-size: 0.9em;
    margin-top: 10px;
}

.woocommerce-cpw-message ul {
    list-style-type: none; /* Remove bullet points */
    padding: 0;
    margin: 0;
}

.woocommerce-cpw-message li {
    text-align: center;
}

.quick-btn {
    display: inline-block; /* Makes sure the anchor behaves like a block element */
    width: 100px; /* Set a fixed width */
    height: 30px; /* Set a fixed height */
    line-height: 30px; /* Vertically center the text */
    text-align: center; /* Horizontally center the text */
    border: 1px solid #ccc; /* Optional: Add a border for visual clarity */
    margin-right: 5px; /* Optional: Add some spacing between the buttons */
    overflow: hidden; /* Ensures content doesn't spill out */
    white-space: nowrap; /* Prevents text from wrapping */
    text-overflow: ellipsis; /* Optional: If text is too long, it will end with '...' */
}

/* You can also style the label if needed */
label {
    display: inline-block;
    margin-right: 10px;
}

form.cart table.thwepo-extra-options label.label-tag {
    display: inline;
    word-break: unset;
    font-weight: 900;
	  text-transform: uppercase;
}

/* Logged in & out conditions */
.logged-in-condition .hide-logged-in {
  display: none!important;
}
.logged-out-condition .hide-logged-out {
  display: none!important;
} 

/* Hide quantity indicator on view order page */
.woocommerce-page.woocommerce-view-order .order_details .product-quantity {
    display: none;
}

.elementor-widget-woocommerce-my-account .e-my-account-tab:not(.e-my-account-tab__dashboard--custom) .woocommerce-MyAccount-content p:last-of-type {
    margin-bottom: 0;
}

p.order-details {
    display: none;
}

.elementor-widget-woocommerce-my-account .e-my-account-tab:not(.e-my-account-tab__dashboard--custom) .woocommerce-MyAccount-content h2:first-of-type {
    margin-top: 30px;
    display: none;
}

/* Change the font for input text fields */
input[type="text"] {
    font-family: 'Roboto', sans-serif; /* Change 'Roboto' to your desired font */
    font-size: 16px; /* Change the font size as needed */
}

/* Change the font for the email input field */
#reg_email {
    font-family: 'Roboto', sans-serif; /* Change 'Roboto' to your desired font */
    font-size: 16px; /* Change the font size as needed */
}

/* Hide the "Order again" button based on its link text */
a.button[href*="order_again="] {
    display: none;
}

/* Apply styles to the woocommerce customer details section */
.woocommerce-customer-details {
    border: 2px solid var(--gtheme-color);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(50, 140, 156, 0.1);
    background-color: var(--gtheme-color-box);
    color: #000;
    font-size: 18px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

/* Add hover effect to the woocommerce customer details section */
.woocommerce-customer-details:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(50, 140, 156, 0.15);
}



/* Apply styles to the WooCommerce order details container */
.woocommerce-order {
    border: 2px solid var(--gtheme-color);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(50, 140, 156, 0.1);
    background-color: var(--gtheme-color-box);
    color: #000;
    font-size: 18px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

/* Add hover effect to the WooCommerce order details container */
.woocommerce-order:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(50, 140, 156, 0.15);
}

ul.wc_stripe_cart_payment_methods li.wc-stripe-payment-method button, ul.wc_stripe_checkout_banner_gateways li button, ul.wc_stripe_product_payment_methods li[class*=payment_method_stripe_] button {
    margin: 0;
    display: none;
}

body.checkout-wc form h1, body.checkout-wc h2, body.checkout-wc h3, body.checkout-wc h4, main.checkoutwc form h1, main.checkoutwc h2, main.checkoutwc h3, main.checkoutwc h4 {
    color: #333;
    font-weight: 300;
    margin-bottom: 0.6em;
    margin-top: 0;
    font-weight: 700;
    margin-bottom: 1em;
    text-transform: uppercase;
    font-size: 1.5em;
}

img.custom-logo {
    height: auto;
    max-width: 60%;
    margin-left: -50px;
}

#wrapper{
	min-height:0px;
}		
*{
    box-sizing: unset;
}
a{
    text-decoration: none;
}

form div{
 box-sizing: border-box;   
}
.Zebra_DatePicker_Icon_Wrapper{
    width: 100%;
}
.Zebra_DatePicker .dp_daypicker th {
  background: #aef3ed;
}
 </style>

 @endpush




@section('content')
    
<br>


<div id="content-full" class="grid xcontainer col-940  single-product">
	<div class="woocommerce">
		<div class="woocommerce-notices-wrapper"></div><div id="product-2233" class="cpw-product product type-product post-2233 status-publish first instock product_cat-uncategorized downloadable virtual sold-individually purchasable product-type-simple">

	<div class="woocommerce-product-gallery woocommerce-product-gallery--without-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 1; transition: opacity 0.25s ease-in-out;">
	<div class="woocommerce-product-gallery__wrapper">
		<div class="woocommerce-product-gallery__image--placeholder"><img src="../../wp-content/uploads/woocommerce-placeholder-600x600.png" alt="Awaiting product image" class="wp-post-image"></div>	</div>
</div>

	<div class="summary entry-summary">

	<form  onsubmit="updateQuoteForm(event)" class="cart" action="" method="post" enctype="multipart/form-data">

      
<style>
    .qfmain{
        width: 100%;
        max-width: 800px;
    }
    .qfgroup{
        margin-bottom: 20px;
    }
    .qfgroup label{
        display: inline;
        word-break: unset;
        font-weight: 900;
        text-transform: uppercase;
        margin-right: 10px;
    }
    .qfgroup input, .qfgroup select{
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        height: var(--input-height);
        line-height: var(--input-height);
        padding: 10px;
        background-color: var(--input-bg-color);
        border: 1px solid var(--input-border-color);
        border-radius: var(--input-border-radius);
        transition: var(--input-transition);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .selectize-input{
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        height: var(--input-height);
        /* line-height: var(--input-height); */
        /* padding: 10px; */
        background-color: var(--input-bg-color);
        border: 1px solid var(--input-border-color);
        border-radius: var(--input-border-radius);
        transition: var(--input-transition);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px 10px !important;
        text-align: left;
        margin-bottom: 0px !important;
    }
    .selectize-input.items.not-full{
        padding: 0px 10px !important;
    }

    .qfgroup input:focus, .qfgroup select:focus{
        
        box-shadow: 0 0 5px rgba(59, 58, 58, 0.5);
            border-color: #222;
            border-width: 1px !important;
            outline: 1px solid #222 !important;

    }


    .qfgroup .quick-btn{
        width: 100%;
        box-sizing: border-box;
        display: inline-block;
        height: unset;
    }
    
    .selectize-input.items.has-options.full.has-items{
        height: unset;
    }


    .date-row{
                
        box-sizing: border-box;
        height: var(--input-height);
        line-height: var(--input-height);
        padding: 10px;
        background-color: var(--input-bg-color);
        border: 1px solid var(--input-border-color);
        border-radius: var(--input-border-radius);
        transition: var(--input-transition);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        display: flex;
        justify-content: center;
        align-items: center;           
        }
        .date-row:has(input:focus) {
            box-shadow: 0 0 5px rgba(59, 58, 58, 0.5);
            border-color: #222;
            border-width: 1px !important;
            outline: 1px solid #222 !important;
        }
        .date-row .date-input{
        flex: 1;
        border:  none !important;
        outline: none !important;
        display: inline-block !important;
        margin: 3px 0px !important;
        height: 30px !important;
        text-align: center;
        border-radius:  0px !important;
        box-shadow: none;
        }
        .date-row .date-input:focus{
        outline: none !important;
        border: none !important;
        box-shadow: none;
        }
        
         /* For Chrome, Safari, Edge, and Opera */
         .date-input::-webkit-outer-spin-button,
        .date-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* For Firefox */
        .date-input {
            -moz-appearance: textfield;
        }

        .date-row .date-line {
            background-color: #e4e2e2;
            display: inline-block;
            width: 1px;
            /* border: 1px solid #e4e2e2; */
            height: 80%;
        }

        @media (max-width: 490px) {
            .lookup-btn{
                font-size: 14px;
                padding-left: 5px;
                padding-right: 5px;
                display: flex;
                align-items: center;
            }
        
        }
        .iti.iti--allow-dropdown{
            width: 100% !important;
        }

        div#header, div#footer{
            display: none;
        }
        .d-none2{
            display: none;
        }

        .selectize-control.single:has(input:focus) {
            box-shadow: 0 0 5px rgba(59, 58, 58, 0.5) !important;
            border-color: #222;
            border-width: 1px !important;
            outline: 1px solid #222 !important;
            border-radius: var(--input-border-radius);
        }
        #occupation-selectized{
            border:  none !important;
            outline: none !important;
            box-shadow: none;
        }
        #occupation-selectized:focus{
            outline: none !important;
            border: none !important;
            box-shadow: none;
        }
        .selectize-input.focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #222;
            border-width: 1px !important;
            border-radius: var(--input-border-radius);

        }

</style>

@if($quote != null)
<input autocomplete="off" name="id" name="id" value="{{$quote?->id}}" type="hidden">
<input autocomplete="off" name="_method" id="_method" value="PATCH" type="hidden"> 

@else
    <input autocomplete="off" name="_method" id="_method" value="POST" type="hidden"> 
@endif

<div class="qfmain">

    @if($quote == null)

    <h1>New Order</h1>

    @else
    
    <h1>Edit Order - #{{$quote->policy_number}}</h1>

    @endif
<br>    

@if($quote == null)
<div class="col-12 col-md-8 qfgroup">
    <label>Select User</label> <abbr title="Required">*</abbr>
    <select autocomplete="off" id="user_id" name="user_id">
    </select>
</div>
@endif


<div class="row">
    <div class="col-12 col-md-8 qfgroup">
        <label>Registration Number</label> <abbr title="Required">*</abbr>
        <input autocomplete="off" type="text" id="reg_number867x" name="reg_numberx" value="{{$quote?->reg_number}}" style="text-transform: uppercase;">
        <input autocomplete="off" id="reg_number867" name="reg_number" type="hidden" value="{{$quote?->reg_number}}">
    </div>
    <div class="col-12 col-md-4">
        <a id="get_cars" class="find-car-btn" style="width:100%; box-sizing:border-box; height: var(--input-height);">Find Car</a>
    </div>
</div>
<div class="row @if($quote == null) d-none @endif" id="mm_cc_line">
    <div class="col-12 col-md-4 qfgroup">
        <label>Vehicle Make</label> <abbr title="Required">*</abbr>
        <input autocomplete="off" value="{{$quote?->vehicle_make}}" readonly type="text" id="vehicle_make632" name="vehicle_make" class=" validate-required" >
    </div>
    <div class="col-12 col-md-4 qfgroup">
        <label>Vehicle Model</label> <abbr title="Required">*</abbr>
        <input autocomplete="off" value="{{$quote?->vehicle_model}}" readonly type="text" id="vehicle_model756" name="vehicle_model" class="validate-required" >
    </div>
    <div class="col-12 d-none col-md-4 qfgroup">
        <label>Engine CC</label>
        <input autocomplete="off" value="{{$quote?->engine_cc}}" readonly type="text" id="engine_cc681" name="engine_cc" value="">
    </div>
</div>


@if($quote != null)

<div class="qfgroup">
    <div><b>DURATION:  {{date('d-m-Y H:i', strtotime($quote->start_date. ' '.$quote->start_time))}} to  {{date('d-m-Y H:i', strtotime($quote->end_date. ' '.$quote->end_time))}}</b></div>
    <input onchange="shouldModifyDateChange(event)" autocomplete="off"  style="width:28px; height: 28px; vertical-align: middle;" type="checkbox" name="modify_date" value="1" id="modify_date"> <label for="modify_date" style="cursor: pointer"> Make changes to dates?</label>
</div>

@endif


<div class="qfgroup period_grp @if($quote != null) d-none2 @endif">
    <label>Duration type?</label>
    <div class="row g-2 duration_type">
        <div class="col-4"><a id="hour_period_type" class="quick-btn active  quick" data-type="hours">Hours</a></div>
        <div class="col-4"><a class="quick-btn disabled quick" data-type="days">Days</a></div>
        <div class="col-4"><a class="quick-btn disabled quick" data-type="weeks">Weeks</a></div>
    </div>
    <input id="duration_type" name="duration_type" type="hidden">
</div>
<div class="qfgroup period_grp @if($quote != null) d-none2 @endif">
    <label> How long?</label>
    <div class="row g-2 duration_period">
        <div class="col-6 col-sm-3"><a class="quick-btn  disabled quick" data-period="1"> 1 hour</a></div>
        <div class="col-6 col-sm-3"><a class="quick-btn active quick" data-period="3">3 hours</a></div>
        <div class="col-6 col-sm-3"><a class="quick-btn disabled quick" data-period="5">5 hours</a></div>
        <div class="col-6 col-sm-3"><a class="quick-btn disabled quick" data-period="o">Other</a></div>
    </div>
    <select id="duration_period" name="duration_period" class="validate-required d-none"></select>
</div>

<input autocomplete="off"  name="start_date" id="start_date" type="hidden" required>
<input autocomplete="off"  name="end_date" id="end_date" type="hidden" required>
<input autocomplete="off"  name="start_time" id="start_time" type="hidden" required>
<input autocomplete="off"  name="end_time" id="end_time" type="hidden" required>

<div class="qfgroup period_grp @if($quote != null) d-none2 @endif">
    <label>Start date? </label> <abbr title="Required">*</abbr>
    <select id="start_daten" name="start_daten" class="validate-required" autocomplete="off">
        <option value=""></option>
        @php
            $today = time();
            for ($i = 0; $i < 30; $i++) {
                $dateValue = date('Y-m-d', strtotime("+$i days", $today));
    
                if ($i == 0) {
                    $dateLabel = "\T\o\d\a\y";
                } elseif ($i == 1) {
                    $dateLabel = "\T\o\m\o\\r\\r\o\w";
                } else {
                    $dateLabel = "l";
                }
                $dateLabel = date($dateLabel.', jS F', strtotime("+$i days", $today));

                if($i == 0){
                    echo '<option data-class="immediate" value="'.$dateValue.'xx">Immediate Start</option>';    
                }
    
                echo "<option value='$dateValue'>$dateLabel</option>";
            }
        @endphp
    </select>
</div>


<div class="qfgroup period_grp @if($quote != null) d-none2 @endif" id="hh_mm_line">
    <label>Start time? </label> <abbr title="Required">*</abbr>
    <div class="row" >
        <div class="col-6 col-md-4">
            <select id="start_hour" name="start_hour"></select>
        </div>
        <div class="col-6 col-md-4">
            <select id="start_minute" name="start_minute"></select>
        </div>
    </div>
</div>

<div class="qfgroup">
    <label>Reason</label> <abbr title="Required">*</abbr>
    <select autocomplete="off" id="cover_reason" name="cover_reason" class=" validate-required">
        <option value="">Select reason</option>
        <option  {{($quote?->cover_reason == "Borrowing")?"selected": ""}}  value="Borrowing">Borrowing</option>
        <option {{($quote?->cover_reason == "Buying/Selling/Testing")?"selected": ""}} value="Buying/Selling/Testing">Buying/Selling/Testing</option>
        <option {{($quote?->cover_reason == "Learning")?"selected": ""}} value="Learning">Learning</option>
        <option {{($quote?->cover_reason == "Maintenance")?"selected": ""}} value="Maintenance">Maintenance</option>
        <option {{($quote?->cover_reason == "Other")?"selected": ""}} value="Other">Other</option>

    </select>
</div>

<div class="row">
    <div class="col-5 col-md-2 qfgroup">
        <label>Title</label> <abbr title="Required">*</abbr>
        <select id="title" name="title" class=" validate-required" >
            <option value=""></option>
            <option {{($quote?->title == "Mr")?"selected": ""}} value="Mr">Mr</option>
            <option {{($quote?->title == "Mrs")?"selected": ""}} value="Mrs">Mrs</option>
            <option {{($quote?->title == "Miss")?"selected": ""}} value="Miss">Miss</option>
            <option {{($quote?->title == "Ms")?"selected": ""}} value="Ms">Ms</option>
        </select>
    </div>
    <div class="col-12 col-md-4 qfgroup">
        <label>First Name(s)</label> <abbr title="Required">*</abbr>
        <input  value="{{$quote?->first_name}}" id="first_name556" name="first_name" class=" validate-required" >
    </div>
    <div class="col-12 col-md-3 qfgroup">
        <label>Middle Name</label>
        <input  value="{{$quote?->middle_name}}" id="middle_name517" name="middle_name" >
    </div>
    <div class="col-12 col-md-3 qfgroup">
        <label>Last Name</label> <abbr title="Required">*</abbr>
        <input value="{{$quote?->last_name}}" id="last_name386" name="last_name" class=" validate-required">
    </div>
</div>

<div class=" row">
    <div class="col-12 col-md-6 qfgroup">
    <label>Date of Birth</label> <abbr title="Required">*</abbr><br>
    <div class="date-row">
        <input value="{{($quote == null)? '': date("d", strtotime($quote?->date_of_birth))}}"  autocomplete="off" required  type="number" class="date-input" id="dd" placeholder="DD" maxlength="2">
        <span class="date-line"></span>
        <input value="{{($quote == null)? '': date("m", strtotime($quote?->date_of_birth))}}" autocomplete="off" required type="number" class="date-input" id="mm" placeholder="MM" maxlength="2">
        <span class="date-line"></span>
        <input value="{{($quote == null)? '': date("Y", strtotime($quote?->date_of_birth))}}" autocomplete="off" required type="number" class="date-input" id="yyyy" placeholder="YYYY" maxlength="4">
    </div>
    <input value="{{date("d-m-Y", strtotime($quote?->date_of_birth))}}" autocomplete="off" type="hidden" name="date_of_birth" id="date_of_birth">
    </div>

    <div class="col-12 col-md-6 qfgroup">
        <label>Contact number</label> <abbr title="Required">*</abbr>
        <input value="{{$quote?->contact_number}}" autocomplete="off" id="phone" name="phone" class="validate-requinred">
        <div id="error-msg" style="color:red; font-size: 12px"></div>
        <div id="valid-msg" style="color:green; font-size: 12px"></div>
        <input value="{{$quote?->contact_number}}"  autocomplete="off" id="contact_number" name="contact_number" type="hidden">
    </div>
        


</div>


<div class="row" style="align-items: flex-end">
    <div class="col-6 col-md-6 qfgroup">
        <label>Postcode</label> <abbr title="Required">*</abbr>
        <input data-postcode="{{$quote?->postcode}}" value="{{$quote?->postcode}}" placeholder="Postcode" type="text" autocomplete="off" id="postcode" name="postcode" class="form-control  validate-required text-uppercase">
    </div>
    <div class="col-6 col-md-6 qfgroup">
        <a  onclick="searchAddress(event)" class="find-car-btn lookup-btn" style="width:100%; box-sizing:border-box; height: var(--input-height);">LOOK UP ADDRESS</a>
    </div>
</div>


<div class="qfgroup @if($quote == null) d-none @endif">
    <label>Address</label> <abbr title="Required">*</abbr>
    <select  autocomplete="off" id="address" name="address" class="validate-requinred">
        <option selected data-postcod="{{$quote?->postcode}}" value="{{$quote?->address}}">{{$quote?->address}}</option>
    </select>
 </div>


<div class="qfgroup">
    <label>Occupation</label> <abbr title="Required">*</abbr>
    <select type="text" autocomplete="off" id="occupation" name="occupation" class=" validate-required">
    </select>
</div>



<div class="row">
    <div class="col-12 col-md-4 qfgroup">
        <label>Type</label> <abbr title="Required">*</abbr>
        <select id="licence_type304" name="licence_type" class="validate-required" >
            <option {{($quote?->licence_type == "Full UK")?"selected": ""}} value="Full UK" >Full UK</option>
            <option {{($quote?->licence_type == "Full Northern Ireland")?"selected": ""}} value="Full Northern Ireland" >Full Northern Ireland</option>
            <option {{($quote?->licence_type == "Full EU")?"selected": ""}} value="Full EU" >Full EU</option>
            <option {{($quote?->licence_type == "Full International")?"selected": ""}} value="Full International" >Full International</option>
            <option {{($quote?->licence_type == "Automatic UK")?"selected": ""}} value="Automatic UK" >Automatic UK</option>
        </select>
    </div>
    <div class="col-12 col-md-4 qfgroup">
        <label>Held Duration</label> <abbr title="Required">*</abbr>
        <select id="licence_held_duration384" name="licence_held_duration" class="validate-required" >
            <option {{($quote?->licence_held_duration == "Under 1 Year")?"selected": ""}} value="Under 1 Year" >Under 1 Year</option>
            <option {{($quote?->licence_held_duration == "1-2 Years")?"selected": ""}} value="1-2 Years" >1-2 Years</option>
            <option {{($quote?->licence_held_duration == "2-4 Years")?"selected": ""}} value="2-4 Years" >2-4 Years</option>
            <option {{($quote?->licence_held_duration == "5-10 Years")?"selected": ""}} value="5-10 Years" >5-10 Years</option>
            <option {{($quote?->licence_held_duration == "10+ Years")?"selected": ""}} value="10+ Years" >10+ Years</option>
        </select>
    </div>
    <div class="col-12 col-md-4 qfgroup">
        <label>Value</label> <abbr  title="Required">*</abbr>
        <select id="vehicle_type928" name="vehicle_type"  class="validate-required" >
            <option {{($quote?->vehicle_type == "£1,000 - £5,000")?"selected": ""}} value="£1,000 - £5,000" >£1,000 - £5,000</option>
            <option {{($quote?->vehicle_type == "£5,000 - £10,000")?"selected": ""}} value="£5,000 - £10,000" >£5,000 - £10,000</option>
            <option {{($quote?->vehicle_type == "£10,000 - £20,000")?"selected": ""}} value="£10,000 - £20,000" >£10,000 - £20,000</option>
            <option {{($quote?->vehicle_type == "£20,000 - £30,000")?"selected": ""}} value="£20,000 - £30,000" >£20,000 - £30,000</option>
            <option {{($quote?->vehicle_type == "£30,000 - £50,000")?"selected": ""}} value="£30,000 - £50,000" >£30,000 - £50,000</option>
            <option {{($quote?->vehicle_type == "£50,000 - £80,000")?"selected": ""}} value="£50,000 - £80,000" >£50,000 - £80,000</option>
            <option {{($quote?->vehicle_type == "£80,000+")?"selected": ""}} value="£80,000+" >£80,000+</option>
        </select>
    </div>
</div>


<div class="text-center">


    <div class="d-flex gap-5" style="align-items:center;  justify-content:center">

        <p class="cwp-input-wrapper mt-5" style="border:1px solid var( --e-global-color-primary );  border-radius:10px; padding: 10px 40px; max-width: 350px; display:flex; flex-direction: column; justify-content: center; align-items: center; gap:5px">
    <span style="white-space: nowrap; font-size: 25px;">Total Price</span>
    <span style="border:none !important; padding-top:0px !important; font-size: 25px;" type="text"  class="input-text amount cpw-input text">£<span id="cpw_val"></span></span>

    <span style="font-size: 25px;">£<input required autocomplete="off" name="update_price" id="update_price"  style="border:1px solid #CCC; !important; text-align:left; padding-top:0px !important; font-size: 25px; max-width: 150px;" type="number" step="0.01"  class="input-text amount cpw-input text" placeholder=""><small style="font-size: 10px">Discounted price</small></span>

</p>

<input type="hidden" id="cpw" name="cpw" onchange="setUpdatePrice()">

@if($quote != null)

<p class="cwp-input-wrapper mt-5" style="background-color:#CCC; border:1px solid var( --e-global-color-primary );  border-radius:10px; padding: 10px 40px; max-width: 350px; display:flex; flex-direction: column; justify-content: center; align-items: center; gap:5px">
    <span style="white-space: nowrap; font-size: 25px;">Previous  Prices</span>
    <span style="border:none !important; padding-top:0px !important; font-size: 25px;" type="text"  class="input-text amount cpw-input text">£<span>{{ number_format($quote?->cpw,2) }}</span>
    </span>
    <span style="border:none !important; padding-top:0px !important; font-size: 25px;" type="text"  class="input-text amount cpw-input text"><span>£{{ number_format($quote?->update_price,2) }}<small style="font-size: 10px">Discounted price</small></span></span>

</p>
@endif

    </div>

<div class="wc-stripe-clear"></div>

<div><button type="submit" name="add-to-cart" value="2233" class="single_add_to_cart_button sbutton button alt cpw-disabled" style="pointer-events: none;">Continue</button></div>

    
</div>



</div>


    </form>

	
<div class="product_meta">

	
	
		<span class="sku_wrapper">SKU: <span class="sku">quote-01</span></span>

	
	<span class="posted_in">Category: <a href="../../product-category/uncategorized/index.html" rel="tag">Uncategorized</a></span>
	
	
</div>
	</div>

	</div>

	</div>	
</div>

<br><br><br>



@endsection('content')




@push('js')

{{-- extra jss files here --}}
<script src="/js/selectize.min.js"></script>
<script src="/js/occupation_list.js"></script>

<script>
    let getQuoteFunctionString = `{!! $quoteJsFunc !!}`;
    // Load and evaluate the function dynamically
    eval(getQuoteFunctionString);

</script>




<script>

        const startDate =  "{{ date("d-m-Y", strtotime("-75 years")) }}";
        const endDate = "{{date("d-m-Y", strtotime("-17 years")) }}";

        const startYear = {{date("Y", strtotime("-75 years"))}};
        const endYear = {{date("Y", strtotime("-17 years"))}};

        function enroll() {
            console.log("Invalid Date Entered!");
        }

        function valid() {
            console.log("Valid Date Entered!");
        }

        jQuery(document).ready(function ($) {
            // Allow only digits and auto-move to the next input
            $(".date-input").on("input", function () {
                let val = $(this).val().replace(/\D/g, ''); // Remove non-digits
                $(this).val(val); // Set cleaned value

                //Enforce maxlength is obey
                let maxLength = parseInt($(this).prop('maxlength'));
                if($(this).val().length > maxLength){
                    $(this).val($(this).val().slice(0, maxLength)).trigger('changes');
                }
                val = $(this).val().replace(/\D/g, '');//  Re initialize val
                
                let id = $(this).attr("id");

                // Auto-move to the next input when full
                if ((id === "dd" || id === "mm") && val.length === 2) {
                    let nextInput = $(this).closest('.date-row').find('.date-input').eq($(".date-input").index(this) + 1);
                    if (nextInput.length) {
                        nextInput.focus();
                    }
                } else if (id === "yyyy" && val.length === 4) {
                    // validateDate();
                }
                validateDate();
            });

            // Handle paste event (multiple date formats)
            $(document).on("paste", ".date-input", function (e) {
        

                let data = e.originalEvent.clipboardData.getData("text");

                let cleanData = data.replace(/[^\d]/g, "");

                // Extract numbers from various formats (e.g., DD/MM/YYYY, D-M-YYYY, DDMMYYYY)
                let day, month, year;

                if(data.split("-").length == 3){
                    let rdata = data.split("-");
                    day = (rdata[0].length <= 2)? rdata[0]: "";
                    month = (rdata[1].length <= 2)? rdata[1]: "";
                    year = (rdata[2].length <= 4)? rdata[2]: "";
                }
                else if(data.split("/").length == 3){
                    let rdata = data.split("-");
                    day = (rdata[0].length <= 2)? rdata[0]: "";
                    month = (rdata[1].length <= 2)? rdata[1]: "";
                    year = (rdata[2].length <= 4)? rdata[2]: "";
                }
                else if (data.length === 8) {
                    day = cleanData.substring(0, 2);
                    month = cleanData.substring(2, 4);
                    year = cleanData.substring(4, 8);
                }

                if(day && month){
                     // Fill inputs
                    $("#dd").val(day);
                    $("#mm").val(month);
                    $("#yyyy").val(year);
                    validateDate();
                }
                e.preventDefault();
            });
            
        });

        // Validate Date and Call Appropriate Function
        function validateDate() {
            let day = parseInt($("#dd").val(), 10);
            let month = parseInt($("#mm").val(), 10);
            let year = parseInt($("#yyyy").val(), 10);

            $("#date_of_birth").val('').trigger('change');

            let isValid = !isNaN(day) && !isNaN(month) && !isNaN(year);
            if (isValid) {
                let date = new Date(year, month - 1, day);
                isValid =
                    date.getFullYear() === year &&
                    date.getMonth() === month - 1 &&
                    date.getDate() === day;
            }
            if(isValid){
                $(".date_error").remove();
                if(year >= startYear && year <= endYear){
                    let date_n = `${ (day>9)?day:"0"+day}-${(month>9)?month:"0"+month}-${year}`;
                    if(isDateInRange(date_n, startDate, endDate)){
                       $("#date_of_birth").val(date_n).trigger('change');
                       return date_n;   
                    }
                    else{
                        if(year >= startYear){
                            $(".date-row").after('<div class="form_error date_error">Date out of range - You are not old enough to use our services.</div>');
                        }
                        else{
                            $(".date-row").after('<div class="form_error date_error">Date out of range - You are not elligible  to use our services.</div>');
                        }
                        return "";
                    }
                }
                else{
                    $(".date-row").after('<div class="form_error date_error">Year out of range</div>');
                    return "";
                }
            }else{
                $(".date_error").remove();
                if($("#dd").val() && $("#mm").val() && $("#yyyy").val()){
                    $(".date-row").after('<div class="form_error date_error">Not a valid date</div>');
                }
                return "";
            } 
        }

        function isDateInRange(date_n, startDate, endDate) {
            // Convert all dates to Date objects (assuming format: DD-MM-YYYY)
            const toDate = (dateStr) => {
                let [day, month, year] = dateStr.split('-').map(Number);
                return new Date(year, month - 1, day); // Month is 0-based in JS
            };

            let dateToCheck = toDate(date_n);
            let start = toDate(startDate);
            let end = toDate(endDate);

            // Check if date_n is within range (inclusive)
            return dateToCheck >= start && dateToCheck <= end;
        }




</script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script>
        const nphone_input = document.querySelector("#phone");
        const nerrorMsg = document.querySelector("#error-msg");
        const validMsg = document.querySelector("#valid-msg");

        // here, the index maps to the error code returned from getValidationError - see readme
        const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

        // initialise plugin
        const iti = window.intlTelInput(nphone_input, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
            nationalMode: false,
            autoInsertDialCode: true,
            formatOnDisplay: true,
            initialCountry: 'gb',
            preferredCountries: ["gb", "us", "ch"],
            separateDialCode: true
        });

        const reset = () => {
            nphone_input.classList.remove("error");
            nerrorMsg.innerHTML = "";
            nerrorMsg.classList.add("hide");
            validMsg.classList.add("hide");
        };

        // on blur: validate
        nphone_input.addEventListener('input', () => {
            reset();
            if (nphone_input.value.trim()) {
                if (iti.isValidNumber()) {
                    validMsg.classList.remove("hide");
                    $("#contact_number").val(iti.getNumber());
                } else {
                    nphone_input.classList.add("error");
                    const errorCode = iti.getValidationError();
                    nerrorMsg.innerHTML = errorMap[errorCode];
                    nerrorMsg.classList.remove("hide");
                }
            }
        });


    </script>
    <script>
        
        @if($quote != null)

        const bstartDate = "{{$quote->start_date}}";
        const bendDate = "{{$quote->end_date}}";
        const bstartTime = "{{date("H:i", strtotime($quote->start_time))}}";
        const bendTime = "{{date("H:i", strtotime($quote->end_time))}}";
        const boccupation = "{{$quote->occupation}}";

        @endif

    </script>
    <script src="/admin-assets/js/policies.js"></script>

@endpush
