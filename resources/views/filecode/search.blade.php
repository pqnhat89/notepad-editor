@extends('layouts.app')

@section('content')

<div class="uk-container uk-container-center">
  <table class="uk-table uk-table-striped uk-table-hover">
    <thead>
      <tr>
        <th>File name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if (isset($filecodes))
      @foreach ($filecodes as $filecode)
      <tr>
        <td>{{ urldecode($filecode->name) }}</td>
        <td><a href="{{ url('/readfile'.'/'.$filecode->id) }}">VIEW</a></td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="4" class="uk-text-center"><code>NO FILE AVAILABLE</code></td>
      </tr>
    </tbody>
  </table>
  @endif
</div>
@endsection