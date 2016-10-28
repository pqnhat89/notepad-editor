<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\FilecodeRequest;
use App\Http\Requests\NotepadRequest;
use Request;
use App\Filecode;
use App\Notepad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FilecodeController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
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
  public function store(FilecodeRequest $request) {
    $filecode = new Filecode;
    if ($request->filename) {
      $filecode->name = urlencode($request->filename);
    }
    if ($request->filecontent) {
      $filecode->content = urlencode($request->filecontent);
    }
    if ($request->notepadid) {
      $filecode->notepad_id = $request->notepadid;
    }
//    $filecode->hash = Hash::make($request->filename);
    $filecode->hash = str_random(40);
    $filecode->save();
    return redirect(url('/') . '/' . $request->notepadname);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(FilecodeRequest $request, $id) {

    $filecode = DB::table('filecodes')->where('hash', $id)->first();
    $notepad = DB::table('notepads')->where('id', $filecode->notepad_id)->first();

    if ($notepad->lock == "ON") {
      return view('errors/error', array('error' => 'FILE LOCKED, PLEASE TRY AGAIN LATER !'));
    } else {
      if ($request->filepw) {
        if ($notepad->password == $request->filepw) {
          session([$notepad->name => $notepad->password]);
          return redirect(url()->full());
        } else {
          return redirect(url()->full());
        }
      }
      if ($notepad->password) {
        if ($request->session()->get($notepad->name) == $notepad->password) {
          return view('filecode/show', array('filecode' => $filecode, 'style' => $request->style, 'styles' => $this->styles, 'lock' => 'OFF', 'alert' => ''));
        } else {
          return view('notepad/inputpw', array('alert' => 'NEED PASSWORD', 'name' => $filecode->name));
        }
      } else {
        return view('filecode/show', array('filecode' => $filecode, 'style' => $request->style, 'styles' => $this->styles, 'lock' => 'OFF', 'alert' => ''));
      }
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(FilecodeRequest $request, $id) {
    if (Request::ajax()) {

      foreach ($request->data as $data) {

        $filecode = Filecode::find($data['fileid']);

        if ($filecode->updated_at != $data['updated_at']) {
          return 'FALSE';
        }

        if ($data['filename']) {
          $filecode->name = urlencode($data['filename']);
        }
        if ($data['filecontent']) {
          $filecode->content = urlencode($data['filecontent']);
        }
        $filecode->save();
      }
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($hash) {
    DB::table('filecodes')->where('hash', $hash)->delete();
    return back();
  }

  public function search(FilecodeRequest $request) {
    $s = urlencode($request->s);
    $filecodes = DB::table('filecodes')
//            ->join('notepads', 'filecodes.notepad_id', '=', 'notepads.id')
            ->where('filecodes.content', 'like', '%' . $s . '%')
            ->get();
    if (count($filecodes) > 0) {
      return view('filecode/search', array('filecodes' => $filecodes));
    } else {
      return view('filecode/search');
    }
  }

  public function highlight(NotepadRequest $request) {
    $filecode = DB::table('filecodes')->where('hash', $request->hash)->first();

    if (count($filecode) == 0) {
      return view('errors/error', array('error' => 'FILE NOT AVAILABLE, PLEASE RECHECK'));
    }

    $notepad = DB::table('notepads')->where('id', $filecode->notepad_id)->first();

    if ($notepad->lock == "ON") {
      return view('errors/error', array('error' => 'FILE LOCKED, PLEASE TRY AGAIN LATER !'));
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
      if ($request->session()->get($notepad->name) == $notepad->password) {
        return view('notepad/highlight', array('notepad' => $notepad, 'filecode' => $filecode, 'language' => $request->language, 'style' => $request->style, 'styles' => $this->styles));
      } else {
        return view('notepad/inputpw', array('alert' => 'NEED PASSWORD', 'name' => urldecode($filecode->name)));
      }
    } else {
      return view('notepad/highlight', array('notepad' => $notepad, 'filecode' => $filecode, 'language' => $request->language, 'style' => $request->style, 'styles' => $this->styles));
    }
  }

  public function savefile($id) {
    $filecodes = DB::table('filecodes')->where('notepad_id', $id)->get();
    $files_to_zip = array();
    // create file with content
    foreach ($filecodes as $filecode) {
      Storage::put('public/' . $id . '/' . $filecode->name, urldecode($filecode->content));
      array_push($files_to_zip, array($filecode->name => storage_path('app/public/' . $id . '/' . $filecode->name)));
    }
    //create zip file
    $zip = new ZipArchive;
    if ($zip->open($id . '.zip', ZipArchive::CREATE) === TRUE) {
      foreach ($files_to_zip as $values) {
        foreach ($values as $key => $value) {
          $zip->addFile($value, $id . '.' . $key);
        }
      }
      $zip->close();
      // delete directory folder after create zip file
      Storage::deleteDirectory('public/' . $id);
    } else {
      echo 'failed';
    }
    // download file to disk
    return response()->download($id . '.zip');
  }

}
