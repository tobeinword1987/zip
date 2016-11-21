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

    public function editTemplates($message = null)
    {
        $templatesOfGenerator = GetFreebieFiles::getTemplatesOfGenerator();
        return view('editTemplates',array('message' => $message, 'templatesOfGenerator' =>$templatesOfGenerator));
    }

    public function saveTemplate(Request $request)
    {
        $dirOldTemplates = env('FREEBIE').'OldTemplates';
        //save file to another location
        if(!is_dir($dirOldTemplates)) {
            mkdir($dirOldTemplates);
        }
        $template = $request->chooseTemplate;
        if($template!='choose template'){
            $oldTemplate = date("dmy_H_i_s").'-'.$template.'.phtml';
            copy(env('TEMPLATES').'/'.$template.'.phtml',$dirOldTemplates.'/'.$oldTemplate);
            //rewrite the existing file
            $newTemplate = $request->editTemplateText;
            
            //create temp file
            $tmpfname = tempnam("/tmp", $template);

            $handle = fopen($tmpfname, "w");
            fwrite($handle, $newTemplate);
            fclose($handle);

            if( substr($output = shell_exec('php -l '.$tmpfname), 0, 16) == 'No syntax errors') {
                $f=fopen(env('TEMPLATES').'/'.$template.'.phtml','w');
                fwrite($f,$newTemplate);
                fclose($f);
                $message = "Template '".$template.'.phtml'.'\' was successfully saved to server';
            } else {
                $message = "Error parsing!";
            }

            unlink($tmpfname);
        } else {
            $message = "You don't choose any template!";
        }

        return $this->editTemplates($message);
    }

    public function getTemplateText()
    {
        $template = $_GET['data'];
        $path = env('TEMPLATES').'/'.$template.'.phtml';
        $content = file_get_contents($path);
        return $content;
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

        //Заменим расширение у файла на phtml
        $newFile = $this->replace_extension_template($newFile);

        $templatesDir = env('TEMPLATES');

        $dirOldTemplates = env('FREEBIE').'OldTemplates';
        //save file to another location
        if(!is_dir($dirOldTemplates)) {
            mkdir($dirOldTemplates);
        }

        if(is_file(env('TEMPLATES').'/'.$newFile)){
            $template = $request->template;
            $oldTemplate = date("dmy_H_i_s").'-'.$newFile;
            copy(env('TEMPLATES').'/'.$newFile,$dirOldTemplates.'/'.$oldTemplate);
        }

        copy($file,$templatesDir.'/'.$newFile);

        //return path to zip to the user
        $message = "'".$newFile.'\' was successfully added to server';

        return $this->uploadTemplates($message);
    }

    //Функция замены расширения темплейта
    public function replace_extension_template($filename){
        $path_info = pathinfo($filename);
        $index = strripos($filename, $path_info['extension']);
        $filename = substr($filename, 0, $index).'phtml';
        return $filename;
    }
}
