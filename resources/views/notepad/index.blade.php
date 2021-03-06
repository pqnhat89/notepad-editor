@extends('layouts.app')

@section('title', '- Index')

@section('content')
<div class="uk-container uk-container-center">
  <div class="uk-panel uk-container uk-container-center">
    <form class="uk-form" method="POST" name="formNotepadAdd" action="{{ url('/') }}">
      {{ csrf_field() }}
      <div class="uk-grid">
        <div class="uk-form-row uk-width-medium-1-2">
          <label class="uk-form-label">Folder name : </label>
          <input name='notepadname' value="{{ $rand }}">
        </div>
        <div class="uk-width-medium-1-2 uk-text-right">
          <!--<a href="#notepadsecret" class="uk-button uk-button-danger uk-width-1-1" data-uk-modal>CREATE SECRET FOLDER</a>-->
        </div>
      </div>
      <input class="uk-width-1-1 uk-margin-top" name='filename' value="New Text Document.txt" placeholder="please input your filename" required="">
      <textarea name="filecontent" style="width: 100%" rows="20" placeholder="please input your content"></textarea>
      <div class="uk-text-right uk-margin">
        <button type="submit" class="uk-button uk-button-primary">
          <i class="uk-icon uk-icon-expand"></i> CREATE PUBLISH FOLDER
        </button>
        <a href="#notepadsecret" class="uk-button uk-button-danger" data-uk-modal>
          <i class="uk-icon uk-icon-expand"></i> CREATE SECRET FOLDER
        </a>
      </div>
      <div id="notepadsecret" class="uk-modal">
        <div class="uk-modal-dialog">
          <a class="uk-modal-close uk-close"></a>
          <div class="uk-panel uk-container uk-container-center">
            <div class="uk-panel-title">CREATE SECRET FOLDER</div>
            <div class="uk-form uk-form-horizontal uk-panel uk-container uk-container-center">
              <div class="uk-form-row">
                <label class="uk-form-label" for="#notepadpw">Password</label>
                <input name="notepadpw" value="">
              </div>
              <div class="uk-form-row uk-text-center">
                <button type="submit" class="uk-button uk-button-danger">
                  <i class="uk-icon uk-icon-expand"></i> CREATE SECRET FOLDER
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection