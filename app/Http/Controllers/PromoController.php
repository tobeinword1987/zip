<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\History;
use File;
use GetFreebieFiles;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class PromoController extends Controller
{
    public function uploadPromo($message = null)
    {
        $promoFiles = GetFreebieFiles::getPromoFiles();
        return view('uploadPromo',array('message' => $message, 'promoFiles' =>$promoFiles));
    }

    public function addReplacePromo(Request $request)
    {
        $input = Input::all();

        $rules = array(
            'promo' => 'required|Mimes:pdf'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('/uploadPromo')->withErrors($validator)->withInput();;
        }

        //copy zip to public directory
        $file = array_get($input,'promo');
        $newFile = $_FILES['promo']['name'];

        $templatesDir = env('PROMO');

        $dirOldPromo = env('FREEBIE').'OldPromo';
        //save file to another location
        if(!is_dir($dirOldPromo)) {
            mkdir($dirOldPromo);
        }

        if(is_file(env('PROMO').'/'.$newFile)){
            $promo = $request->promo;
            $oldPromo = date("dmy_H_i_s").'-'.$newFile;
            copy(env('PROMO').'/'.$newFile,$dirOldPromo.'/'.$oldPromo);
        }


        copy($file,$templatesDir.'/'.$newFile);

        //return path to zip to the user
        $message = "'".$newFile.'\' was successfully added to server';

        return $this->uploadPromo($message);
    }
}
