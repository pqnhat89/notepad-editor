<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ZipArchive;

class Controller extends BaseController {

  use AuthorizesRequests,
      DispatchesJobs,
      ValidatesRequests;

  public $styles = array(
      'default',
      'agate',
      'androidstudio',
      'arduino-light',
      'arta',
      'ascetic',
      'atelier-cave-dark',
      'atelier-cave-light',
      'atelier-dune-dark',
      'atelier-dune-light',
      'atelier-estuary-dark',
      'atelier-estuary-light',
      'atelier-forest-dark',
      'atelier-forest-light',
      'atelier-heath-dark',
      'atelier-heath-light',
      'atelier-lakeside-dark',
      'atelier-lakeside-light',
      'atelier-plateau-dark',
      'atelier-plateau-light',
      'atelier-savanna-dark',
      'atelier-savanna-light',
      'atelier-seaside-dark',
      'atelier-seaside-light',
      'atelier-sulphurpool-dark',
      'atelier-sulphurpool-light',
      'atom-one-dark',
      'atom-one-light',
      'brown-paper',
      'codepen-embed',
      'color-brewer',
      'darcula',
      'dark',
      'darkula',
      'docco',
      'dracula',
      'far',
      'foundation',
      'github',
      'github-gist',
      'googlecode',
      'grayscale',
      'gruvbox-dark',
      'gruvbox-light',
      'hopscotch',
      'hybrid',
      'idea',
      'ir-black',
      'kimbie.dark',
      'kimbie.light',
      'magula',
      'mono-blue',
      'monokai',
      'monokai-sublime',
      'obsidian',
      'ocean',
      'paraiso-dark',
      'paraiso-light',
      'pojoaque',
      'purebasic',
      'qtcreator_dark',
      'qtcreator_light',
      'railscasts',
      'rainbow',
      'school-book',
      'solarized-dark',
      'solarized-light',
      'sunburst',
      'tomorrow',
      'tomorrow-night',
      'tomorrow-night-blue',
      'tomorrow-night-bright',
      'tomorrow-night-eighties',
      'vs',
      'xcode',
      'xt256',
      'zenburn'
  );

  public function create_zip($files = array(), $destination = '', $overwrite = false) {
    //if the zip file already exists and overwrite is false, return false
    if (file_exists($destination) && !$overwrite) {
      return false;
    }
    //vars
    $valid_files = array();
    //if files were passed in...
    if (is_array($files)) {
      //cycle through each file
      foreach ($files as $file) {
        //make sure the file exists
        if (file_exists($file)) {
          $valid_files[] = $file;
        }
      }
    }
    //if we have good files...
    if (count($valid_files)) {
      //create the archive
      $zip = new ZipArchive();
      if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
        return false;
      }
      //add the files
      foreach ($valid_files as $file) {
        $zip->addFile($file, $file);
      }
      //debug
      //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
      //close the zip -- done!
      $zip->close();

      //check to make sure the file exists
      return file_exists($destination);
    } else {
      return false;
    }
  }

}
