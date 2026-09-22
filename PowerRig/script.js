const container = document.querySelector(".container"),
  pwShowHide = document.querySelectorAll(".showHidePw"),
  pwFields = document.getElementById("reg_password"),
  signUp = document.querySelector(".signup-link"),
  logIn = document.querySelector(".login-link");

//   js code to show/hide password and change icon
function eyePassword() {
  var eye = document.getElementById("eye-icon");

  if (pwFields.type == "password") {
    pwFields.type = "text";
    eye.classList.replace("uil-eye-slash", "uil-eye");
  } else {
    pwFields.type = "password";
    eye.classList.replace("uil-eye", "uil-eye-slash");
  }
}

var pwFields2 = document.getElementById("password");

function eyePassword2() {
  var eye = document.getElementById("eye-icon2");

  if (pwFields2.type == "password") {
    pwFields2.type = "text";
    eye.classList.replace("uil-eye-slash", "uil-eye");
  } else {
    pwFields2.type = "password";
    eye.classList.replace("uil-eye", "uil-eye-slash");
  }
}


// js code to appear signup and login form
signUp.addEventListener("click", () => {
  container.classList.add("active");
});
logIn.addEventListener("click", () => {
  container.classList.remove("active");
});

function signup() {
  var fname = document.getElementById("fname").value;
  var lname = document.getElementById("lname").value;
  var email = document.getElementById("reg_email").value;
  var password = document.getElementById("reg_password").value;

  var form = new FormData();
  form.append("fname", fname);
  form.append("lname", lname);
  form.append("email", email);
  form.append("password", password);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;
      if (respons == "Success") {
        document.getElementById("reg_msg").innerHTML = "Registation Successful";
        document.getElementById("reg_msg").className = "alert alert-success";
        document.getElementById("reg_msgdiv").className = "d-block";
        window.location.reload();
      } else {
        document.getElementById("reg_msg").innerHTML = respons;
        document.getElementById("reg_msgdiv").className = "d-block";
      }
    }
  };
  request.open("POST", "signupProcess.php", true);
  request.send(form);
}

function login() {
  var email = document.getElementById("email").value;
  var password = document.getElementById("password").value;
  var rememberme = document.getElementById("rememberme").checked;

  var form = new FormData();
  form.append("email", email);
  form.append("password", password);
  form.append("rememberme", rememberme);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var result = request.responseText;
      if (result == "Success") {
        window.location = "home.php";
      } else {
        document.getElementById("msg").innerHTML = result;
        document.getElementById("msgdiv").className = "d-block";
      }
    }
  };
  request.open("POST", "loginProcess.php", true);
  request.send(form);
}

var forgotPasswordModal;
function forgotPassword() {
  var email = document.getElementById("email").value;

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var text = request.responseText;
      if (text == "Success") {
        alert(
          "Verification code has send successfully. Pleace chek your Email"
        );
        var modal = document.getElementById("fpmodal");
        var forgotPasswordModal = new bootstrap.Modal(modal);
        forgotPasswordModal.show();
      } else {
        document.getElementById("msg").innerHTML = text;
        document.getElementById("msgdiv").className = "d-block";
      }
    }
  };

  request.open("GET", "forgotPasswordProcess.php?email=" + email, true);
  request.send();
}

function showPassword() {
  var textfield = document.getElementById("np");
  var button = document.getElementById("npb");

  if (textfield.type == "password") {
    textfield.type = "text";
    button.innerHTML = "Hide";
  } else {
    textfield.type = "password";
    button.innerHTML = "show";
  }
}

function showPassword2() {
  var textfield = document.getElementById("rnp");
  var button = document.getElementById("rnpb");

  if (textfield.type == "password") {
    textfield.type = "text";
    button.innerHTML = "Hide";
  } else {
    textfield.type = "password";
    button.innerHTML = "show";
  }
}

function resetPassword() {
  var email = document.getElementById("email").value;
  var newPassword = document.getElementById("np").value;
  var retypePassword = document.getElementById("rnp").value;
  var verification = document.getElementById("vcode").value;

  var form = new FormData();
  form.append("email", email);
  form.append("newPassword", newPassword);
  form.append("retypePassword", retypePassword);
  form.append("verification", verification);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;
      if (respons == "Success") {
        alert("Password Updated Successsully");
        forgotPasswordModal.hide();
      } else {
        alert(respons);
      }
    }
  };
  request.open("POST", "resetPassword.php", true);
  request.send(form);
}

function changeMainImage(image) {
  const mainImg = document.getElementById("mainImg");
  mainImg.src = image.src;
}

function addToCart(id) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;

      alert(respons);
      window.location.reload();
    }
  };
  request.open("GET", "addToCartProcess.php?id=" + id, true);
  request.send();
}

