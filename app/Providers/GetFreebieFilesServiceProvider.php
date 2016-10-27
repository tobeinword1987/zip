<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GetFreebieFilesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function getTemplatesOfGenerator()
    {
        $dir = env('TEMPLATES');
        $filesInDir = scandir($dir);

        foreach ($filesInDir as $key) {
            if (filetype($dir.'/'.$key)!='dir') {
                $info = pathinfo($key);
                $file_name =  basename($key,'.'.$info['extension']);
                $templatesOfGenerator[]= $file_name;
            }
        }
        return $templatesOfGenerator;
    }

    public static function getPromoFiles()
    {
        $dir = env('PROMO');
        $filesInDir = scandir($dir);

        foreach ($filesInDir as $key) {
            if (filetype($dir.'/'.$key)!='dir') {
                $promoFiles[]= $key;
            }
        }
        return $promoFiles;
    }
}
