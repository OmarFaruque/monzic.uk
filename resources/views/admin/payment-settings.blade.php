@php
    $user = Auth::user();
@endphp
@extends('templates.admin')



@push('meta')
    <title>Payment Settings </title>
@endpush



@section('content')
    
<style>
    .card-body input{
        border-color:  #999 !important;
        color: #333 !important;
        font-size: 16px !important;
    }
</style>
    <section>
        <div class="container-fluid pt-1 sform text-center">
            
            <h2>Payment Settings</h2>

            
            <div class="card" style="border: 5px solid #999; max-width: 500px; ">
                <div class="card-header text-bold ">PAYMENT GATEWWAY</div>
                <div class="card-body">
                    <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                        <input type="hidden" name="param" value="payment_gateway">
                        <select style="font-size: 20px; font-weight:bold" name="value" id="payment_gateway"
                            class="form-control" required>
                            <option value=""></option>
                            <option value="stripe" {{ ($payment_gateway == "stripe") ? 'selected' : '' }}>STRIPE</option>
                            <option value="airwallex" {{ ($payment_gateway == "airwallex") ? 'selected' : '' }}>AIRWALLEX</option>
                            <option value="paypal" {{ ($payment_gateway == "paypal") ? 'selected' : '' }}>PAYPAL</option>
                            <option value="nowpay" {{ ($payment_gateway == "nowpay") ? 'selected' : '' }}>NowPayment</option>
                            <option value="squareup" {{ ($payment_gateway == "squareup") ? 'selected' : '' }}>SQUAREUP</option>
                            <option value="wordpress" {{ ($payment_gateway == "wordpress") ? 'selected' : '' }}>WOOCOMMERCE</option>
                            <option value="paddle" {{ ($payment_gateway == "paddle") ? 'selected' : '' }}>Paddle</option>
                            <option value="mollie" {{ ($payment_gateway == "mollie") ? 'selected' : '' }}>Mollie</option>
                            <option value="lemonsqueezy" {{ ($payment_gateway == "lemonsqueezy") ? 'selected' : '' }}>Lemon Squeezy</option>
                        </select>

                        <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                UPDATE</button></div>
                    </form>

                </div>

            </div>



            <details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">STRIPE SETTINGS:</summary>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Stripe Public Key</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="stripe_public">
                                <input value="{{$stripe_public}}"  name="value" id="stripe_public" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Stripe Secret Key</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="stripe_secret">
                                <input value="{{$stripe_secret}}"  name="value" id="stripe_secret" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Stripe Webhook Secret</div>
                        <div class="card-body">
                            <div>WEBHOOK URL:  {{ url('/stripe-webhook-suizhide') }}</div>
                            <div>Visit <a>Stripe <a href="https://dashboard.stripe.com/live/webhooks">dashboad page > developer > webhook.</a> Create a new webhook endpoint, Events should be (checkout.session.async_payment_succeeded
                                checkout.session.completed) and enter the link above as the url. You will enter the generated webhook secret below </a></div>
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="stripe_whook_secret">
                                <input value="{{$stripe_whook_secret}}"  name="value" id="stripe_whook_secret" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            </details>




            <details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">AIRWALLEX SETTINGS:</summary>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Airwallex Client ID</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="airwallex_client_id">
                                <input value="{{$airwallex_client_id}}"  name="value" id="airwallex_client_id" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Airwallex API Key</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="airwallex_api_key">
                                <input value="{{$airwallex_api_key}}"  name="value" id="airwallex_api_key" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">AirwallexWebhook Secret</div>
                        <div class="card-body">
                            <div>WEBHOOK URL:  {{ url('/airwallex/webhook-suizhide') }}</div>
                            <div>Visit <a>Airwallex <a href="https://www.airwallex.com/app/developer/webhooks">account > developer > webhook.</a> Create a new webhook endpoint, Events should be (payment_intent.succeeded) and enter the link above as the url. You will enter the generated webhook secret below </a></div>
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="airwallex_whook_secret">
                                <input value="{{$airwallex_whook_secret}}"  name="value" id="airwallex_whook_secret" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            </details>


            <details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">PAYPAL SETTINGS:</summary>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Client ID</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="paypal_client_id">
                                <input value="{{$paypal_client_id}}"  name="value" id="paypal_client_id" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Client Secret</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="paypal_client_secret">
                                <input value="{{$paypal_client_secret}}"  name="value" id="paypal_client_secret" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </details>




