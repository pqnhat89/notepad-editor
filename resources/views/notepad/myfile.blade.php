@extends('layouts.app')

@section('content')

<div class="uk-container uk-container-center">
  <table class="uk-table uk-table-striped uk-table-hover">
    <thead>
      <tr>
        <th>Filename</th>
        <th>Lock (turn on to lock file)</th>
        <th>View</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      @if (count($notepads)>0)
      @foreach ($notepads as $notepad)
      <tr>
        <td>{{ $notepad->name }}</td>
        <td>
          <form id="formLock" name="formLock">
            <?php $check = $notepad->lock == "ON" ? "checked" : "" ?>
            <label class="switch">
              {{ csrf_field() }}
              <input class="myonoffswitch" id="{{ $notepad->id }}" type="checkbox" {{ $check }}>
              <div class="slider round"></div>
            </label>
          </form>
        </td>
        <td><a href="{{ url('/'.$notepad->name) }}"><i class="uk-icon uk-icon-medium uk-icon-search-plus"></i></a></td>
        <td>
          <form name="formDeleteNotepad">
            {{ csrf_field() }}
            <a class="deletenotepad" id="{{ $notepad->id }}"><i class="uk-icon uk-icon-medium uk-icon-remove"></i></a>
          </form>
        </td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="4" class="uk-text-center"><code>NO FILE AVAILABLE</code></td>
      </tr>
      @endif
    </tbody>
  </table>
</div>
@endsection