function addToCartPD(id) {
  var qtyInput = document.getElementById("qty_input" + id);
  var qty = qtyInput.value;
  var formdata = new FormData();
  formdata.append("id", id);
  formdata.append("qty", qty);
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;
      if(respons == "Cart Updated" || respons == "New product added to the cart"){
        swal("Good job!", respons, "success");
        window.location.reload();
      } else{
        swal("Error", respons, "error");
      }

    }
  };
  request.open("POST", "addToCartProcessPD.php", true);
  request.send(formdata);
}

function deleteFromCart(id) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var response = request.responseText;
      if (response == "Removed") {
        alert("Product removed from Cart.");
        window.location.reload();
      } else {
        alert(response);
      }
    }
  };

  request.open("GET", "deleteFromCartProcess.php?id=" + id, true);
  request.send();
}

function changeQTY(id, cid) {
  var qty = document.getElementById("qty_num" + id).value;
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if ((request.status == 200) & (request.readyState == 4)) {
      var response = request.responseText;
      if (response == "Updated") {
        window.location.reload();
      } else {
        alert(response);
        window.location.reload();
      }
    }
  };

  request.open("GET","cartQtyUpdateProcess.php?qty=" + qty + "&id=" + id + "&cid=" + cid,true);
  request.send();
}
// PowerRig Advanced Interactive Grid Engine AJAX Request Handler
function searchProducts() {
    var searchText = document.getElementById("basic_search").value;
    var sortValue = document.getElementById("sort_filter").value;
    
    // Read the active category ID dynamically from the page URL parameters if present
    var urlParams = new URLSearchParams(window.location.search);
    var categoryId = urlParams.get('id') || 0;

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var responseText = request.responseText;
            // Update matrix target element cleanly
            document.getElementById("pid").innerHTML = responseText;
        }
    };

    // Open connection string pointing parameters explicitly to the new processor template file
    request.open("GET", "searchProductsProcess.php?search=" + encodeURIComponent(searchText) + "&sort=" + sortValue + "&category=" + categoryId, true);
    request.send();
}

function addToWishlist(id) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if ((request.status == 200) & (request.readyState == 4)) {
      var response = request.responseText;

      alert(response);
      window.location.reload();
    }
  };

  request.open("GET", "addToWishlistProcess.php?id=" + id, true);
  request.send();
}

function logout() {
  swal({
    title: "Wanna logout?",
    text: "Are you sure you want to log out?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      swal("You log out", {
        icon: "success",
      });
      window.location.href = "logout.php";

    } else {
      swal("Thank you for staying");
    }
  });
}

function addProduct() {
  var sub_category = document.getElementById("subCategory").value;
  var brand = document.getElementById("brand").value;
  var model = document.getElementById("model").value;
  var title = document.getElementById("title").value;
  var condition = 0;

  if (document.getElementById("new").checked) {
    condition = 1;
  } else if (document.getElementById("used").checked) {
    condition = 2;
  }

  var color = document.getElementById("color").value;
  var qty = document.getElementById("qty").value;
  var price = document.getElementById("price").value;
  var dcost = document.getElementById("dcost").value;
  var description = document.getElementById("description").value;
  var image = document.getElementById("imageuploader");

  var form = new FormData();
  form.append("sub_category", sub_category);
  form.append("brand", brand);
  form.append("model", model);
  form.append("title", title);
  form.append("condition", condition);
  form.append("color", color);
  form.append("qty", qty);
  form.append("price", price);
  form.append("dcost", dcost);
  form.append("description", description);

  var file_count = image.files.length;

  for (var x = 0; x < file_count; x++) {
    form.append("image" + x, image.files[x]);
  }

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var response = request.responseText;

      if (response == "success") {
        window.location.reload();
        swal("Success", "Product save", "success");
      } else {
        swal("Error", response, "error");
      }
    }
  };
  request.open("POST", "addProductProcess.php", true);
  request.send(form);
}

function addColor() {
  var color = document.getElementById("new-color").value;

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      var response = request.responseText;
      if(response == "color added"){
        window.location.reload();
      }else{
        swal ("Eroor",response ,  "error" );
      }
    }
  };
  request.open("GET", "addColorProcess.php?color=" + color, true);
  request.send();
}

function changeProductImage() {
  var image = document.getElementById("imageuploader");

  image.onchange = function () {
    var file_count = image.files.length;

    if (file_count <= 4) {
      for (var x = 0; x < file_count; x++) {
        document.getElementById("i" + x).src = "";

        var file = this.files[x];
        var url = window.URL.createObjectURL(file);
        document.getElementById("i" + x).src = url;
      }
    } else {
      swal("Eroor", file_count + " Images. You should only upload only 4 or less than 4 images", "error");
    }
  };
}

