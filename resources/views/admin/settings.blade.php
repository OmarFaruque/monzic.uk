@php
    $user = Auth::user();
@endphp
@extends('templates.admin')



@push('meta')
    <title>Settings </title>
@endpush



@section('content')
    <section>
        <div class="container-fluid p-5 sform text-left">
            <h2 class="mb-3">Settings</h2>
            <div class="row">

                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Order Expires Visibility</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="expires_vis">
                                <select style="font-size: 20px; font-weight:bold" name="value" id="expires_vis"
                                    class="form-control" required>
                                    <option value="1" {{ $expiresVis == 1 ? 'selected' : '' }}>VISIBLE</option>
                                    <option value="0" {{ $expiresVis != 1 ? 'selected' : '' }}>NOT VISIBLE</option>
                                </select>

                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>

                        </div>

                    </div>
                </div>


                <div class="col-12 col-md-6">

                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">Checking Backdated Time (In checkout page)</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="backdated_time">
                                <select style="font-size: 20px; font-weight:bold" name="value" id="backdated_time"
                                    class="form-control" required>
                                    <option value="1" {{ $backdatedTime == 1 ? 'selected' : '' }}>ENABLED</option>
                                    <option value="0" {{ $backdatedTime != 1 ? 'selected' : '' }}>DISABLED</option>
                                </select>

                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>

                        </div>

                    </div>
                </div>



                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">CAR SEARCH API TO USE</div>
                        <div class="card-body">
                            <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                <input type="hidden" name="param" value="carsearch_api">
                                <select style="font-size: 20px; font-weight:bold" name="value" id="carsearch_api"
                                    class="form-control" required>
                                    <option value="self" {{ $carsearch_api == 'ours' ? 'selected' : '' }}>OURS</option>
                                    <option value="others" {{ $carsearch_api == 'others' ? 'selected' : '' }}>OTHERS
                                    </option>
                                </select>

                                <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card" style="border: 5px solid #999; ">
                        <div class="card-header text-bold ">OpenAi</div>
                        <div class="card-body">
                            <form autocomplete="off" action="{{ route('update.OpenAPI') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label for="openai_api_key" class="apikey">API Key</label>
                                            <input type="text" name="openai_api_key" id="openai_api_key" value="{{ old('openai_api_key', $openai_api_key) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="ai_document_price" class="apikey">Doc. Price</label>
                                            <input type="number" min="0" name="ai_document_price" id="ai_document_price" value="{{ old('ai_document_price', $ai_document_price) }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3"><button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        UPDATE</button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <details class="my-5">
                        <summary style="padding: 10px; font-size:20px; border:2px solid #999">CHECKOUT PAGE NOTICE:</summary>
                        <div class="row">
                            <div class="col-12 col-md-9">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Checkout page notice</div>
                                    <div class="card-body">
                                        <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="checkout_notice">
                                            <textarea name="value" id="checkout_notice" class="form-control" required>{!! $checkout_notice !!}</textarea>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                                    UPDATE</button></div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 col-md-3">

                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Show Checkout Page Notice</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="show_checkout_notice">
                                            <select style="font-size: 20px; font-weight:bold" name="value"
                                                id="show_checkout_notice" class="form-control" required>
                                                <option value="yes"
                                                    {{ $show_checkout_notice == 'yes' ? 'selected' : '' }}>YES</option>
                                                <option value="no"
                                                    {{ $show_checkout_notice == 'no' ? 'selected' : '' }}>NO
                                                </option>
                                            </select>
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
                    <details class="my-5">
                        <summary style="padding: 10px; font-size:20px; border:2px solid #999">PAGE Popup NOTICE:
                        </summary>

                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Page Popup notice</div>
                                    <div class="card-body">
                                        <form class="is_ckedit" autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="home_notice">
                                            <textarea name="value" id="home_notice" class="form-control" required>{!! $home_notice !!}</textarea>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                                    UPDATE</button></div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="col-6 col-md-6">

                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Show  Page Popup</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="show_home_notice">
                                            <select style="font-size: 20px; font-weight:bold" name="value"
                                                id="show_home_notice" class="form-control" required>
                                                <option value="yes"
                                                    {{ $show_home_notice == 'yes' ? 'selected' : '' }}>YES</option>
                                                <option value="no"
                                                    {{ $show_home_notice == 'no' ? 'selected' : '' }}>NO
                                                </option>
                                            </select>
                                            <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i>
                                                    UPDATE</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">

                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Page to show Popup</div>
                                    <div class="card-body">
                                        <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                                            <input type="hidden" name="param" value="choosen_page_notice">
                                            <select style="font-size: 20px; font-weight:bold" name="value"
                                                id="choosen_page_notice" class="form-control" required>
                                                <option value="home"
                                                    {{ $choosen_page_notice == 'home' ? 'selected' : '' }}>Home page</option>
                                                <option value="checkout"
                                                    {{ $choosen_page_notice == 'checkout' ? 'selected' : '' }}>Checkout page
                                                </option>
                                                <option value="both"
                                                    {{ $choosen_page_notice == 'both' ? 'selected' : '' }}>Both pages
                                                </option>
                                            </select>
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
                        <summary style="padding: 10px; font-size:20px; border:2px solid #999">Paddle Settings:</summary>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="card" style="border: 5px solid #999; ">
                                    <div class="card-header text-bold ">Paddle Settings</div>
                                    <div class="card-body">
                                        <form class="is_ckedit" autocomplete="off" action="{{ route('update.OpenAPI') }}" method="POST">
                                            @csrf
                                            <div class="row gap-5 w-full w-100">
                                                <div class="form-group col-md-6">
                                                    <label for="paddle_vendor_id">Paddle Vendor ID</label>
                                                    <input type="text" value="{{ old('paddle_vendor_id', $paddle_vendor_id) }}" placeholder="Paddle vendor ID..." name="paddle_vendor_id" id="paddle_vendor_id" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paddle_apikey">Paddle API Key</label>
                                                    <input type="text" placeholder="Paddle API Key..." value="{{ old('paddle_apikey', $paddle_apikey) }}" name="paddle_apikey" id="paddle_apikey" class="form-control">
                                                </div>
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
    <script src="{{ asset('admin-assets/ckeditor/ckeditor.js') }}"></script>

    <script>
        $(document).ready(function() {

            CKEDITOR.config.height = 300;
            CKEDITOR.config.width = 'auto';
            CKEDITOR.replace('checkout_notice');
            CKEDITOR.replace('home_notice');

        });
    </script>
@endpush
apikey_01jppyby539wz5d5ewhh23fjkj
test_686dcf591a3cd76f3fcec9fae16