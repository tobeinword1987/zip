<?php
use Illuminate\Support\Facades\Input;
?>
@extends('PageTemplate')

@section('bodyContent')
    @if (!empty($message))
        <div class="alert alert-success">
            {{ $message }}
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
    <form role="form" action={{ url('/uploadPromo') }} method="post" enctype="multipart/form-data">
        <div class="row">
            <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Browse promo</label><br>
                    <div class="inputZip">
                        <input type="file" id="promo" name="promo" accept=".pdf" value="{{ Input::old('template') }}">
                    </div>
                </div><br>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label previewTemplate">Preview promo files</label>
                    <ul>
                        @foreach($promoFiles as $promo)
                            <li>{{ $promo }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="text-align: center">
                <button class="btn btn-sbm btn-info" name="uploadButton" value="Upload">Upload</button>
            </div>
        </div>
    </form>
@endsection