function verify_brand_model() {
  var subCategory = document.getElementById("subCategory").value;

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      if (request.response == "Done") {
        document.getElementById("brand-group").style.display = "none";
        document.getElementById("model-group").style.display = "none";
        document.getElementById("condition-group").style.display = "none";
        document.getElementById("color").style.display = "none";
      } else {
        document.getElementById("brand-group").style.display = "block";
        document.getElementById("model-group").style.display = "block";
        document.getElementById("condition-group").style.display = "block";
        document.getElementById("color").style.display = "block";
      }
    }
  };
  request.open("GET", "verifyBMProcess.php?subCategory=" + subCategory, true);
  request.send();
}

function sendid(id) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;
      if (respons == "Success") {
        window.location = "updateProduct.php";
      } else {
        alert(respons);
      }
    }
  };
  request.open("GET", "sendIdProcess.php?id=" + id, true);
  request.send();
}

function removeProduct(id) {
  swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this product!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        var request = new XMLHttpRequest();

        request.onreadystatechange = function () {
          if (request.status == 200 && request.readyState == 4) {
            window.location.reload();
            swal("Poof! Your Product has been deleted!", {
              icon: "success",
            });
          }
        };
        request.open("GET", "removeProductProcess.php?id=" + id, true);
        request.send();

      } else {
        swal("Your Product is safe!");
      }
    });
}

function changeStatus(id) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if ((request.status == 200) & (request.readyState == 4)) {
      var respons = request.responseText;
      if (respons == "Deactivated" || respons == "Activated") {
        window.location.reload();
      } else {
        alert(respons);
      }
    }
  };
  request.open("GET", "changeStatusProcess.php?id=" + id, true);
  request.send();
}

function changeProfileimg() {
  var img = document.getElementById("img");
  var fileInput = document.getElementById("profileimage");
  var file = fileInput.files[0];

  if (file) {
    var url = URL.createObjectURL(file);
    img.src = url;
  }
}

function updateProfile() {
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var line = document.getElementById("line").value;
    var district = document.getElementById("district").value; // Appended Target Property 
    var city = document.getElementById("city").value;
    var image = document.getElementById("profileimage");

    var f = new FormData();
    f.append("fname", fname);
    f.append("lname", lname);
    f.append("line", line);
    f.append("district", district); // Transmitted Target Property
    f.append("city", city);

    if (image.files.length == 1) {
        f.append("i", image.files[0]);
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var t = r.responseText;
            if (t == "success") {
                swal("Profile Updated", "Your information has been saved.", "success")
                .then(() => { location.reload(); });
            } else {
                swal("Action Required", t, "warning");
            }
        }
    };
    r.open("POST", "updateProfileProcess.php", true);
    r.send(f);
}

var av;
function adminVerification() {
  var email = document.getElementById("email").value;

  var request = new XMLHttpRequest();

  var form = new FormData();
  form.append("email", email);

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;
      if (respons == "Success") {
        alert("Please take a look at your email to find the VERIFICATION CODE");
        var adminVerificationModa =
          document.getElementById("verificationModal");
        av = new bootstrap.Modal(adminVerificationModa);
        av.show();
      } else {
        alert(respons);
      }
    }
  };
  request.open("POST", "adminVerificationProcess.php", true);
  request.send(form);
}

function verify() {
  var vcode = document.getElementById("vcode").value;

  var request = new XMLHttpRequest();

  var form = new FormData();
  form.append("vcode", vcode);

  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var respons = request.responseText;

      if (respons == "Success") {
        av.hide();
        window.location = "adminDashboard.php";
      } else {
        alert(respons);
      }
    }
  };
  request.open("POST", "verificationProcess.php", true);
  request.send(form);
}

function payNow(id) {
  var qty = document.getElementById("qty_input" + id).value;
  var f = new FormData();
  f.append("pro_id", id);
  f.append("pro_qty", qty);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      var response = JSON.parse(request.responseText);
      if(response.Error == "none"){
        if (response.url) {
          window.location.href = response.url; // Redirects straight to Stripe
        }
      } else {
        swal("Error", response.Error, "error");
      }
    }
  }

  // UPDATE THIS LINE to point to checkout.php
  request.open("POST", "checkout.php", true); 
  request.send(f);
}

function checkout() {
  var f = new FormData();
  f.append("cart", "true");

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      var response = JSON.parse(request.responseText);
      if (response.url) {
        window.location.href = response.url;
      } else {
        console.error('Error:', response.error);
      }
    }
  }

  request.open("POST", "paymentProcess.php", true);
  request.send(f);
}

function printWindow() {
  var elementsToHide = document.querySelectorAll('.no-print');
  elementsToHide.forEach(function (element) {
    element.style.display = 'none';
  });

  window.print();

  elementsToHide.forEach(function (element) {
    element.style.display = '';
  });
}


function blockUser(email) {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if ((request.status == 200) && (request.readyState == 4)) {
      var response = request.responseText;
      alert(response);
      window.location.reload();
    }
  };
  request.open("GET", "userStatusProcess.php?email=" + email, true);
  request.send();
}

