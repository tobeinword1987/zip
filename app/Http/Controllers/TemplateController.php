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

class TemplateController extends Controller
{
    public function uploadTemplates($message = null)
    {
        $templatesOfGenerator = GetFreebieFiles::getTemplatesOfGenerator();
        return view('templates',array('message' => $message, 'templatesOfGenerator' =>$templatesOfGenerator));
    }

    public function addReplaceTemplates(Request $request)
    {
        $input = Input::all();

        $rules = array(
            'template' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('/uploadTemplates')->withErrors($validator)->withInput();;
        }

        //copy zip to public directory
        $file = array_get($input,'template');
        $newFile = $_FILES['template']['name'];

        $templatesDir = env('TEMPLATES');

        copy($file,$templatesDir.'/'.$newFile);

        //return path to zip to the user
        $message = "'".$newFile.'\' was successfully added to server';

        return $this->uploadTemplates($message);
    }
}