<details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">BANK TRANSFER SETTINGS:</summary>

                
                <div class="row">
                
                    <div class="col-12 col-md-6">
                        <div class="card" style="border: 5px solid #999; ">
                            <div class="card-header text-bold ">Show Bank Payment</div>
                            <div class="card-body">
                                <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                    <input type="hidden" name="param" value="show_bank">
                                    <select  name="value" id="show_bank" class="form-control" required>
                                        <option {{ ($show_bank == 1)? 'selected': '' }} value="1">Yes</option>
                                        <option {{ ($show_bank == 0)? 'selected': '' }} value="0">No</option>
    
                                    </select>
                                    <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                            UPDATE</button></div>
                                </form>
                            </div>
    
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">BANK NAME</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="bank_name">
                                <input value="{{$bank_name}}"  name="value" id="bank_name" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>

                
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Sort Code</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="bank_sort_code">
                                <input value="{{$bank_sort_code}}"  name="value" id="bank_sort_code" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Account Number</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="bank_account_number">
                                <input value="{{$bank_account_number}}"  name="value" id="bank_account_number" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Refrence Number</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="bank_ref_number">
                                <input value="{{$bank_ref_number}}"  name="value" id="bank_ref_number" class="form-control" style="" required><b>-POLICY_NUMBER</b>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Info Text</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="bank_infor_text">
                                <input value="{{$bank_infor_text}}"  name="value" id="bank_infor_text" class="form-control" style="" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Percentage Off Payment</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="bank_per_off">
                                <input type="number" step="0.1" value="{{$bank_per_off}}"  name="value" id="bank_per_off" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
            </details>




            <details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">NOWPAYMENT SETTINGS:</summary>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">API KEY</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="now_api_key">
                                <input value="{{$now_api_key}}"  name="value" id="now_api_key" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">IPN  Secret</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="now_ipn_secret">
                                <input value="{{$now_ipn_secret}}"  name="value" id="now_ipn_secret" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Percentage Off Payment</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="nowp_per_off">
                                <input type="number" step="0.1" value="{{$nowp_per_off}}"  name="value" id="nowp_per_off" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>


                

            </div>
            </details>



            <details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">SQUAREUP SETTINGS:</summary>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">APP LOCATION  ID</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="square_loc_id">
                                <input value="{{$square_loc_id}}"  name="value" id="square_loc_id" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">APP ID</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="square_app_id">
                                <input value="{{$square_app_id}}"  name="value" id="square_app_id" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">APP ACCESS TOKEN</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="square_access_token">
                                <input value="{{$square_access_token}}"  name="value" id="square_access_token" class="form-control" required>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">SQUARE PAYMENT METHOD</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 5)">
                                <input type="hidden" name="param" value="square_pmethods">
                                <input type="hidden" value=""  name="value" id="square_pmethods">
                                <div class="my-3 text-left"><input {{ in_array("card", $square_pmethods)? 'checked':'' }} type="checkbox" name="method" value="card" id="card"> <label for="card"> Card Payment</label></div>
                                <div class="my-3 text-left"><input {{ in_array("google", $square_pmethods)? 'checked':'' }} type="checkbox" name="method" value="google" id="google"> <label for="google"> Google Play</label></div>
                                <div class="my-3 text-left"><input {{ in_array("apple", $square_pmethods)? 'checked':'' }} type="checkbox" name="method" value="apple" id="apple"> <label for="apple"> Apple Payment</label></div>

                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            </details>




            <details class="my-5">
                <summary style="padding: 10px; font-size:20px; border:2px solid #999">CHECKBOX AT CHECKOUT PAGE:</summary>
