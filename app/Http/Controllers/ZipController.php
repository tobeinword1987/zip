<?php

namespace App\Http\Controllers;

use App\History;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class ZipController extends Controller
{
    var $serverPath;

    public function getTemplatesOfGenerator()
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
    
    public function getPromoFiles()
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

    public function firstPage($message=null)
    {
        $promoFiles = $this->getPromoFiles();
        $templatesOfGenerator = $this->getTemplatesOfGenerator();
        if (!empty($message)) {
            Session::flash('message',$message);
        }
        return view('zip', array('promoFiles' => $promoFiles, 'templatesOfGenerator' =>$templatesOfGenerator, 'message' => $message));
    }
    
    public function uploadZip(Request $request)
    {
        $tempDir = env('TEMPDIR');

        if(!is_dir($tempDir)) {
            mkdir($tempDir);
        }
        
        $input = Input::all();

//        $request->flash();

        $rules = array(
            'zip' => 'required|Mimes:zip',
            'optionsRadios' => 'required',
            'collectionName' => 'required',
            'linkToCollection' => 'required',
            'linkToLicence' => 'required',
            'licenceText' => 'required',
            'checkbox' => 'required'
        );

        $messages = [
            'checkbox.required' => 'You have to check at least one of the Promo files!',
            'optionsRadios.required' => 'You have to check one  Preview template!',
        ];

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator)->withInput();;
        }

        $letters = $request->checkbox;

        $linkToLicence = $request->linkToLicence;
        $licenceText = $request->licenceText;
        $linkToCollection = $request->linkToCollection;

        $comment="Author:icons8\n".
            "Link:https://icons8.com\n".
            "License:".$linkToLicence.PHP_EOL.
            $licenceText.PHP_EOL.'Have comments? You are very welcome! ['.$linkToCollection.']';

        $extractDir = date("dmy").'/';
        $stuffersDir = env('PROMO').'/';

        //copy zip to public directory
        $file = array_get($input,'zip');
        $newFile = $_FILES['zip']['name'];

        if ($tempDir.$newFile == $request->serverPath) {
            $this->serverPath = $request->serverPath;
//            echo "zip is already generated";
        } else {
            $this->serverPath = $tempDir.$newFile;
//            echo "there is no such zip? i copy it";
            copy($file,$tempDir.$newFile);
        }

        $request->flash();

        $request->session()->put('serverPath',$this->serverPath);

        $zip = new ZipArchive();
        if ($zip->open($tempDir.$newFile) === TRUE) {
            //extract date.zip to temp/date/ directory
            $zip->extractTo($extractDir);

            //read dir to find all promo (pdf) files in it
            $promoFiles = scandir($extractDir);
            foreach ($promoFiles as $promoFile){
                if (is_file($extractDir.$promoFile)) {
                    if (($this->getExtension($promoFile) == 'pdf') and (!in_array($promoFile, $letters))) {
                        //удалим из архива лишние файлы, образовавшиеся вследствии наложения без загрузки архива
                        $zip->deleteName($promoFile);
                    }
                }
            }

            //put promo to zip
            foreach($letters as $stuffer) {
                $content = file_get_contents($stuffersDir.$stuffer);
                $zip->addFromString($stuffer, $content);
            }

            //generate html
            $options = array(
                '--template'      => $request->optionsRadios,
                '--path'    => date("dmy"),
                '--out'     => 'preview.html',
            );

            include_once env('GENERATOR');

            //add comment to zip
            $zip->setArchiveComment($comment);

            $zip->close();
        }

        //return path to zip to the user
        $message = $_SERVER['HTTP_HOST'].'/'.$newFile;
        $message = $tempDir.$newFile;

        $this->delete(date("dmy"));
        if (is_file('preview.html')) {
            unlink('preview.html');
        }
        return $this->firstPage($message);
    }

    //delete directory with contents
    function delete($path)
    {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                $this->delete(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        }

        else if (is_file($path) === true) {
            return unlink($path);
        }
        return false;
    }

    function getExtension($filename) {
        $path_info = pathinfo($filename);
        return $path_info['extension'];
    }
}