function adminLogout() {
  swal({
    title: "Wanna logout?",
    text: "Are you sure you want to log out?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      swal("You log out", {
        icon: "success",
      });
      window.location.href = "adminLogout.php";

    } else {
      swal("Thank you for staying");
    }
  });
}

function blockProduct(id) {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if ((request.status == 200) && (request.readyState == 4)) {
      var response = request.responseText;
      alert(response);
      window.location.reload();
    }
  };
  request.open("GET", "blockProductProcess.php?id=" + id, true);
  request.send();
}

cm;
function addNewCategory() {
  var m = document.getElementById("addCategoryModal");
  cm = new bootstrap.Modal(m);
  cm.show();
}

function saveCategory() {
  var n = document.getElementById("n").value;
  var e = document.getElementById("e").value;

  var form = new FormData();
  form.append("n", n);
  form.append("e", e);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var response = request.responseText;
      if (response == "success") {
        cm.hide();
        window.location.reload();
      } else {
        swal("Error", response, "error");
      }

    }
  };
  request.open("POST", "saveCategoryProcess.php", true);
  request.send(form);
}

scm;
function addNewSubCategory() {
  var m = document.getElementById("addSubCategoryModal");
  scm = new bootstrap.Modal(m);
  scm.show();
}

function saveSubCategory() {
  var n = document.getElementById("n_sub").value;
  var c = document.getElementById("cat").value;
  var e = document.getElementById("e_sub").value;

  var form = new FormData();
  form.append("n", n);
  form.append("c", c);
  form.append("e", e);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var response = request.responseText;
      if (response == "success") {
        scm.hide();
        window.location.reload();
      } else {
        swal("Error", response, "error");
      }
    }
  };
  request.open("POST", "saveSubCategoryProcess.php", true);
  request.send(form);
}

function changeInvoiceStatus(id) {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.status == 200 && request.readyState == 4) {
      var response = request.responseText;
      if (response == "success") {
        window.location.reload();
      } else {
        alert(response);
      }
    }
  };
  request.open("GET", "changeInvoiceStatusProcess.php?id=" + id, true);
  request.send();
}

var m;
function addFeedback(id) {
  var feedbackModal = document.getElementById("feedbackmodal" + id);
  m = new bootstrap.Modal(feedbackModal);
  m.show();
}


function saveFeedback(id) {

  var comment = document.getElementById("comment" + id).value;

  var form = new FormData();
  form.append("comment", comment);
  form.append("id", id);

  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if ((request.status == 200) & (request.readyState == 4)) {
      var response = request.responseText;
      if (response == "success") {
        swal("Success", "Feedback saved", "success");
        m.hide();
      } else {
        swal("Error", response, "error");
      }
    }
  };

  request.open("POST", "saveCommentProcess.php", true);
  request.send(form);
}

function loadChart() {
  var ctx = document.getElementById("myChart");
  var ctx2 = document.getElementById("myChart2");

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
          var response = JSON.parse(request.responseText);
          var backgroundColors = [
            'rgba(255, 99, 132, 0.2)', // Red
            'rgba(54, 162, 235, 0.2)', // Blue
            'rgba(255, 206, 86, 0.2)', // Yellow
            'rgba(75, 192, 192, 0.2)', // Green
            'rgba(153, 102, 255, 0.2)', // Purple
            'rgba(255, 159, 64, 0.2)', // Orange
            'rgba(199, 199, 199, 0.2)', // Grey
            'rgba(83, 102, 255, 0.2)', // Blue-purple
            'rgba(255, 94, 94, 0.2)', // Light Red
            'rgba(54, 255, 235, 0.2)', // Cyan
            'rgba(154, 102, 255, 0.2)', // Lavender
            'rgba(255, 207, 64, 0.2)'  // Light Orange
        ];

        var borderColors = [
            'rgba(255, 99, 132, 1)', // Red
            'rgba(54, 162, 235, 1)', // Blue
            'rgba(255, 206, 86, 1)', // Yellow
            'rgba(75, 192, 192, 1)', // Green
            'rgba(153, 102, 255, 1)', // Purple
            'rgba(255, 159, 64, 1)', // Orange
            'rgba(199, 199, 199, 1)', // Grey
            'rgba(83, 102, 255, 1)', // Blue-purple
            'rgba(255, 94, 94, 1)', // Light Red
            'rgba(54, 255, 235, 1)', // Cyan
            'rgba(154, 102, 255, 1)', // Lavender
            'rgba(255, 207, 64, 1)'  // Light Orange
        ];
        new Chart(ctx, {
          type: 'bar',
          data: {
              labels: response.months,
              datasets: [{
                  label: 'Total Sales (LKR)',
                  data: response.monthly_price,
                  backgroundColor: backgroundColors,
                  borderColor: borderColors,
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  y: {
                      beginAtZero: true,
                      title: {
                          display: true,
                          text: 'Sales in LKR'
                      }
                  }
              },
              plugins: {
                  legend: {
                      display: true,
                      position: 'top',
                      labels: {
                          boxWidth: 20,
                          padding: 15
                      }
                  }
              },
              responsive: true,
              maintainAspectRatio: true // Adjust chart to fit the container's aspect ratio
          }
      });

          new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: response.labels,
                datasets: [{
                    label: 'Total Sold ',
                    data: response.data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: true
            }
        });
      }
  };
  request.open("POST", "loadChartProcess.php", true);
  request.send();
}

