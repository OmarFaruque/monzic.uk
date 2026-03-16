<?php

namespace App\Http\Controllers\Admin;

use CHelper;
use Exception;
use DataTables;
use App\Models\Admin;
use App\Models\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{


    public function __construct(Request $request) {}


    public function quoteFormula(Request $request)
    {

        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $quote_js_func = Setting::where("param", "quote_js_func")->first();
        $quoteJsFunc = $quote_js_func->value;

        $quote_php_func = Setting::where("param", "quote_php_func")->first();
        $quotePhpFunc = $quote_php_func->value;


        return view('admin.quote_formula', ["quoteJsFunc" => $quoteJsFunc, "quotePhpFunc" => $quotePhpFunc]);
    }


    public function pageEditing(Request $request)
    {

        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $settingP = Setting::where("param", "LIKE", 'pags[%')->get();

        $pags = [];
        foreach ($settingP as $setting) {
            $param = str_replace(["pags[", "]"], "", $setting->param);
            $pags[$param] = $setting->value;
        }


        return view('admin.page-editing', ["pags" => $pags,]);
    }

    public function pageTemplate(Request $request)
    {

        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $settingP = Setting::where("param", "LIKE", 'page[%')->get();

        $page = [];
        foreach ($settingP as $setting) {
            $param = str_replace(["page[", "]"], "", $setting->param);
            $page[$param] = $setting->value;
        }


        return view('admin.page-template', ["page" => $page,]);
    }


    public function settings(Request $request)
    {


        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $expires_vis = Setting::where("param", "expires_vis")->first();
        if ($expires_vis == null) {
            $expiresVis = 0;;
        } else {
            $expiresVis = $expires_vis->value;
        }
        $backdated_time = Setting::where("param", "backdated_time")->first();
        if ($backdated_time == null) {
            $backdatedTime = 0;;
        } else {
            $backdatedTime = $backdated_time->value;
        }
        $carsearch_api_nx = Setting::where("param", "carsearch_api")->first();
        if ($carsearch_api_nx == null) {
            $carsearch_api = "others";
        } else {
            $carsearch_api = $carsearch_api_nx->value;
        }

        $setn = Setting::where("param", "checkout_notice")->first();
        if ($setn == null) {
            $checkout_notice = "";
        } else {
            $checkout_notice = $setn->value;
        }
        $setn = Setting::where("param", "show_checkout_notice")->first();
        if ($setn == null) {
            $show_checkout_notice = "yes";
        } else {
            $show_checkout_notice = $setn->value;
        }
        $setn = Setting::where("param", "home_notice")->first();
        if ($setn == null) {
            $home_notice = "";
        } else {
            $home_notice = $setn->value;
        }
        $setn = Setting::where("param", "show_home_notice")->first();
        if ($setn == null) {
            $show_home_notice = "yes";
        } else {
            $show_home_notice = $setn->value;
        }
        $setn = Setting::where("param", "choosen_page_notice")->first();
        if ($setn == null) {
            $choosen_page_notice = "home";
        } else {
            $choosen_page_notice = $setn->value;
        }



        $setn = Setting::where("param", "openai_api_key")->first();
        if ($setn == null) {
            $openai_api_key = "";
        } else {
            $openai_api_key = $setn->value;
        }

         $setn = Setting::where("param", "openai_api_key")->first();
        if ($setn == null) {
            $openai_api_key = "";
        } else {
            $openai_api_key = $setn->value;
        }

       

        $setn = Setting::where("param", "ai_document_price")->first();
        if ($setn == null) {
            $ai_document_price = "";
        } else {
            $ai_document_price = $setn->value;
        }

        $setn = Setting::where("param", "ai_document_tips")->first();
        if ($setn == null) {
            $ai_document_tips = "";
        } else {
            $ai_document_tips = $setn->value;
        }


        $setn = Setting::where("param", "external_redirect_url")->first();
        if ($setn == null) {
            $external_redirect_url = "";
        } else {
            $external_redirect_url = $setn->value;
        }

        $setn = Setting::where("param", "external_redirect_status")->first();
        if ($setn == null) {
            $external_redirect_status = 0;
        } else {
            $external_redirect_status = $setn->value;
        }


        



        return view('admin.settings', ["ai_document_tips" => $ai_document_tips, "ai_document_price" => $ai_document_price, "openai_api_key" => $openai_api_key, "expiresVis" => $expiresVis, "backdatedTime" => $backdatedTime, "carsearch_api" => $carsearch_api, "checkout_notice" => $checkout_notice, "show_checkout_notice" => $show_checkout_notice, "home_notice" => $home_notice, "show_home_notice" => $show_home_notice, "choosen_page_notice" => $choosen_page_notice, "external_redirect_url" => $external_redirect_url, "external_redirect_status" => $external_redirect_status]);
    }




    public function paymentSettings(Request $request)
    {

        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }

        $settn = Setting::where("param", "stripe_public")->first();
        if ($settn == null) {
            $stripe_public = "";
        } else {
            $stripe_public = $settn->value;
        }

        $settn = Setting::where("param", "stripe_secret")->first();
        if ($settn == null) {
            $stripe_secret = "";
        } else {
            $stripe_secret = $settn->value;
        }

        $settn = Setting::where("param", "square_loc_id")->first();
        if ($settn == null) {
            $square_loc_id = "";
        } else {
            $square_loc_id = $settn->value;
        }

        $settn = Setting::where("param", "square_app_id")->first();
        if ($settn == null) {
            $square_app_id = "";
        } else {
            $square_app_id = $settn->value;
        }

        $settn = Setting::where("param", "square_access_token")->first();
        if ($settn == null) {
            $square_access_token = "";
        } else {
            $square_access_token = $settn->value;
        }

        $settn = Setting::where("param", "payment_gateway")->first();
        if ($settn == null) {
            $payment_gateway = "stripe";
        } else {
            $payment_gateway = $settn->value;
        }


        $settn = Setting::where("param", "square_pmethods")->first();
        if ($settn == null) {
            $square_pmethods = [];
        } else {
            try {
                $square_pmethods = json_decode($settn->value, true);
            } catch (Exception) {
                $square_pmethods = [];
            }
        }


        $setn = Setting::where("param", "paypal_client_id")->first();
        if ($setn == null) {
            $paypal_client_id = config('services.paypal.client_id');
        } else {
            $paypal_client_id = $setn->value;
        }

        $setn = Setting::where("param", "paypal_client_secret")->first();
        if ($setn == null) {
            $paypal_client_secret = config('services.paypal.client_secret');
        } else {
            $paypal_client_secret = $setn->value;
        }


        $setn = Setting::where("param", "now_api_key")->first();
        if ($setn == null) {
            $now_api_key = "";
        } else {
            $now_api_key = $setn->value;
        }

        $setn = Setting::where("param", "now_ipn_secret")->first();
        if ($setn == null) {
            $now_ipn_secret = "";
        } else {
            $now_ipn_secret = $setn->value;
        }
        $setn = Setting::where("param", "nowp_per_off")->first();
        if ($setn == null) {
            $nowp_per_off = 0;
        } else {
            $nowp_per_off = $setn->value;
        }

        






        $setn = Setting::where("param", "bank_name")->first();
        if ($setn == null) {
            $bank_name = "";
        } else {
            $bank_name = $setn->value;
        }
        $setn = Setting::where("param", "bank_sort_code")->first();
        if ($setn == null) {
            $bank_sort_code = "";
        } else {
            $bank_sort_code = $setn->value;
        }
        $setn = Setting::where("param", "bank_account_number")->first();
        if ($setn == null) {
            $bank_account_number = "";
        } else {
            $bank_account_number = $setn->value;
        }
        $setn = Setting::where("param", "bank_ref_number")->first();
        if ($setn == null) {
            $bank_ref_number = "";
        } else {
            $bank_ref_number = $setn->value;
        }
        $setn = Setting::where("param", "show_bank")->first();
        if ($setn == null) {
            $show_bank = 0;
        } else {
            $show_bank = $setn->value;
        }

        $setn = Setting::where("param", "bank_infor_text")->first();
        if ($setn == null) {
            $bank_infor_text = "Please put the reference exactly as shown, otherwise the payment may be reversed. ";
        } else {
            $bank_infor_text = $setn->value;
        }

        $setn = Setting::where("param", "bank_per_off")->first();
        if ($setn == null) {
            $bank_per_off = 0;
        } else {
            $bank_per_off = $setn->value;
        }



        $setn = Setting::where("param", "checkout_checkbox")->first();
        if ($setn == null) {
            $checkout_checkbox = "";
        } else {
            $checkout_checkbox = $setn->value;
        }

        $setn = Setting::where("param", "stripe_whook_secret")->first();
        if ($setn == null) {
            $stripe_whook_secret = "";
        } else {
            $stripe_whook_secret = $setn->value;
        }


        


        $stn = Setting::where("param", "airwallex_client_id")->first();
        if ($stn == null) {
            $airwallex_client_id = '';
        } else {
            $airwallex_client_id = $stn->value;
        }

        $stn = Setting::where("param", "airwallex_api_key")->first();
        if ($stn == null) {
            $airwallex_api_key = '';
        } else {
            $airwallex_api_key = $stn->value;
        }

        $setn = Setting::where("param", "airwallex_whook_secret")->first();
        if ($setn == null) {
            $airwallex_whook_secret = "";
        } else {
            $airwallex_whook_secret = $setn->value;
        }



        $setn = Setting::where("param", "paddle_vendor_id")->first();
        if ($setn == null) {
            $paddle_vendor_id = "";
        } else {
            $paddle_vendor_id = $setn->value;
        }

        $setn = Setting::where("param", "paddle_apikey")->first();
        if ($setn == null) {
            $paddle_apikey = "";
        } else {
            $paddle_apikey = $setn->value;
        }

        $setn = Setting::where("param", "paddle_mode")->first();
        if ($setn == null) {
            $paddle_mode = "";
        } else {
            $paddle_mode = $setn->value;
        }

        $setn = Setting::where("param", "paddle_client_token_live")->first();
        if ($setn == null) {
            $paddle_client_token_live = "";
        } else {
            $paddle_client_token_live = $setn->value;
        }

        $setn = Setting::where("param", "paddle_apikey_live")->first();
        if ($setn == null) {
            $paddle_apikey_live = "";
        } else {
            $paddle_apikey_live = $setn->value;
        }

        $setn = Setting::where("param", "paddle_discord_webhook_url")->first();
        if ($setn == null) {
            $paddle_discord_webhook_url = "";
        } else {
            $paddle_discord_webhook_url = $setn->value;
        }

        $setn = Setting::where("param", "mollie_api_key")->first();
        if ($setn == null) {
            $mollie_api_key = "";
        } else {
            $mollie_api_key = $setn->value;
        }


        // "lemonsqueezy_store_id", "lemonsqueezy_api_key", "lemonsqueezy_webhook_secret"
        $setn = Setting::where("param", "lemonsqueezy_store_id")->first();
        if ($setn == null) {
            $lemonsqueezy_store_id = "";
        } else {
            $lemonsqueezy_store_id = $setn->value;
        }

        $setn = Setting::where("param", "lemonsqueezy_api_key")->first();
        if ($setn == null) {
            $lemonsqueezy_api_key = "";
        } else {
            $lemonsqueezy_api_key = $setn->value;
        }

        $setn = Setting::where("param", "lemonsqueezy_variant_id")->first();
        if ($setn == null) {
            $lemonsqueezy_variant_id = "";
        } else {
            $lemonsqueezy_variant_id = $setn->value;
        }

        $setn = Setting::where("param", "lemonsqueezy_webhook_secret")->first();
        if ($setn == null) {
            $lemonsqueezy_webhook_secret = "";
        } else {
            $lemonsqueezy_webhook_secret = $setn->value;
        }


        return view('admin.payment-settings', [
            "mollie_api_key" => $mollie_api_key, 
            "paddle_apikey_live" => $paddle_apikey_live, 
            "paddle_client_token_live" => $paddle_client_token_live, 
            "paddle_mode" => $paddle_mode, 
            "paddle_apikey" => $paddle_apikey, 
            "paddle_vendor_id" => $paddle_vendor_id, 
            "paddle_discord_webhook_url" => $paddle_discord_webhook_url,
            "payment_gateway" => $payment_gateway, 
            "stripe_public" => $stripe_public, 
            "stripe_secret" => $stripe_secret, 
            "square_app_id" => $square_app_id, 
            "square_access_token" => $square_access_token, 
            "square_loc_id" => $square_loc_id, 
            "square_pmethods" => $square_pmethods, 
            "paypal_client_secret" => $paypal_client_secret, 
            "paypal_client_id" => $paypal_client_id, 
            "now_api_key" => $now_api_key, 
            "now_ipn_secret" => $now_ipn_secret, 
            "bank_name" => $bank_name, 
            "bank_sort_code" => $bank_sort_code, 
            "bank_account_number" => $bank_account_number, 
            "bank_ref_number" => $bank_ref_number, 
            "show_bank" => $show_bank, 
            "nowp_per_off" => $nowp_per_off, 
            "bank_infor_text" => $bank_infor_text, 
            'bank_per_off' => $bank_per_off, 
            'checkout_checkbox' => $checkout_checkbox, 
            "stripe_whook_secret" => $stripe_whook_secret, 
            "airwallex_client_id" => $airwallex_client_id, 
            "airwallex_api_key" => $airwallex_api_key, 
            "airwallex_whook_secret" => $airwallex_whook_secret, 
            "lemonsqueezy_store_id" => $lemonsqueezy_store_id, 
            "lemonsqueezy_api_key" => $lemonsqueezy_api_key, 
            "lemonsqueezy_webhook_secret" => $lemonsqueezy_webhook_secret,
            "lemonsqueezy_variant_id" => $lemonsqueezy_variant_id,
        ]);
    }




    public function updateSetting(Request $request)
    {


        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }


        if ($request->input('param') === 'payment_gateway' && $request->input('value') === 'paddle') {
            
            $paddle_mode = Setting::where('param', 'paddle_mode')->value('value');
            
            $apiKey = $paddle_mode !== 'live' ? Setting::where('param', 'paddle_apikey')->value('value') : Setting::where('param', 'paddle_apikey_live')->value('value');
            $apiBaseUrl = $paddle_mode !== 'live' ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
            $qKey = $paddle_mode !== 'live' ? 'paddle_quote_product_id_test' : 'paddle_quote_product_id';
            $aiKey = $paddle_mode !== 'live' ? 'paddle_ai_product_id_test' : 'paddle_ai_product_id';
            
            $QproductId = Setting::where('param', $qKey)->value('value');
            $AiproductId = Setting::where('param', $aiKey)->value('value');

            try {
                // Create or verify Quote Product
                if (!$QproductId) {
                    
                    $QproductId = $this->createOrGetPaddleProduct(
                        $apiKey, 
                        $apiBaseUrl, 
                        'Quote', 
                        'Quote for Car Registration'
                    );
                    if ($QproductId) {
                        Setting::updateOrCreate(['param' => $qKey], ['value' => $QproductId]);
                        Log::info('Quote Product Created', ['product_id' => $QproductId]);
                    } else {
                        return back()->with('error', 'Failed to create Paddle Quote product.');
                    }
                } else {
                    // Verify product exists in Paddle
                    if (!$this->paddleProductExists($apiKey, $apiBaseUrl, $QproductId)) {
                        
                        $QproductId = $this->createOrGetPaddleProduct(
                            $apiKey, 
                            $apiBaseUrl, 
                            'Quote', 
                            'Quote for Car Registration'
                        );
                        if ($QproductId) {
                            Setting::updateOrCreate(['param' => $qKey], ['value' => $QproductId]);
                        } else {
                            return back()->with('error', 'Failed to create Paddle Quote product.');
                        }
                    }
                }

                // Create or verify AI Document Product
                if (!$AiproductId) {
                    
                    $AiproductId = $this->createOrGetPaddleProduct(
                        $apiKey, 
                        $apiBaseUrl, 
                        'AI Document Download', 
                        'Instant PDF generated from AI prompt'
                    );
                    if ($AiproductId) {
                        Setting::updateOrCreate(['param' => $aiKey], ['value' => $AiproductId]);
                        
                    } else {
                        return back()->with('error', 'Failed to create Paddle AI Document product.');
                    }
                } else {
                    // Verify product exists in Paddle
                    if (!$this->paddleProductExists($apiKey, $apiBaseUrl, $AiproductId)) {
                        
                        $AiproductId = $this->createOrGetPaddleProduct(
                            $apiKey, 
                            $apiBaseUrl, 
                            'AI Document Download', 
                            'Instant PDF generated from AI prompt'
                        );
                        if ($AiproductId) {
                            Setting::updateOrCreate(['param' => $aiKey], ['value' => $AiproductId]);
                        } else {
                            return back()->with('error', 'Failed to create Paddle AI Document product.');
                        }
                    }
                }
                
            } catch (\Exception $e) {
                Log::error('Paddle Setup Error', ['error' => $e->getMessage()]);
                return back()->with('error', 'Failed to setup Paddle products: ' . $e->getMessage());
            }
        }



        $validator = Validator::make($request->all(), [
            'param' => 'required|string|max:30',
            'value' => 'required|string',
            'pags' => 'nullable|array',
            'pags.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $params = ["quote_php_func", "quote_js_func", "expires_vis", "backdated_time", "payment_gateway", "stripe_public", "stripe_secret", "square_app_id", "square_access_token", "square_loc_id", "square_pmethods", "carsearch_api", "paypal_client_secret", "paypal_client_id", "checkout_notice", "show_checkout_notice", "home_notice", "show_home_notice", "choosen_page_notice", "now_api_key", "now_ipn_secret",  "bank_name", "bank_sort_code", "bank_account_number", "bank_ref_number", "show_bank", "nowp_per_off", "bank_per_off", "bank_infor_text", "checkout_checkbox", "stripe_whook_secret", "airwallex_client_id", "airwallex_api_key", "airwallex_whook_secret", "external_redirect_url", "external_redirect_status", "mollie_api_key", "lemonsqueezy_store_id", "lemonsqueezy_api_key", "lemonsqueezy_webhook_secret", "lemonsqueezy_variant_id"];


        if (! in_array($request->param, $params) && strpos($request->param, "page[") === false  && strpos($request->param, "pags[") === false) {
            return response()->json([
                'status' => false,
                'message' => 'Not a valid setting Param',
            ], 400);
        }

        if ($request->param == 'pags[]') {
            foreach ($request->pags as $key => $val) {
                $nparam = 'pags[' . $key . ']';

                $setting = Setting::where("param", $nparam)->first();
                if ($setting == null) {
                    $setting = new Setting();
                    $setting->param = $nparam;
                }
                $setting->value = $val;
                $setting->save();
            }
        } else {

            $setting = Setting::where("param", $request->param)->first();
            if ($setting == null) {
                $setting = new Setting();
                $setting->param = $request->param;
            }
            $setting->value = $request->value;
            $setting->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'successful',
        ], 200);
    }


    public function updateOpenAPISetting(Request $request){
        
        if ($request->has('ai_document_tips')) {
            Setting::updateOrCreate(
                ['param' => 'ai_document_tips'],
                ['value' => 'on']
            );
        } else {
            Setting::updateOrCreate(
                ['param' => 'ai_document_tips'],
                ['value' => 'off']
            );
        }
        $request->request->remove('ai_document_tips');

        // dd($request->all());
        foreach ($request->all() as $param => $value) {
            if($param == '_token') continue;

            Setting::updateOrCreate(
                ['param' => $param],
                ['value' => $value ?? '']
            );
        }





        // If AI price is set and Paddle product is not created yet
        $aiPrice = $request->input('ai_document_price');
        


        if ($aiPrice) {
            
            // $existingPriceId = Setting::where('param', 'paddle_ai_price_id')->value('value');
            
            $paddle_mode = Setting::where('param', 'paddle_mode')->value('value');
            if($paddle_mode != 'live'){
                $apiKey = Setting::where('param', 'paddle_apikey')->value('value');
                $productId = Setting::where('param', 'paddle_ai_product_id_test')->value('value');
            }
            else{
                $apiKey = Setting::where('param', 'paddle_apikey_live')->value('value');
                $productId = Setting::where('param', 'paddle_ai_product_id')->value('value');
            }


            if (!$apiKey) {
                return back()->with('error', 'Missing Paddle API Key in settings.');
            }
            $apiBaseUrl = $paddle_mode !== 'live' ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';


                try {
                     if (!$productId) {
                        //  Setting::updateOrCreate(['param' => 'paddle_product_id'], ['value' => $productId]);
                        $productRes = Http::withToken($apiKey)->post($apiBaseUrl . '/products', [
                            'name' => 'AI Document Download',
                            'description' => 'Instant PDF generated from AI prompt',
                            'type' => 'standard',
                            'tax_category' => 'standard',
                        ]);

                        

                        if (!$productRes->successful()) {
                            Log::error('Paddle Product Create Error', ['response' => $productRes->json()]);
                            return back()->with('error', 'Failed to create Paddle product.');
                        }

                        $productId = $productRes->json('data.id');

                        

                        // Store in settings
                        $keyis =  $paddle_mode != 'live' ? 'paddle_ai_product_id_test' : 'paddle_ai_product_id';
                        Setting::updateOrCreate(['param' => $keyis], ['value' => $productId]);
                    }

                  

                        // Create price
                        $priceRes = Http::withToken($apiKey)->post($apiBaseUrl . '/prices', [
                            'product_id' => $productId,
                            'unit_price' => [
                                'amount' => (string) ($aiPrice * 100),
                                'currency_code' => 'GBP',
                            ],
                            'description' => 'One-time purchase for AI-generated PDF'
                        ]);

                        if (!$priceRes->successful()) {
                            Log::error('Paddle Price Create Error', ['response' => $priceRes->json()]);
                            return back()->with('error', 'Failed to create Paddle price.');
                        }

                        $priceId = $priceRes->json('data.id');
                        Setting::updateOrCreate(['param' => 'paddle_ai_price_id'], ['value' => $priceId]);

                } catch (\Exception $e) {
                    return back()->with('error', 'Failed to create Paddle product/price: ' . $e->getMessage());
                }
            
        }





        return back()->with('success', 'OpenAI settings updated successfully!');
    }


    public function updateExternalRedirectSetting(Request $request){
        foreach ($request->all() as $param => $value) {
            if($param == '_token') continue;

            Setting::updateOrCreate(
                ['param' => $param],
                ['value' => $value ?? '']
            );
        }

        return back()->with('success', 'External redirect settings updated successfully!');
    }



    public function evaluatePhpQuote(Request $request)
    {

        $user = $request->user();
        if (! $user->isAllowed(["SUPER_ADMIN"])) {
            return "Access Restricted";
        }


        $validator = Validator::make($request->all(), [
            'minuteAvailable' => 'required|int',
            'hourAvailable' => 'required|int',
            'dayAvailable' => 'required|int',
            'age' => 'required|int',
            'getQuoteFunctionString' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }


        $minuteAvailable = $request->minuteAvailable;
        $hourAvailable = $request->hourAvailable;
        $dayAvailable = $request->dayAvailable;
        $age = $request->age;
        $getQuoteFunctionString = $request->getQuoteFunctionString;



        $maliciousPatterns = [
            '\beval\b',
            '\bexec\b',
            '\bshell_exec\b',
            '\bsystem\b',
            '\bpassthru\b',
            '\bpopen\b',
            '\bunlink\b',
            '\brmdir\b',
            '\bmkdir\b',
            '\brm\b',
            '\bcopy\b',
            '\bchown\b',
            '\bchmod\b',
            '\bsymlink\b',
            '\breadfile\b',
            '\bfile_put_contents\b',
            '\bfile_get_contents\b',
            '\bfopen\b',
            '\bfclose\b',
            '\bfwrite\b',
            '\brename\b',
            '\btouch\b',
            '\bdelete\b',
            '\bmove_uploaded_file\b',
            '\$_[A-Za-z0-9_]+', // Superglobals and variable injection
            '\bbase64_decode\b',
            '\bgzinflate\b',
            '\bstr_rot13\b',
            '\bassert\b',
            '\bcreate_function\b',
            '\bpreg_replace\b',
            '<script.*?>.*?<\/script>', // JavaScript injections
            '<\?php.*?\?>', // PHP tags injections
            '\bpdo\b',
            '\bmysql\b',
            '\bmysqli\b',
            '\bpg_.*?\(',
            '\bDROP\b',
            '\bTRUNCATE\b',
            '\bINSERT\b',
            '\bSELECT\b',
            '\bUPDATE\b',
            '\bDELETE\b',
            '\bALTER\b',
            '\bRENAME\b',
            '\bMERGE\b',
            '\bLOCK\b',
            '\bUNLOCK\b',
            '\bGRANT\b',
            '\bREVOKE\b',
            '\bROLLBACK\b',
            '\bCOMMIT\b',
            '\bCOPY\b',
            '\bCOPYTO\b',
            '\bhttp\b',
            '\bhttps\b',
            '\bcurl\b',
            '\bfile_get_contents\b',
            '\bstream_context_create\b',
            '\bfsockopen\b',
            '\bpfsockopen\b',
            '\bcurl_exec\b',
            '\bcurl_setopt\b',
            '\|.*\|', // Unix pipe commands
            '#|\-\-', // Comment markers
        ];

        $detectedPatterns = [];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match("/$pattern/i", $getQuoteFunctionString)) {
                $detectedPatterns[] = $pattern;
            }
        }

        if (!empty($detectedPatterns)) {
            return response()->json([
                'status' => false,
                'message' => 'These string / word patterns can\'t be used. If used as variable, please replace. (' . implode(', ', $detectedPatterns) . ')',
            ], 400);
        }


        try {

            // If no malicious code is detected, you can safely execute the code
            eval($getQuoteFunctionString);
            // eval('function getQuote($minuteAvailable, $hourAvailable, $dayAvailable, $age) { return 67; }');

            $final_price = getQuote($minuteAvailable, $hourAvailable, $dayAvailable, $age);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage() . $getQuoteFunctionString,
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'successful',
            'final_price' => $final_price,
        ], 200);
    }


    /**
     * Check if a Paddle product exists
     */
    private function paddleProductExists($apiKey, $apiBaseUrl, $productId)
    {
        if (!$productId) {
            return false;
        }
        
        try {
            $response = Http::withToken($apiKey)->get($apiBaseUrl . '/products/' . $productId);
            
            if (!$response->successful()) {
                return false;
            }

            $status = $response->json('data.status');
            
            // If the product is archived, treat it as not existing
            if ($status === 'archived') {
                Log::warning('Paddle Product archived', ['product_id' => $productId]);
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Paddle Product Check Error', ['product_id' => $productId, 'error' => $e->getMessage()]);
            return false;
        }
    }


    /**
     * Create a Paddle product and return its ID
     */
    private function createOrGetPaddleProduct($apiKey, $apiBaseUrl, $productName, $productDescription)
    {
        try {
            Log::info('Creating Paddle Product', [
                'name' => $productName,
                'description' => $productDescription,
                'api_url' => $apiBaseUrl . '/products'
            ]);

            $response = Http::withToken($apiKey)->post($apiBaseUrl . '/products', [
                'name' => $productName,
                'description' => $productDescription,
                'type' => 'standard',
                'tax_category' => 'standard',
            ]);

            if (!$response->successful()) {
                Log::error('Paddle Product Create Error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'product_name' => $productName
                ]);
                return null;
            }

            $productId = $response->json('data.id');
            
            
            return $productId;

        } catch (\Exception $e) {
            Log::error('Paddle Product Creation Exception', [
                'error' => $e->getMessage(),
                'product_name' => $productName
            ]);
            return null;
        }
    }
}

