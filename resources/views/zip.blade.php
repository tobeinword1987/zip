<?php
    use Illuminate\Support\Facades\Input;
?>
{{--@if(Input::old('fruit')=="orange" checked @endif--}}
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>ZipTask</title>

<!-- Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

<!-- Styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="css\style.css">

<style>
body {
font-family: 'Lato';
}

.fa-btn {
margin-right: 6px;
}
</style>

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="js\script.js"></script>
</head>
<body id="app-layout">
   @if (session('message'))
   <div class="alert alert-success">
     <a href="{{ session('message') }}">Generated zip</a>
   </div>
   @endif

   @if ( $errors->count() > 0 )
   <p>The following errors have occurred:</p>
   @foreach( $errors->all() as $message )
   <span class="help-block">
   <strong>{{ $message }}</strong>
   </span>
   @endforeach
   @endif
   <form role="form" action={{ url('/') }} method="post" enctype="multipart/form-data">
      <div class="row">
         <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
         <div class="col-sm-4">
         <div class="form-group">
         <label class="control-label">ZIP</label><br>
         <div class="inputZip">
            <input type="file" id="zip" name="zip" accept=".zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" value="{{ Input::old('zip') }}">
            <input type="hidden" name="serverPath" value="{{ session('serverPath') }}">
         </div>
         </div><br>
         <div class="form-group">
            <label class="control-label promoFiles">Promo Files</label>
            @foreach($promoFiles as $promo)
               <div class="checkbox">
                  <label>
                     <input type="checkbox" name="checkbox[]" value="{{ $promo }}" @if (is_array(Input::old('checkbox')) && in_array($promo,Input::old('checkbox'))) checked @endif>{{ $promo }}
                  </label>
               </div>
            @endforeach
         </div>
         <div class="form-group">
            <label class="control-label previewTemplate">Preview template</label>
            @foreach($templatesOfGenerator as $template)
               <div class="radio">
                  <label>
                        <input type="radio" name="optionsRadios" id="radio1" value="{{ $template }}" @if (Input::old('optionsRadios')==$template) checked @endif>{{ $template }}
                  </label>
               </div>
            @endforeach
         </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label">Collection name</label><br>
               <div>
                  <input class="form-control input-sm"  placeholder="Input collection name" id="collectionName" name="collectionName" value="{{ Input::old('collectionName') }}">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label">Link to Collection</label><br>
               <div>
                  <input class="form-control input-sm"  placeholder="Input link" id="linkToCollection" name="linkToCollection" value="{{ Input::old('linkToCollection') }}">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label">Link to License</label><br>
               <div>
                  <input class="form-control input-sm"  placeholder="Input link" id="linkToLicence" name="linkToLicence" value="{{ Input::old('linkToLicence') }}">
               </div>
            </div>
            <div class="form-group">
               <label class="control-label">License text</label><br>
               <div>
                  <textarea class="form-control input-sm"  rows="20" placeholder="Input licence text" name="licenceText" id="licenceText">{{ Input::old('licenceText') }}</textarea>
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group" id="addnewHref">
               <label class="control-label">ZIP comment</label><br>
               <pre class="span12" id="zipComment" name="zipComment" html="{{ Input::old('zipComment')}}"></pre>
               <label class="btn btn-sm btn-info preview" value="preview">preview</label>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12" style="text-align: center">
            <button class="btn btn-sbm btn-info" name="generateButton" value="Generate">Generate</button>
         </div>
      </div>
   </form>
</body>
</html>

