<?php
  
  $login ='';
  $name ='';
  $email='';
  $password='';

  if( isset($_SESSION['parametre']) && !empty($_SESSION['parametre'])){
    $_parametre = $_SESSION['parametre'];
    $login =$_parametre->login;
    $name =$_parametre->name;
    $email =$_parametre->email;
    $password =$_parametre->password;
    
  }else{
    $_parametre = array();
  }

 


?>
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Créer un nouveau compte!</h1>
              </div>
              <form class="user" action="<?php echo Router::url('users/newUser') ?> " method="POST">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" name="login" class="form-control form-control-user" id="exampleFirstName" placeholder="Votre login" value="<?php echo $login ?>" >
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="name" class="form-control form-control-user" id="exampleLastName" placeholder="Votre nom " value="<?php echo $name ?>">
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Votre adresse email" value="<?php echo $email ?>">
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Votre mot de passe"value="<?php echo $password ?>">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" name="password2" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Répéter votre mot de passe">
                  </div>
                </div>
                <input type="submit" value="Enregistrer votre compte" class="btn btn-primary btn-user btn-block">
                  
              </form>
              <hr>
              <div class="text-center">
              <a class="small" href="<?php echo Router::url('users/ForgotPassword') ?>" >Vous avez oublié votre mot de passe?</a>
              </div>
              <div class="text-center">
              <a class="small" href="<?php echo Router::url('users/login') ?>">Vous avez déjà un compte? S'identifier!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>