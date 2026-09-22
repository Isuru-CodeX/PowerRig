<?php
session_start();

if (!isset($_SESSION['admin'])) {
  ?>

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
    <link rel="stylesheet" href="home.css" /> 
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
    
    <title>PowerRig - Admin Management Portal</title>
  </head>

  <body class="auth-body">
    <div class="container auth-container">
      
      <div class="brand-header text-center">
        <img src="./resources/system/logo/PowerRig.png" alt="PowerRig Logo" class="brand-logo" />
      </div>

      <div class="forms">
        <div class="form login">
          <span class="h2 form-title">Admin Portal</span>
          <p class="subtitle">Please request a secure administrative dynamic authorization token.</p>

          <div class="col-12 mt-2 d-none" id="msgdiv">
            <div class="alert alert-danger" role="alert" id="msg"></div>
          </div>

          <div>
            <div class="input-field">
              <input type="text" placeholder="Enter your email" id="email" required />
              <i class="uil uil-envelope icon"></i>
            </div>

            <div class="input-field button">
              <input type="button" value="Send Verification Code" onclick="adminVerification();" />
            </div>
          </div>
        </div>

        <div class="modal fade" tabindex="-1" id="verificationModal" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
              <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Admin Verification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label text-light small">Enter Verification Code</label>
                    <input type="text" class="form-control modal-input" id="vcode" placeholder="Enter security key received" />
                  </div>
                </div>
              </div>
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-modal-save" onclick="verify();">Verify</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <script src="script.js"></script>
    <script src="bootstrap.js"></script>

  </body>

  </html>
  <?php
} else {
  header("Location: adminDashboard.php");
}
?>