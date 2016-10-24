<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
//use App\Http\Request;
use App\Http\Requests\NotepadRequest;
use App\Notepad;
use App\Filecode;
use Illuminate\Support\Facades\DB;
use Request;
use Illuminate\Support\Facades\Auth;

class NotepadController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $rand = substr(md5(microtime()), rand(0, 26), 10);
    return view('notepad/index', array('rand' => $rand));
//    return redirect(url('/') . '/' . $rand);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(NotepadRequest $request) {
    $notepad = new Notepad;
    $notepad->name = urlencode($request->notepadname);
    if ($request->notepadpw) {
      session([$request->notepadname => $request->notepadpw]);
      $notepad->password = urlencode($request->notepadpw);
    }
    if (isset(Auth::user()->id)) {
      $notepad->user_id = Auth::user()->id;
    }
    $notepad->save();

    $filecode = new Filecode;
    $new_notepad = DB::table('notepads')->where('name', urlencode($request->notepadname))->first();
    if ($request->filename) {
      $filecode->name = urlencode($request->filename);
    } else {
      $filecode->name = 'New Text Document.txt';
    }
    if ($request->filecontent) {
      $filecode->content = urlencode($request->filecontent);
    }
    $filecode->notepad_id = $new_notepad->id;
    $filecode->save();

    return redirect(url('/') . '/' . $request->notepadname);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(NotepadRequest $request, $name) {
    $notepad = DB::table('notepads')->where('name', urlencode($name))->first();

    if (!$notepad) {

      $create_notepad = new Notepad;
      $create_notepad->name = urlencode($name);
      if (isset(Auth::user()->id)) {
        $create_notepad->user_id = Auth::user()->id;
      }
      $create_notepad->save();

      $new_notepad = DB::table('notepads')->where('name', urlencode($name))->first();

      $create_filecode = new Filecode;
      $create_filecode->name = 'New Text Document.txt';
      $create_filecode->notepad_id = $new_notepad->id;
      $create_filecode->save();

      return redirect(url('/') . '/' . $name);
    }

    $filecodes = DB::table('filecodes')->where('notepad_id', $notepad->id)->get();

    if (count($filecodes) == 0) {
      $create_filecode = new Filecode;
      $create_filecode->name = 'New Text Document.txt';
      $create_filecode->notepad_id = $notepad->id;
      $create_filecode->save();
    }

    if ($notepad->lock == "ON") {
      return view('notepad/inputpw', array('alert' => 'FILE LOCKED, PLEASE TRY AGAIN LATER !', 'name' => $name, 'lock' => 'ON'));
    }

    if ($request->filepw) {
      if ($notepad->password == $request->filepw) {
        session([$notepad->name => $notepad->password]);
        return redirect(url()->full());
      } else {
        return redirect(url()->full());
      }
    }

    if ($notepad->password) {
      if ($request->session()->get($name) == $notepad->password) {
        return view('notepad/show', array('notepad' => $notepad, 'filecodes' => $filecodes, 'styles' => $this->styles));
      } else {
        return view('notepad/inputpw', array('alert' => 'NEED PASSWORD', 'name' => $name));
      }
    } else {
      return view('notepad/show', array('notepad' => $notepad, 'filecodes' => $filecodes, 'styles' => $this->styles));
    }
  }

  public function checkpw(NotepadRequest $request, $name) {
    $notepad = DB::table('notepads')->where('name', $name)->first();
    if ($notepad->password == $request->filepw) {
      session([$notepad->name => $notepad->password]);
      return redirect(url('/') . '/' . $name);
    } else {
      return view('notepad/inputpw', array('alert' => 'WRONG PASSWORD', 'name' => $name));
    }
  }

  public function checkpwhighlight(NotepadRequest $request, $name) {
    $notepad = DB::table('notepads')->where('name', $name)->first();
    if ($notepad->password == $request->filepw) {
      session([$notepad->name => $notepad->password]);
      return redirect(url('/') . '/' . $name);
    } else {
      return view('notepad/inputpw', array('alert' => 'WRONG PASSWORD', 'name' => $name));
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    return 'this is edit function';
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(NotepadRequest $request, $id) {
    if (Request::ajax()) {
      $notepad = Notepad::find($id);
      if ($request->notepadcontent) {
        $notepad->content = urlencode($request->notepadcontent);
      }
      if ($request->notepaddesc) {
        $notepad->description = urlencode($request->notepaddesc);
      }
      if ($request->lock) {
        $notepad->lock = $request->lock;
      }
      $notepad->save();
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    DB::table('notepads')->where('id', $id)->delete();
    DB::table('filecodes')->where('notepad_id', $id)->delete();
  }

  public function myfile() {
    $notepads = DB::table('notepads')->where('user_id', Auth::user()->id)->get();
    return view('notepad/myfile', array('notepads' => $notepads));
  }

}
