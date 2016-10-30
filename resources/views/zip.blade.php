<?php
use Illuminate\Support\Facades\Input;
        $generated_arr = explode('/',$message);
        $generated = array_pop($generated_arr);
?>
@extends('PageTemplate')

@section('bodyContent')
   @if (!empty($message))
   <div class="alert alert-success">
     <a href="{{ $message }}">Download prepared "{{ $generated }}"</a>
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
            @if(!empty(session('serverPath')))
               use uploaded "{{ session('serverPath') }}" or download new one
            @endif
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
@endsection

