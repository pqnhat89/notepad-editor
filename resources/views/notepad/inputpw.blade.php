@extends('layouts.app')

@section('content')
<div class="uk-container uk-container-center">
  @if (isset($alert))
  <div class="uk-text-center">
    <code>{{ $alert }}</code>
  </div>
  @endif
  @if (!isset($lock))
  <form class="uk-form uk-form-horizontal" method="POST" action="{{ url()->full() }}">
    {{ csrf_field() }}
    <div class="uk-grid uk-grid-width-1-2">
      <div class="uk-panel uk-panel-box uk-panel-box-primary uk-container-center">
        <div class="uk-form-row">
          <label class="uk-form-label">Filename</label>
          <label>{{ $name }}</label>
        </div>
        <div class="uk-form-row">
          <label class="uk-form-label">Required password</label>
          <input type="password" name="filepw" required="">
        </div>
        <div class="uk-text-center uk-margin">
          <button type="submit" class="uk-button uk-button-primary">SUBMIT</button>
        </div>
      </div>
    </div>
  </form>
  @endif
</div>
@endsection