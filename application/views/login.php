<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body>
  <script>
    function _loader(){
      if($('#username').val()!=='' && $('#password').val()!==''){
        $('.loader-wrap').removeClass('d-none');
      }
    }
  </script>  

  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/login.css');?>">    

  <div class="loader-wrap d-none">
    <div class="loader">
      <div class="box-1 box"></div>
      <div class="box-2 box"></div>
      <div class="box-3 box"></div>
      <div class="box-4 box"></div>
      <div class="box-5 box"></div>
    </div>
  </div>
	<div class="container-fluid">
	  <div class="row no-gutter">
      <div class="login-brand position-absolute">
        <img src="<? echo app_url('assets/dist/img/logo-utama.png'); ?>" class="brand-image mt-4 ml-4"> 
        <span class="brand-text text-xl ml-4"><?= $app_name; ?></span>
        <a class="vendor-text text-sm ml-2">
        </a>                                  
      </div>
	    <div class="d-md-flex col-lg-4 bg-secondary bg-image">
      </div>
	    <div class="col-lg-2"></div>
	    <div class="col-lg-4">
	      <div class="login d-flex align-items-center py-5">
	        <div class="container">
	          <div class="row">
	            <div class="col-md-9 col-lg-8 mx-auto">
	              <h3 class="login-heading mb-4">Login Area</h3>
	              <? echo @$pesan; ?>
	              <form id="formlogin" method="post">
	                <div class="form-label-group">
	                  <input type="text" name="username" id="username" class="form-control text-sm" placeholder="Nama User" value="<?php echo @$username; ?>" required autofocus>
	                  <label class="text-sm form-label-sm" for="username">Nama User</label>
	                </div>
	                <div class="form-label-group">
	                  <input type="password" name="password" id="password" class="form-control text-sm" placeholder="Password" required>
	                  <label class="text-sm" for="password">Password</label>
	                </div>
	                <button class="btn btn-lg btn-primary btn-block btn-login mb-4" name="submit" type="submit" onclick="_loader();">Login</button>
	                <div align="center">
	                	<a class="small" data-dismiss="modal" data-toggle="modal" href="#lupapass" data-target="#lupapass">Lupa password?</a>
	            	</div>
	              </form>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

    <div class="modal fade" id="lupapass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <h5 class="modal-title text-sm" id="myModalLabel">Lupa Password Login?</h5>                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <form class='form-horizontal' method="post">
              <div class="modal-body">
                  <center class="text-sm text-red">Masukkan Email yang terkait dengan akun anda!</center><br/>
                  <div class="input-group mb-3">
                      <label for="email" class="col-3 control-label">Email</label>
                      <input type="email" class="required form-control text-lowercase" name="email">
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-envelope fa-fw"></span>
                        </div>
                      </div>                                        
                  </div>
                <div style='clear:both'></div>
              </div>
              <div class="modal-footer">
                  <div class="form-group">
                      <div class="col-sm-offset-3">
                          <button type="submit" name='lupa' class="btn btn-primary btn-sm mx-4">Kirimkan Permintaan</button>
                          <a class="text-sm" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href='#login' data-target='#login' title="Lupa Password Members">Kembali Login?</a>
                      </div>
                  </div>                
              </div>
            </form>                            
            </div>
          </div>
    </div>