@php
$user = Auth::user();  
@endphp
@extends('templates.admin')



@push('meta')
    <title>Quote Formula </title>

@endpush



@section('content')
  


    
    <section>
        <div class="container-fluid pt-1 sform text-center" >

          <div class="alert alert-warning"><i class="fa fa-info"></i> Careful! here! Properly evaluate code before saving.</div>

          <div class="card" style="border: 5px solid #999; ">
            <details>
              <summary class="" style="background-color: #999"><div class="card-header text-bold "> JAVASCRIPT VERSION</div></summary>
              <div class="card-body">
                <form onsubmit="evaluateJsQuote(event)" autocomplete="off">
                <div class="d-flex" style="align-items:center">
                  <div class="form-group mx-2">
                  <label>Minutes Aval.</label>
                  <input min="0" required onkeyup="setFInput(event)" name="minute_aval" type="number" step="1" class="form-control" placeholder="Minutes Aval." value="0">
                  </div>
                  <div class="form-group mx-2">
                    <label>Hours Aval.</label>
                    <input min="0" required onkeyup="setFInput(event)" name="hour_aval" type="number" step="1" class="form-control" placeholder="Hours Aval." value="3">
                  </div>
                  <div class="form-group mx-2">
                    <label>Days Aval.</label>
                    <input min="0" required onkeyup="setFInput(event)" name="day_aval" type="number" step="1" class="form-control" placeholder="Days Aval." value="0">
                  </div>
                  <div class="form-group mx-2">
                    <label>Age</label>
                    <input min="1" required onkeyup="setFInput(event)" name="age" type="number" step="1" class="form-control" placeholder="age" value="20">
                  </div>
                  <div class="form-group mx-2">
                    <label>Quote Value:</label>
                    <input readonly name="cpw" class="form-control">
                  </div>
                    <button class="btn btn-primary" style="white-space: nowrap;">Execute Func</button>
                </div>
                </form>
                <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                <input type="hidden" name="param" value="quote_js_func">
                <textarea name="value" id="quote_js_func" class="form-control" style="min-height: 300px; font-family: 'Courier New', monospace; background-color: hsl(0, 0%, 96%); border: 1px solid #ddd; padding: 10px; white-space: pre-wrap;" spellcheck="false">{!! $quoteJsFunc !!}</textarea>
              <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i> UPDATE</button></div>  
              </form>
                  
              </div>
            </details>


          </div>




          <div class="card my-5" style="border: 5px solid #999; ">
            <details open>
              <summary class="" style="background-color: #999"><div class="card-header text-bold "> PHP VERSION</div></summary>
              <div class="card-body">
                <form onsubmit="evaluatePhpQuote(event)" autocomplete="off">
                <div class="d-flex" style="align-items:center">
                  <div class="form-group mx-2">
                  <label>Minutes Aval.</label>
                  <input min="0" required onkeyup="setFInput(event)" name="minute_aval" type="number" step="1" class="form-control" placeholder="Minutes Aval." value="0">
                  </div>
                  <div class="form-group mx-2">
                    <label>Hours Aval.</label>
                    <input min="0" required onkeyup="setFInput(event)" name="hour_aval" type="number" step="1" class="form-control" placeholder="Hours Aval." value="3">
                  </div>
                  <div class="form-group mx-2">
                    <label>Days Aval.</label>
                    <input min="0" required onkeyup="setFInput(event)" name="day_aval" type="number" step="1" class="form-control" placeholder="Days Aval." value="0">
                  </div>
                  <div class="form-group mx-2">
                    <label>Age</label>
                    <input min="1" required onkeyup="setFInput(event)" name="age" type="number" step="1" class="form-control" placeholder="age" value="20">
                  </div>
                  <div class="form-group mx-2">
                    <label>Quote Value:</label>
                    <input readonly name="cpw" class="form-control">
                  </div>
                    <button class="btn btn-primary" style="white-space: nowrap;">Execute Func</button>
                </div>
                </form>
                <form autocomplete="off" onsubmit="updateSettings(event, 0)">
                <input type="hidden" name="param" value="quote_php_func">
                <textarea name="value" id="quote_php_func" class="form-control" style="min-height: 300px; font-family: 'Courier New', monospace; background-color: hsl(0, 0%, 96%); border: 1px solid #ddd; padding: 10px; white-space: pre-wrap;" spellcheck="false">{!! $quotePhpFunc !!}</textarea>
              <div class="my-3"><button class="btn btn-success"><i class="fa fa-save"></i> UPDATE</button></div>  
              </form>
                  
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
                  <button onclick="updateSettings(event, 1)" type="button" class="btn  btn-success" data-bs-dismiss="modal">Confirm Action</button>
              </div>

          </div>
      </div>
  </div>




@endsection('content')



@push('js')

<script src="{{ asset('/admin-assets/js/settings.js?ver='.config('app.version')) }}"></script>


    <script>
       

    </script>

@endpush
