<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $settingPGX = Setting::where("param", "LIKE", 'pags[%')->get();
            $pagstn = [];

            foreach ($settingPGX as $setting) {
                $param = str_replace(["pags[", "]"], "", $setting->param);
                $pagstn[$param] = $setting->value;
            }

            $view->with('pagstn', $pagstn);
        });
    }

    public function register()
    {
        //
    }
}