function searchManageProducts() {
  var searchTerm = document.getElementById("search").value.toLowerCase();

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      var response = request.responseText;

      document.querySelectorAll(".scroll-card").forEach(function (element) {
        element.innerHTML = response;
      });
      document.querySelector(".section-title").innerHTML = "searched results";

      document.getElementById("scroll-card").innerHTML = response;
    }
  };
  request.open("GET", "searchProduct.php?searchTerm=" + searchTerm, true);
  request.send();
}

function searchMyProducts() {
  var searchTerm = document.getElementById("search-box").value.toLowerCase();

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      var response = request.responseText;

      document.querySelectorAll(".scroll-card").forEach(function (element) {
        element.innerHTML = response;
      });
      document.querySelector(".section-title").innerHTML = "searched results";

      document.getElementById("scroll-card").innerHTML = response;
    }
  };
  request.open("GET", "searchMyProduct.php?searchTerm=" + searchTerm, true);
  request.send();
}

function deleteUser(email) {
    // Premium SweetAlert confirmation modal before dropping database data
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this user registration record (" + email + ")!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            // Setup async AJAX communication pipeline
            var request = new XMLHttpRequest();
            
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var response = request.responseText.trim();
                    
                    if (response == "success") {
                        swal("Poof! The user account ledger has been successfully purged!", {
                            icon: "success",
                        }).then(() => {
                            // Reload page instantly to reflect clean data in your table layout
                            window.location.reload();
                        });
                    } else {
                        // Display error message from backend
                        swal("Alert!", response, "error");
                    }
                }
            };

            // Transmit processing request via GET method string parameters
            request.open("GET", "deleteUserProcess.php?email=" + encodeURIComponent(email), true);
            request.send();
        }
    });
}

// Function to handle adding a brand
function addNewBrand() {
    var brandName = document.getElementById("brandName").value.trim();

    if (brandName == "") {
        swal("Input Error", "Please fill in a valid brand name before saving.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Success!", "Brand data registered successfully.", "success").then(() => {
                    window.location.reload();
                });
            } else {
                swal("Alert", text, "error");
            }
        }
    };

    r.open("GET", "addBrandProcess.php?name=" + encodeURIComponent(brandName), true);
    r.send();
}

// Function to handle inline brand edits via sweetalert prompt input
function editBrandName(id, currentName) {
    swal({
        title: "Modify Brand Name",
        text: "Update label designation details below:",
        content: {
            element: "input",
            attributes: {
                placeholder: "Enter updated brand name...",
                value: currentName,
            },
        },
        buttons: true,
    }).then((newValue) => {
        if (newValue === null) return; // Admin cancelled action
        
        var updatedName = newValue.trim();
        if (updatedName == "") {
            swal("Invalid Input", "Brand title field cannot be submitted blank.", "warning");
            return;
        }

        var r = new XMLHttpRequest();
        r.onreadystatechange = function () {
            if (r.readyState == 4 && r.status == 200) {
                var text = r.responseText.trim();
                if (text == "success") {
                    swal("Updated!", "Brand label record successfully changed.", "success").then(() => {
                        window.location.reload();
                    });
                } else {
                    swal("Execution Error", text, "error");
                }
            }
        };

        r.open("GET", "editBrandProcess.php?id=" + id + "&name=" + encodeURIComponent(updatedName), true);
        r.send();
    });
}

// Function to safely execute brand records purge
function deleteBrand(id) {
    swal({
        title: "Delete Brand?",
        text: "Warning: Removing this model marker can disconnect foreign relational dependencies in product charts!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    var text = r.responseText.trim();
                    if (text == "success") {
                        swal("Purged", "The brand configuration profile was safely dropped.", "success").then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Operation Blocked", text, "error");
                    }
                }
            };

            r.open("GET", "deleteBrandProcess.php?id=" + id, true);
            r.send();
        }
    });
}

