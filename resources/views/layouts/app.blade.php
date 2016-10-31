<?php $rand = substr(md5(microtime()), rand(0, 26), 10) ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel @yield('title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/uikit/css/uikit.min.css">
    <link rel="stylesheet" href="/assets/numberedtextarea/jquery.numberedtextarea.css">
    <link rel="stylesheet" href="/assets/css/onoffswitch.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <style>
      .uk-button-warning{
        background-color:tomato!important;
        color: #fff!important;
      }
      body {
        background-image: linear-gradient(180deg, rgba(255,255,255,0) 30%, #fff),linear-gradient(70deg, #e0f1ff 32%, #fffae3);
        font-family: 'Lato';
      }

      .fa-btn {
        margin-right: 6px;
      }
    </style>
  </head>
  <body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">

          <!-- Collapsed Hamburger -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- Branding Image -->
          <a class="navbar-brand" href="{{ url('/') }}">
            NOTEPAD
          </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav">
            <!--            <li><a href="#filenormal" data-uk-modal>Create Normal File</a></li>
                        <li><a href="#filesecret" data-uk-modal>Create Secret File</a></li>-->
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="nav navbar-nav navbar-right">
            <li style="padding: 10px 15px">
              <form method="GET" class="uk-form uk-form-horizontal" action="{{ url('/search/s') }}">
                <div class="uk-form-row">
                  <input name="s" placeholder="search" required="" value="">
                  <button type="submit" class="uk-button uk-button-success">GO</button>
                </div>
              </form>
            </li>
            <!-- Authentication Links -->
            @if (Auth::guest())
            <li><a href="{{ url('/login') }}">Login</a></li>
            <li><a href="{{ url('/register') }}">Register</a></li>
            @else
            <li><a href="{{ url('/myfile').'/'.Auth::user()->name }}">My File</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
              </a>

              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="{{ url('/logout') }}"
                     onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                    Logout
                  </a>

                  <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </li>
              </ul>
            </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
    <div class="uk-container uk-container-center">
      @if (count($errors) > 0)
      <ul class="uk-alert uk-alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
      @endif
    </div>
    @yield('content')

    <!-- JavaScripts -->
    <script src="/assets/uikit/js/jquery.js"></script>
    <script src="/assets/highlight/highlight.min.js"></script>
    <script src="/assets/numberedtextarea/jquery.numberedtextarea.js"></script>
    <script src="/assets/uikit/js/uikit.min.js"></script>
    <script src="/assets/uikit/js/core/switcher.min.js"></script>
    <script src="/assets/clipboard/clipboard.min.js"></script>
    <script src="/assets/js/customize.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script>$('textarea').numberedtextarea();</script>
    <script>var clipboard = new Clipboard('.copy-button');</script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script type="text/javascript">
      $(document).ready(function () {

        $('.filesubmit, .highlight, .addnewfile').on('click', function () {
          var data = $('.formShow').serializeArray();
          var url = "{{ url('/filecode') }}/fileupdate";
          $.ajax({
            url: url,
            type: 'PUT',
            cache: false,
            data: data,
            success: function (status) {
              if (status == "FALSE") {
                alert("Data on the server has been changed by another. Please backup data and refresh this page.");
              }
            }
          });
        });

        $('#formLock .myonoffswitch').on('click', function () {
          var _token = $("form[name='formLock']").find("input[name='_token']").val();
          var id = $(this).attr('id');
          if ($(this).is(":checked")) {
            var lock = "ON";
          } else {
            var lock = "OFF";
          }
          var url = "{{ url('/') }}/";
          $.ajax({
            url: url + id,
            type: 'PUT',
            cache: false,
            data: {"_token": _token, "id": id, "lock": lock},
            error: function () {
              if (confirm('UPDATE FAILED ! CLICK OK TO REFRESH PAGE')) {
                window.location.reload();
              }
            }
          });
        });

        $('.deletenotepad').on('click', function () {
          var element = $(this).parent().parent().parent();
          var _token = $("form[name='formDeleteNotepad']").find("input[name='_token']").val();
          var id = this.id;
          var url = "{{ url('/') }}/";
          $.ajax({
            url: url + id,
            type: 'DELETE',
            cache: false,
            data: {"_token": _token, "id": id},
            success: function () {
              element.remove();
            },
            error: function () {
              if (confirm('DELETE FAILED ! CLICK OK TO REFRESH PAGE')) {
                window.location.reload();
              }
            }
          });
        });

      });
    </script>
  </body>
</html>
