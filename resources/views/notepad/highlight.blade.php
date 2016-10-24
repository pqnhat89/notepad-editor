@extends('layouts.app')

@section('title', '- HighLight')

@section('content')
<link rel="stylesheet" href="/assets/highlight/styles/{{ $style }}.css">
<div class="uk-container uk-container-center">

  <div class="uk-grid">
    <div class="uk-width-medium-1-2 uk-form-horizontal">
      <div class="uk-form-row">
        <label class="uk-form-label">File name</label>
        <label class="uk-form-label">{{ urldecode($filecode->name) }}</label>
      </div>
    </div>
    <div class="uk-width-medium-1-2 uk-text-right uk-form-row">
      <form class="uk-form uk-form-horizontal" method="get">
        <div class="uk-form-row">
          <label>Style</label>
          <select name="style" onchange="this.form.submit();">
            @foreach ($styles as $value)
            <option {{ $style == $value ? 'selected' : '' }} value="{{ $value }}">{{ $value }}</option>
            @endforeach
          </select>
        </div>
        <input type="hidden" name="language" value="{{ $language }}">
        <input type="hidden" name="fileid" value="{{ $filecode->id }}">
        <div class="uk-form-row uk-margin-small-top uk-margin-small-bottom">
          <label>Font-size</label>
          <a class="text-height-up"><img width="30" src="/assets/img/up_button.png"></a>
          <a class="text-height-down"><img width="30" src="/assets/img/down_button.png"></a>
        </div>
      </form>
    </div>
    <div class="uk-width-medium-1-2">
      <p><code>create at: {{ $filecode->created_at }}</code></p>
    </div>
    <div class="uk-width-medium-1-2 uk-text-right">
      <p><code>updated at: {{ $filecode->updated_at }}</code></p>
    </div>
  </div>
  <div class="uk-position-relative">
    <div class="uk-position-top-right">
      <a class="copy-button uk-button uk-button-warning" data-clipboard-action="copy" data-clipboard-target="#filecontent">COPY TO CLIPBOARD</a>
    </div>
    <pre><code id="filecontent" class="filecontent {{ $language }}">{{ urldecode($filecode->content) }}</code></pre>
  </div>

</div>
@endsection