// Function to update Category label strings and rewrite active Brand constraint mappings
function editCategoryName(categoryId, currentName, currentMappingId) {
    // Phase 1: Input text dialog prompting category name updates
    swal({
        title: "Edit Category Name",
        text: "Modify the category text label destination:",
        content: {
            element: "input",
            attributes: {
                placeholder: "Enter new category name...",
                value: currentName,
            },
        },
        buttons: {
            cancel: true,
            confirm: {
                text: "Next: Edit Brand Map",
                closeModal: false,
            }
        },
    }).then((newNameValue) => {
        if (newNameValue === null) return; 

        var updatedName = newNameValue.trim();
        if (updatedName == "") {
            swal("Invalid Input", "Category name cannot be left blank.", "warning");
            return;
        }

        // Phase 2: Perform async query fetching systemic brands to render multi-choice selector
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                try {
                    var brands = JSON.parse(xhr.responseText);
                    
                    var selectHtml = document.createElement("select");
                    selectHtml.className = "form-select text-dark bg-light";
                    selectHtml.style.padding = "10px";
                    selectHtml.style.borderRadius = "8px";
                    selectHtml.style.width = "100%";
                    
                    var defaultOpt = document.createElement("option");
                    defaultOpt.value = "0";
                    defaultOpt.text = "-- No Brand Map (None) --";
                    selectHtml.appendChild(defaultOpt);

                    brands.forEach(function (brand) {
                        var opt = document.createElement("option");
                        opt.value = brand.brand_id;
                        opt.text = brand.brand_name;
                        selectHtml.appendChild(opt);
                    });

                    // Phase 3: Present Brand select dialog window layout
                    swal({
                        title: "Update Brand Relationship Mapping",
                        text: "Select which hardware vendor brand connects to this product layout:",
                        content: selectHtml,
                        buttons: {
                            cancel: true,
                            confirm: "Save All Changes"
                        }
                    }).then((confirmBrandSave) => {
                        if (!confirmBrandSave) return;

                        var selectedBrandId = selectHtml.value;

                        // Phase 4: Forward variables to background processing controller script
                        var r = new XMLHttpRequest();
                        r.onreadystatechange = function () {
                            if (r.readyState == 4 && r.status == 200) {
                                var text = r.responseText.trim();
                                if (text == "success") {
                                    swal("Updated!", "Category configurations successfully synchronized.", "success").then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    swal("Execution Error", text, "error");
                                }
                            }
                        };

                        r.open("GET", "editCategoryProcess.php?id=" + categoryId + "&name=" + encodeURIComponent(updatedName) + "&brand_id=" + selectedBrandId + "&mapping_id=" + currentMappingId, true);
                        r.send();
                    });

                } catch (e) {
                    swal("System Error", "Failed to compile background dropdown data stream maps.", "error");
                }
            }
        };
        xhr.open("GET", "getBrandsJson.php", true);
        xhr.send();
    });
}

// Function to add a standalone core category
function saveCoreCategory() {
    var catName = document.getElementById("newCategoryName").value.trim();

    if (catName == "") {
        swal("Input Error", "Please provide a valid category designation name text.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Saved!", "Core category index added successfully.", "success").then(() => {
                    window.location.reload();
                });
            } else {
                swal("Alert", text, "error");
            }
        }
    };

    r.open("GET", "addCoreCategoryProcess.php?name=" + encodeURIComponent(catName), true);
    r.send();
}

// Function to map a category to a brand
function linkCategoryToBrand() {
    var catId = document.getElementById("mapCategorySelect").value;
    var brandId = document.getElementById("mapBrandSelect").value;

    if (catId == "0" || brandId == "0") {
        swal("Selection Error", "Please specify both a valid Category and Brand selection.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Linked!", "Category-to-Brand constraint map linked successfully.", "success").then(() => {
                    window.location.reload();
                });
            } else {
                swal("Mapping Alert", text, "error");
            }
        }
    };

    r.open("GET", "linkCategoryBrandProcess.php?cat_id=" + catId + "&brand_id=" + brandId, true);
    r.send();
}

// Function to delete either a brand link map row or a standalone category row
function deleteCategoryMapping(targetId, isMapping) {
    var warningMsg = isMapping 
        ? "Warning: Severing this link deletes corresponding product listings mapped under this setup profile!" 
        : "Are you sure you want to completely delete this unmapped core category?";

    swal({
        title: "Confirm Deletion?",
        text: warningMsg,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    var text = r.responseText.trim();
                    if (text == "success") {
                        swal("Dropped!", "Record dropped safely from system storage index charts.", "success").then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Operation Failed", text, "error");
                    }
                }
            };

            r.open("GET", "deleteCategoryProcess.php?id=" + targetId + "&type=" + (isMapping ? "mapping" : "core"), true);
            r.send();
        }
    });
}

// Function to add a standalone core model
function saveCoreModel() {
    var modelName = document.getElementById("newModelName").value.trim();

    if (modelName == "") {
        swal("Input Error", "Please provide a valid model designation name text.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Saved!", "Core model entry registered successfully.", "success").then(() => {
                    window.location.reload();
                });
            } else {
                swal("Alert", text, "error");
            }
        }
    };

    r.open("GET", "addCoreModelProcess.php?name=" + encodeURIComponent(modelName), true);
    r.send();
}

