<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="bootstrap.css" />
  <link rel="stylesheet" href="home.css" /> <link rel="stylesheet" href="style.css" />
  <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">

  <title>PowerRig - Sign In & Register</title>
</head>

<body class="auth-body">
  <div class="container auth-container">
    
    <div class="brand-header text-center">
      <img src="./resources/system/logo/PowerRig.png" alt="Shoplix Logo" class="brand-logo" />
    </div>

    <div class="forms">
      
      <div class="form login">
        <span class="h2 form-title">Welcome Back</span>
        <p class="subtitle">Please enter your credentials to access your account.</p>

        <div class="col-12 mt-2 d-none" id="msgdiv">
          <div class="alert alert-danger" role="alert" id="msg"></div>
        </div>

        <div>
          <?php
          $email = "";
          $password = "";

          if (isset($_COOKIE["email"])) {
            $email = $_COOKIE["email"];
          }

          if (isset($_COOKIE["password"])) {
            $password = $_COOKIE["password"];
          }
          ?>

          <div class="input-field">
            <input type="text" placeholder="Enter your email" id="email" value="<?php echo $email; ?>" required />
            <i class="uil uil-envelope icon"></i>
          </div>
          
          <div class="input-field">
            <input type="password" class="password" placeholder="Enter your password" id="password" value="<?php echo $password; ?>" required />
            <i class="uil uil-lock icon"></i>
            <a onclick="eyePassword2();" class="eye-toggle"><i id="eye-icon2" class="uil uil-eye-slash showHidePw"></i></a>
          </div>

          <div class="checkbox-text">
            <div class="checkbox-content">
              <input type="checkbox" id="rememberme" />
              <label for="rememberme" class="text">Remember me</label>
            </div>
            <a href="#" class="text forgot-link" onclick="forgotPassword();">Forgot password?</a>
          </div>

          <div class="input-field button">
            <input type="button" value="Login" onclick="login();" />
          </div>
        </div>

        <div class="login-signup">
          <span class="text">Not a member? 
            <a href="#" class="text signup-link">Signup Now</a>
          </span>
        </div>
      </div>

      <div class="form signup">
        <span class="h2 form-title">Create Account</span>
        <p class="subtitle">Join Shoplix today and explore premium services.</p>

        <div>
          <div class="col-12 mt-2 d-none" id="reg_msgdiv">
            <div class="alert alert-danger" role="alert" id="reg_msg"></div>
          </div>

          <div class="input-field">
            <input type="text" placeholder="Enter your first name" id="fname" required />
            <i class="uil uil-user icon"></i>
          </div>
          <div class="input-field">
            <input type="text" placeholder="Enter your last name" id="lname" required />
            <i class="uil uil-user icon"></i>
          </div>
          <div class="input-field">
            <input type="text" placeholder="Enter your email" id="reg_email" required />
            <i class="uil uil-envelope icon"></i>
          </div>
          <div class="input-field">
            <input type="password" class="password" placeholder="Create a password" id="reg_password" required />
            <i class="uil uil-lock icon"></i>
            <a onclick="eyePassword();" class="eye-toggle"><i id="eye-icon" class="uil uil-eye-slash showHidePw"></i></a>
          </div>

          <div class="input-field button">
            <input type="button" value="Signup Now" onclick="signup();" />
          </div>
        </div>

        <div class="login-signup">
          <span class="text">Already a member? 
            <a href="#" class="text login-link">Login Now</a>
          </span>
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade" tabindex="-1" id="fpmodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content custom-modal">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bold">Reset Password</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-light shadow-sm small">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control modal-input" id="np" />
                <button id="npb" class="btn btn-custom-outline" type="button" onclick="showPassword();">Show</button>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label text-light shadow-sm small">Re-type Password</label>
              <div class="input-group">
                <input type="password" class="form-control modal-input" id="rnp" />
                <button id="rnpb" class="btn btn-custom-outline" type="button" onclick="showPassword2();">Show</button>
              </div>
            </div>

            <div class="col-12 mt-3">
              <label class="form-label text-light small">Verification Code</label>
              <input type="text" class="form-control modal-input" id="vcode" placeholder="Enter code received" />
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-modal-save" onclick="resetPassword();">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
  <script src="bootstrap.js"></script>
</body>

</html>