<div class="alert alert-info">Each entry should be separeted by  || </div>
            <div class="row">
                <div class="col-12 col-md-12">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Checkbox Entries</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="checkout_checkbox">
                                <textarea rows="10" placeholder="I agree .. || I accept.. || I acknowledge.."  name="value" id="checkout_checkbox" class="form-control" required>{!!$checkout_checkbox!!}</textarea>
                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                
            </div>
            </details>


            <div class="col-12">
                    <details class="my-3">
                        <summary style="padding: 10px; font-size:20px; border:2px solid #999">Paddle Settings:</summary>
                        <div class="row">
                            <div class="col-12 col-md-12 text-left">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Paddle Settings</div>
                                    <div class="card-body" x-data="{ isLive: '{{ $paddle_mode }}' === 'live' }">
                                        <form class="is_ckedit" autocomplete="off" action="{{ route('update.OpenAPI') }}" method="POST">
                                            @csrf

                                            <div class="mb-4">
                                                <label class="font-weight-bold mr-2">Environment:</label>

                                                <!-- Hidden input to bind to Alpine -->
                                                <input type="hidden" name="paddle_mode" :value="isLive ? 'live' : 'sandbox'">

                                                <button type="button"
                                                        @click="isLive = !isLive"
                                                        class="btn"
                                                        :class="isLive ? 'btn-success' : 'btn-secondary'">
                                                    <span x-text="isLive ? 'Live Mode' : 'Sandbox Mode'"></span>
                                                </button>
                                            </div>


                                            <div class="row gap-5 w-full w-100" x-show="!isLive" x-transition>
                                                <div class="form-group col-md-6">
                                                    <label for="paddle_vendor_id">Paddle Client Token</label>
                                                    <input type="text" value="{{ old('paddle_vendor_id', $paddle_vendor_id) }}" placeholder="Paddle Client Token..." name="paddle_vendor_id" id="paddle_vendor_id" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paddle_apikey">Paddle API Key</label>
                                                    <input type="text" placeholder="Paddle API Key..." value="{{ old('paddle_apikey', $paddle_apikey) }}" name="paddle_apikey" id="paddle_apikey" class="form-control">
                                                </div>
                                            </div>


                                             <div class="row gap-5 w-full w-100" x-show="isLive" x-transition>
                                                <div class="form-group col-md-6">
                                                    <label for="paddle_client_token_live">Paddle Client Token</label>
                                                    <input type="text" value="{{ old('paddle_client_token_live', $paddle_client_token_live) }}" placeholder="Paddle Client Token..." name="paddle_client_token_live" id="paddle_client_token_live" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paddle_apikey_live">Paddle API Key</label>
                                                    <input type="text" placeholder="Paddle API Key..." value="{{ old('paddle_apikey_live', $paddle_apikey_live) }}" name="paddle_apikey_live" id="paddle_apikey_live" class="form-control">
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-12"> <p><small><i>Webhook: {{route('paddle.webhook')}}</i></small></p> </div>
                                            </div>
                                            
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                                    UPDATE</button></div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            
                        </div>
                    </details>
                </div>


                <div class="col-12">
                    <details class="my-3">
                        <summary style="padding: 10px; font-size:20px; border:2px solid #999">Mollie Settings:</summary>
                        <div class="row">
                            <div class="col-12 col-md-12 text-left">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Mollie Settings</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="mollie_api_key">
                                            <label for="mollie_api_key">Mollie API Key</label>
                                            <input value="{{$mollie_api_key}}"  name="value" id="mollie_api_key" class="form-control" required>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                                    UPDATE</button></div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            
                        </div>
                    </details>
                </div>


                <div class="col-12">
                    <details class="my-3" open>
                        <summary style="padding: 10px; font-size:20px; border:2px solid #999">Lemon Squeezy Payment Settings: @if($payment_gateway == "lemonsqueezy")<span class="badge badge-success ml-2">Active</span>@endif</summary>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Lemon Squeezy Store ID</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="lemonsqueezy_store_id">
                                            <input value="{{ $lemonsqueezy_store_id ?? '' }}" placeholder="Enter your Lemon Squeezy Store ID" name="value" id="lemonsqueezy_store_id" class="form-control" required>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i> UPDATE</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Lemon Squeezy Variant ID</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="lemonsqueezy_variant_id">
                                            <input value="{{ $lemonsqueezy_variant_id ?? '' }}" placeholder="Enter your Lemon Squeezy Variant ID" name="value" id="lemonsqueezy_variant_id" class="form-control" required>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i> UPDATE</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Lemon Squeezy API Key</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="lemonsqueezy_api_key">
                                            <div class="input-group">
                                                <input value="{{ $lemonsqueezy_api_key ?? '' }}" name="value" id="lemonsqueezy_api_key" placeholder="Enter your Lemon Squeezy API Key" class="form-control" type="password" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('lemonsqueezy_api_key', this)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i> UPDATE</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Lemon Squeezy Webhook Secret</div>
                                    <div class="card-body">
                                        <div><small>This is used to verify webhook events from Lemon Squeezy. <br> Webhook URL: <code>{{ url('/api/lemonsqueezy-webhook') }}</code></small></div>
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)" class="mt-3">
                                            <input type="hidden" name="param" value="lemonsqueezy_webhook_secret">
                                            <div class="input-group">
                                                <input value="{{ $lemonsqueezy_webhook_secret ?? '' }}" name="value" id="lemonsqueezy_webhook_secret" placeholder="webhook secret..." class="form-control" type="password" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('lemonsqueezy_webhook_secret', this)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i> UPDATE</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>



        </div>
    </section>


    
    <!--  Modal  ALert -->
    <div class="modal" id="modal_confirm">
        <div class="modal-dialog">
            <div class="modal-content bg-warning">
                <div style="text-align:right"> <button type="button" class="close"
                        data-bs-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body py-5 text-center" style="font-size:18px; color:#000">

                </div>
                <!-- Modal footer -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="updateSettings(event, 1)" type="button" class="btn  btn-success"
                        data-bs-dismiss="modal">Confirm Action</button>
                </div>

            </div>
        </div>
    </div>
@endsection('content')



@push('js')
    <script src="{{ asset('/admin-assets/js/settings.js?ver=' . config('app.version')) }}"></script>


    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endpush