// Function to map a model to an existing category row
function linkModelToCategory() {
    var modelId = document.getElementById("mapModelSelect").value;
    var catId = document.getElementById("mapCategorySelect").value;

    if (modelId == "0" || catId == "0") {
        swal("Selection Error", "Please specify both a valid Model and Category choice.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Linked!", "Model-to-Category constraint map generated successfully.", "success").then(() => {
                    window.location.reload();
                });
            } else {
                swal("Mapping Alert", text, "error");
            }
        }
    };

    r.open("GET", "linkModelCategoryProcess.php?model_id=" + modelId + "&cat_id=" + catId, true);
    r.send();
}

// Function to update Model names and rewrite structural Category map configurations
function editModelName(modelId, currentName, currentMappingId) {
    // Stage 1: Text modal prompting title edits
    swal({
        title: "Edit Model Name",
        text: "Modify the model text identification label details:",
        content: {
            element: "input",
            attributes: {
                placeholder: "Enter updated model name...",
                value: currentName,
            },
        },
        buttons: {
            cancel: true,
            confirm: {
                text: "Next: Edit Category Map",
                closeModal: false,
            }
        },
    }).then((newNameValue) => {
        if (newNameValue === null) return;

        var updatedName = newNameValue.trim();
        if (updatedName == "") {
            swal("Invalid Input", "Model name values cannot be dropped blank.", "warning");
            return;
        }

        // Stage 2: Pull the current categories to dynamically populate secondary dialog dropdown
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                try {
                    var categories = JSON.parse(xhr.responseText);
                    
                    var selectHtml = document.createElement("select");
                    selectHtml.className = "form-select text-dark bg-light";
                    selectHtml.style.padding = "10px";
                    selectHtml.style.borderRadius = "8px";
                    selectHtml.style.width = "100%";
                    
                    var defaultOpt = document.createElement("option");
                    defaultOpt.value = "0";
                    defaultOpt.text = "-- No Category Map (None) --";
                    selectHtml.appendChild(defaultOpt);

                    categories.forEach(function (cat) {
                        var opt = document.createElement("option");
                        opt.value = cat.category_id;
                        opt.text = cat.category_name;
                        selectHtml.appendChild(opt);
                    });

                    // Stage 3: Present select dropdown box layout prompt
                    swal({
                        title: "Update Category Relationship Mapping",
                        text: "Select which component category connects to this hardware build segment:",
                        content: selectHtml,
                        buttons: {
                            cancel: true,
                            confirm: "Save All Changes"
                        }
                    }).then((confirmCatSave) => {
                        if (!confirmCatSave) return;

                        var selectedCatId = selectHtml.value;

                        // Stage 4: Forward parameters to processing file engine
                        var r = new XMLHttpRequest();
                        r.onreadystatechange = function () {
                            if (r.readyState == 4 && r.status == 200) {
                                var text = r.responseText.trim();
                                if (text == "success") {
                                    swal("Updated!", "Model configurations successfully synchronized.", "success").then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    swal("Execution Error", text, "error");
                                }
                            }
                        };

                        r.open("GET", "editModelProcess.php?id=" + modelId + "&name=" + encodeURIComponent(updatedName) + "&cat_id=" + selectedCatId + "&mapping_id=" + currentMappingId, true);
                        r.send();
                    });

                } catch (e) {
                    swal("System Error", "Failed to compile background dropdown data stream maps.", "error");
                }
            }
        };
        xhr.open("GET", "getCategoriesJson.php", true);
        xhr.send();
    });
}

// Function to handle cascading deletion models
function deleteModelMapping(targetId, isMapping) {
    var warningMsg = isMapping 
        ? "Warning: Severing this link deletes corresponding product listings mapped under this setup profile!" 
        : "Are you sure you want to completely delete this unmapped core model?";

    swal({
        title: "Confirm Deletion?",
        text: warningMsg,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    var text = r.responseText.trim();
                    if (text == "success") {
                        swal("Dropped!", "Record dropped safely from system storage index charts.", "success").then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Operation Failed", text, "error");
                    }
                }
            };

            r.open("GET", "deleteModelProcess.php?id=" + targetId + "&type=" + (isMapping ? "mapping" : "core"), true);
            r.send();
        }
    });
}

// Function to register a new shipping cost allocation matrix
function saveDeliveryFee() {
    var districtId = document.getElementById("deliveryDistrictSelect").value;
    var feeValue = document.getElementById("deliveryFeeInput").value.trim();

    if (districtId == "0") {
        swal("Selection Error", "Please pick a target geographic regional district.", "warning");
        return;
    }

    if (feeValue == "" || isNaN(feeValue) || parseFloat(feeValue) < 0) {
        swal("Input Error", "Please provide a valid, non-negative delivery fee amount.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Saved!", "Shipping tariff rules registered successfully.", "success").then(() => {
                    window.location.reload();
                });
            } else {
                swal("Configuration Alert", text, "error");
            }
        }
    };

    r.open("GET", "addDeliveryFeeProcess.php?district_id=" + districtId + "&fee=" + encodeURIComponent(feeValue), true);
    r.send();
}

