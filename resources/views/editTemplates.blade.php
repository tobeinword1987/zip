<?php
use Illuminate\Support\Facades\Input;
?>
@extends('PageTemplate')

@section('bodyContent')
    @if (!empty($message) and (substr($message,0,5)!="Error"))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @elseif(!empty($message))
        <div class="alert alert-danger">
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
    <form role="form" action={{ url('/saveTemplate') }} method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="form-group">
                <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                <div class="col-sm-12">
                    <label class="control-label">Choose template</label><br>
                    <div class="dropdown">
                        <input class="btn btn-primary dropdown-toggle" type="hidden" data-toggle="dropdown" name="chooseTemplate" id="chooseTemplateHidden" value="{{ isset($_POST['chooseTemplate'])?$_POST['chooseTemplate']:'choose template' }}">
                        <input class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"  id="chooseTemplate" value="{{ isset($_POST['chooseTemplate'])?$_POST['chooseTemplate']:'choose template' }}">
                        <ul class="dropdown-menu">
                            @foreach($templatesOfGenerator as $template)
                                <li><a href="#">{{ $template }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Template text</label><br>
            <div>
                <textarea class="form-control input-sm"  placeholder="Template text" name="editTemplateText" id="editTemplateText">{{ isset($_POST['editTemplateText'])?$_POST['editTemplateText']:'' }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="text-align: center">
                <button class="btn btn-sbm btn-info" name="uploadButton" value="Upload">Save</button>
            </div>
        </div>
    </form>
@endsection

