<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notepad extends Model {

  protected $fillable = [
      'id', 'name', 'description', 'user_id', 'created_at', 'updated_at'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
//      'password', 'remember_token',
  ];

}