// Function to update an existing shipping fee amount entry via SweetAlert
function editDeliveryFee(deliveryId, districtName, currentFee) {
    swal({
        title: "Adjust Shipping Fee",
        text: "Update delivery tariff costs (Rs.) for " + districtName + ":",
        content: {
            element: "input",
            attributes: {
                type: "number",
                placeholder: "Enter new fee value...",
                value: currentFee,
                min: "0"
            },
        },
        buttons: {
            cancel: true,
            confirm: "Update Pricing"
        },
    }).then((newValue) => {
        if (newValue === null) return; // Action cancelled out by admin

        var updatedFee = newValue.trim();
        if (updatedFee == "" || isNaN(updatedFee) || parseFloat(updatedFee) < 0) {
            swal("Invalid Input", "Shipping fees must contain valid numerical values.", "warning");
            return;
        }

        var r = new XMLHttpRequest();
        r.onreadystatechange = function () {
            if (r.readyState == 4 && r.status == 200) {
                var text = r.responseText.trim();
                if (text == "success") {
                    swal("Updated!", "Shipping rates adjusted successfully.", "success").then(() => {
                        window.location.reload();
                    });
                } else {
                    swal("Execution Error", text, "error");
                }
            }
        };

        r.open("GET", "editDeliveryFeeProcess.php?id=" + deliveryId + "&fee=" + encodeURIComponent(updatedFee), true);
        r.send();
    });
}

// Function to safely purge a delivery cost entry
function deleteDeliveryConfig(deliveryId) {
    swal({
        title: "Revoke Fee Rule?",
        text: "Are you sure you want to completely erase this shipping configuration rule?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    var text = r.responseText.trim();
                    if (text == "success") {
                        swal("Deleted!", "Shipping tariff rule removed cleanly.", "success").then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Operation Failed", text, "error");
                    }
                }
            };

            r.open("GET", "deleteDeliveryProcess.php?id=" + deliveryId, true);
            r.send();
        }
    });
}

function runAdvancedSearch() {
    var txt = document.getElementById("adv_txt").value;
    var cat = document.getElementById("adv_cat").value;
    var brand = document.getElementById("adv_brand").value;
    var model = document.getElementById("adv_model").value;
    var color = document.getElementById("adv_color").value;
    var min = document.getElementById("adv_min").value;
    var max = document.getElementById("adv_max").value;
    var sort = document.getElementById("adv_sort").value;

    var form = new FormData();
    form.append("t", txt);
    form.append("c", cat);
    form.append("b", brand);
    form.append("m", model);
    form.append("col", color);
    form.append("min", min);
    form.append("max", max);
    form.append("s", sort);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            // Target the results grid inside advancedSearch.php and inject data
            var targetContainer = document.getElementById("adv_results_container");
            if (targetContainer) {
                targetContainer.innerHTML = response;
            }
        }
    };

    request.open("POST", "advancedSearchProcess.php", true);
    request.send(form);
}

function updateFiltersCascade() {
    var cat_id = document.getElementById("adv_cat").value;

    if (cat_id == 0) {
        // Reset to default "All" state if category is cleared
        window.location.reload(); 
        return;
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = JSON.parse(request.responseText);
            
            // Populate Brands
            var brandSelect = document.getElementById("adv_brand");
            brandSelect.innerHTML = '<option value="0">Select Brand</option>';
            response.brands.forEach(b => {
                brandSelect.innerHTML += `<option value="${b.brand_id}">${b.brand_name}</option>`;
            });

            // Populate Models
            var modelSelect = document.getElementById("adv_model");
            modelSelect.innerHTML = '<option value="0">Select Model</option>';
            response.models.forEach(m => {
                modelSelect.innerHTML += `<option value="${m.model_id}">${m.model_name}</option>`;
            });

            // Update product results after filters are populated
            runAdvancedSearch();
        }
    };

    // Call your existing file with 'category' type
    request.open("GET", "loadProductCascadeProcess.php?type=category&cat_id=" + cat_id, true);
    request.send();
}

// Function to launch multi-item and multi-quantity cart transactions using Stripe
function checkoutCartBundle() {
    // Show user feedback while communicating with Stripe API gateway pipeline
    console.log("Initiating multi-item transaction bundle...");
    
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            try {
                var response = JSON.parse(request.responseText);
                
                if (response.url) {
                    // Redirect directly to the secure Stripe hosting gateway
                    window.location.href = response.url;
                } else if (response.error) {
                    // Throw a visible alert rule if custom conditions block checkout
                    alert("Checkout Blocked: " + response.error);
                }
            } catch (e) {
                alert("An unexpected processing anomaly occurred while compiling checkout parameters.");
                console.error(e);
            }
        }
    };

    request.open("POST", "cartCheckoutProcess.php", true);
    request.send();
}