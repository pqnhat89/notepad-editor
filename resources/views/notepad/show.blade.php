@extends('layouts.app')

@section('title', '- Show')

@section('content')

<div class="uk-container uk-container-center">

  <div class="uk-grid">
    <div class="uk-width-medium-1-2 uk-form-horizontal">
      <label class="uk-form-label">Filename</label>
      <label class="uk-form-label">{{ urldecode($notepad->name) }}</label>
    </div>
    <div class="uk-width-medium-1-2 uk-text-right uk-margin-bottom">
      <div class="uk-form-row">
        <label class="uk-form-label">Set Font Size</label>
        <a class="text-height-up"><img width="30" src="/assets/img/up_button.png"></a>
        <a class="text-height-down"><img width="30" src="/assets/img/down_button.png"></a>
      </div>
    </div>
  </div>

  @if (count($filecodes)>0)
  <div class="uk-grid uk-margin-top-remove">
    <div class="uk-width-medium-1-2">
      <a href="#addnewfile" data-uk-modal class="uk-button uk-button-success">ADD NEW FILE</a>
    </div>
    <div class="uk-width-medium-1-2 uk-text-right">
      <a href="#hightlight" class="uk-button uk-button-warning" data-uk-modal>HIGHLIGHT</a>
    </div>
  </div>
  <ul class="uk-subnav uk-subnav-pill uk-margin-small-top" data-uk-switcher="{connect:'#subnav-pill-content-1'}">
    @foreach ($filecodes as $filecode)
    <li><a href="#">{{ urldecode($filecode->name) }}</a></li>
    @endforeach
  </ul>
  <form class="uk-form formShow">
    {{ csrf_field() }}
    <ul id="subnav-pill-content-1" class="uk-switcher">
      @foreach ($filecodes as $filecode)
      <li>
        <div class="uk-grid">
          <div class="uk-width-medium-1-2">
            <p><code>create at: {{ $filecode->created_at }}</code></p>
          </div>
          <div class="uk-width-medium-1-2 uk-text-right">
            <p><code>updated at: {{ $filecode->updated_at }}</code></p>
          </div>
        </div>
        <input class="uk-width-1-1" name="data[{{ $filecode->id }}][filename]" id="filename" placeholder="Filename" value="{{ urldecode($filecode->name) }}">
        <textarea class="filecontent" name="data[{{ $filecode->id }}][filecontent]" style="width: 100%" rows="20">{{ urldecode($filecode->content) }}</textarea>
        <input type="hidden" value="{{ $filecode->id }}" name="data[{{ $filecode->id }}][fileid]">
        <input type="hidden" value="{{ $notepad->id }}" name="data[{{ $filecode->id }}][notepadid]">
      </li>
      @endforeach
    </ul>
    <div class="uk-margin uk-text-center">
      <a class="filesubmit uk-button uk-button-primary uk-width-1-1">
        <i class="uk-icon uk-icon-expand"></i> Save
      </a>
    </div>
  </form>
  @else
  <form method="POST" class="uk-form" action="{{ url('filecode') }}">
    <input class="uk-width-1-1" name="filename" id="filename" placeholder="Filename" value="">
    {{ csrf_field() }}
    <textarea class="filecontent" name="filecontent" style="width: 100%" rows="20"></textarea>
    <input type="hidden" value="{{ $notepad->id }}" name="notepadid">
    <input type="hidden" value="{{ $notepad->name }}" name="notepadname">
    <div class="uk-margin uk-text-center">
      <button type="submit" class="uk-button uk-button-primary uk-width-1-1">
        <i class="uk-icon uk-icon-expand"></i> Save
      </button>
    </div>
  </form>
  @endif

</div>

<div id="addnewfile" class="uk-modal">
  <div class="uk-modal-dialog">
    <a class="uk-modal-close uk-close"></a>
    <div class="uk-panel uk-container uk-container-center">
      <div class="uk-panel-title">Create New File</div>
      <div class="uk-panel uk-container uk-container-center">
        <form class="uk-form uk-form-horizontal" method="POST" action="{{ url('/filecode') }}">
          {{ csrf_field() }}
          <div class="uk-form-row">
            <label class="uk-form-label">Filename</label>
            <label class="uk-form-label">{{ $notepad->name }}</label>
          </div> 
          <div class="uk-form-row">
            <label class="uk-form-label">Tabname <strong class="uk-text-danger">*</strong></label>
            <input name="filename" required="">
          </div>
          <input type="hidden" name="notepadid" value="{{ $notepad->id }}">
          <input type="hidden" name="notepadname" value="{{ $notepad->name }}">
          <div class="uk-margin uk-text-center">
            <button type="submit" class="uk-button uk-button-primary">
              <i class="uk-icon uk-icon-expand"></i> Create
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="hightlight" class="uk-modal">
  <div class="uk-modal-dialog">
    <a class="uk-modal-close uk-close"></a>
    <div class="uk-panel uk-container uk-container-center">
      <div class="uk-panel-title">Create Highlight</div>
      <div class="uk-panel uk-container uk-container-center">
        <form class="uk-form uk-form-horizontal" method="GET" action="{{ url('/highlight').'/readfile' }}">
          <div class="uk-form-row">
            <label class="uk-form-label">Filename</label>
            <label class="uk-form-label">{{ $notepad->name }}</label>
          </div>
          <div class="uk-form-row">
            <label class="uk-form-label">Choose File</label>
            <select name="fileid">
              @foreach ($filecodes as $filecode)
              <option value="{{ $filecode->id }}">{{ urldecode($filecode->name) }}</option>
              @endforeach
            </select>
          </div>
          <div class="uk-form-row">
            <label class="uk-form-label">Style</label>
            <select name="style">
              @foreach ($styles as $style)
              <option value="{{ $style }}">{{ $style }}</option>
              @endforeach
            </select>
          </div>
          <div class="uk-form-row">
            <label class="uk-form-label">Language : </label>
            <input type="text" name="language" placeholder="css, html, php, ...">
            <p class="uk-text-small">null = auto detect</p>
          </div>
          <button class="uk-button uk-button-danger uk-width-1-1 uk-margin-top" type="submit" formtarget="_blank">highlight</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection