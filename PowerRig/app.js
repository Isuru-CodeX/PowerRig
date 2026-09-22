let sideMenu = document.querySelectorAll(".nav-link");
sideMenu.forEach((item) => {
  let li = item.parentElement;

  item.addEventListener("click", () => {
    sideMenu.forEach((link) => {
      link.parentElement.classList.remove("active");
    });
    li.classList.add("active");
  });
});

let menuBar = document.querySelector(".menu-btn");
let sideBar = document.querySelector(".sidebar");
if (menuBar && sideBar) {
  menuBar.addEventListener("click", () => {
    sideBar.classList.toggle("hide");
  });
}

let searchFrom = document.querySelector(".content nav form");
let searchBtn = document.querySelector(".search-btn");
let searchIcon = document.querySelector(".search-icon");
if (searchBtn && searchFrom && searchIcon) {
  searchBtn.addEventListener("click", (e) => {
    if (window.innerWidth < 576) {
      e.preventDefault();
      searchFrom.classList.toggle("show");
      if (searchFrom.classList.contains("show")) {
        searchIcon.classList.replace("fa-search", "fa-times");
      } else {
        searchIcon.classList.replace("fa-times", "fa-search");
      }
    }
  });
}

window.addEventListener("resize", () => {
  if (searchIcon && searchFrom) {
    if (window.innerWidth > 576) {
      searchIcon.classList.replace("fa-times", "fa-search");
      searchFrom.classList.remove("show");
    }
  }
  if (sideBar) {
    if (window.innerWidth < 768) {
      sideBar.classList.add("hide");
    }
  }
});

if (sideBar && window.innerWidth < 768) {
  sideBar.classList.add("hide");
}

// =========================================================================
// ADD & UPDATE PRODUCT: Cascade Load Brands matching Category constraints
// =========================================================================
function loadCategoryFilters() {
    var categoryId = document.getElementById("pCategory").value;
    var brandSelect = document.getElementById("pBrand");
    var modelSelect = document.getElementById("pModel");

    brandSelect.innerHTML = '<option value="0">-- Select Brand --</option>';
    modelSelect.innerHTML = '<option value="0">-- Select Model --</option>';

    if (categoryId == "0") return;

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            try {
                var response = JSON.parse(r.responseText);

                if (response.brands.length === 0) {
                    brandSelect.innerHTML = '<option value="No Brand">No Brand Found</option>';
                } else {
                    response.brands.forEach(function (brand) {
                        var opt = document.createElement("option");
                        opt.value = brand.brand_id;
                        opt.text = brand.brand_name;
                        brandSelect.appendChild(opt);
                    });
                }

                if (response.models.length === 0) {
                    modelSelect.innerHTML = '<option value="No Model">No Model Found</option>';
                } else {
                    response.models.forEach(function (model) {
                        var opt = document.createElement("option");
                        opt.value = model.model_id;
                        opt.text = model.model_name;
                        modelSelect.appendChild(opt);
                    });
                }
            } catch (e) {
                console.error("Failed to parse category configuration data maps.");
            }
        }
    };
    r.open("GET", "loadProductCascadeProcess.php?type=category&cat_id=" + categoryId, true);
    r.send();
}

function loadFilteredModels() {
    var categoryId = document.getElementById("pCategory").value;
    var brandId = document.getElementById("pBrand").value;
    var modelSelect = document.getElementById("pModel");

    if (brandId == "0" || brandId == "No Brand") return;

    modelSelect.innerHTML = '<option value="0">-- Select Model --</option>';

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            try {
                var models = JSON.parse(r.responseText);
                if (models.length === 0) {
                    modelSelect.innerHTML = '<option value="No Model">No Model Found</option>';
                } else {
                    models.forEach(function (model) {
                        var opt = document.createElement("option");
                        opt.value = model.model_id;
                        opt.text = model.model_name;
                        modelSelect.appendChild(opt);
                    });
                }
            } catch (e) {
                console.error("Failed to re-compile filtered model layout elements.");
            }
        }
    };
    r.open("GET", "loadProductCascadeProcess.php?type=brand&cat_id=" + categoryId + "&brand_id=" + brandId, true);
    r.send();
}

function addNewColorQuick() {
    var newColor = document.getElementById("newColorInput").value.trim();

    if (newColor == "") {
        swal("Input Empty", "Please type out a valid name label before injecting colors.", "warning");
        return;
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text.startsWith("success")) {
                var parts = text.split("|");
                var newId = parts[1];

                var opt = document.createElement("option");
                opt.value = newId;
                opt.text = newColor;
                opt.selected = true;
                document.getElementById("pColor").appendChild(opt);

                document.getElementById("newColorInput").value = "";
                swal("Color Added!", "New color flag added and automatically selected.", "success");
            } else {
                swal("Failed", text, "error");
            }
        }
    };
    r.open("GET", "addNewColorQuickProcess.php?color=" + encodeURIComponent(newColor), true);
    r.send();
}

// Global tracking array holding chosen images in their exact selected order
let selectedProductImagesArray = [];

// Triggers whenever the file input detects a new selection event
function previewProductImages() {
    const fileInput = document.getElementById('productImageFiles');
    const newFiles = Array.from(fileInput.files);
    
    // Safety check constraint: Enforce total image boundary ceiling
    if (selectedProductImagesArray.length + newFiles.length > 6) {
        swal("Limit Reached", "You can upload a maximum of 6 images per product listing.", "warning");
        return;
    }

    // Merge newly selected file entries into our managed array stream
    newFiles.forEach(file => {
        selectedProductImagesArray.push(file);
    });

    renderImagesWorkspaceGrid();
}

// Dynamically draws the custom image queue with order control controls
function renderImagesWorkspaceGrid() {
    const previewContainer = document.getElementById('imagePreviewRow');
    previewContainer.innerHTML = ""; // Clear existing grid cards

    selectedProductImagesArray.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const itemWrapper = document.createElement('div');
            itemWrapper.className = "col-md-2 col-sm-4 col-6 position-relative mb-3";
            
            // Check if this file is at index 0 (The Primary Thumb Home Card display)
            const isMainThumbnail = (index === 0);
            const borderStyle = isMainThumbnail 
                ? "border: 2px solid #ff4a5a; box-shadow: 0 0 12px rgba(255, 74, 90, 0.4);" 
                : "border: 1px solid rgba(255, 255, 255, 0.1);";

            itemWrapper.innerHTML = `
                <div class="position-relative overflow-hidden style-card-wrap" style="border-radius: 12px; background: #1e222b;">
                    <img src="${e.target.result}" style="height:120px; width:100%; object-fit:cover; display:block; ${borderStyle}" />
                    ${isMainThumbnail ? '<span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-0.5 small" style="font-size:10px; border-bottom-right-radius:8px; font-weight:bold; letter-spacing:0.5px;">PRIMARY</span>' : ''}
                    <div class="d-flex gap-1 justify-content-center bg-dark p-1">
                        ${index > 0 ? `<button type="button" class="btn btn-sm btn-outline-info py-0 px-1" style="font-size:11px;" onclick="shiftImagePriorityToFront(${index})"><i class="fas fa-star"></i> Set First</button>` : ''}
                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" style="font-size:11px;" onclick="removeTargetFileFromBundle(${index})"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            previewContainer.appendChild(itemWrapper);
        };
        reader.readAsDataURL(file);
    });
}

// Splices the selected image and injects it straight to the absolute first position (Index 0)
function shiftImagePriorityToFront(currentIndex) {
    if (currentIndex > 0) {
        const selectedTargetItem = selectedProductImagesArray.splice(currentIndex, 1)[0];
        selectedProductImagesArray.unshift(selectedTargetItem); // Push to first position
        renderImagesWorkspaceGrid();
    }
}

// Discards a single selected image from the upload payload context list 
function removeTargetFileFromBundle(targetIndex) {
    selectedProductImagesArray.splice(targetIndex, 1);
    renderImagesWorkspaceGrid();
}

// Sends the serialized data stream sequentially to your backend process files
function processProductAddition() {
    var category = document.getElementById("pCategory").value;
    var brand = document.getElementById("pBrand").value;
    var model = document.getElementById("pModel").value;
    var title = document.getElementById("pTitle").value;
    var color = document.getElementById("pColor").value;
    var qty = document.getElementById("pQty").value;
    var price = document.getElementById("pPrice").value;
    var desc = document.getElementById("pDescription").value;

    // Build the payload
    var form = new FormData();
    form.append("category", category);
    form.append("brand", brand);
    form.append("model", model);
    form.append("title", title);
    form.append("color", color);
    form.append("qty", qty);
    form.append("price", price);
    form.append("desc", desc);

    // CRITICAL FIX: Append your ordered custom array sequence explicitly into FormData 
    selectedProductImagesArray.forEach((file, index) => {
        form.append("imgFile" + index, file);
    });

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            if (r.responseText.trim() == "success") {
                swal("Cataloged!", "Product listing added with correct layout sorting.", "success")
                .then(() => { location.reload(); });
            } else {
                swal("Validation Alert", r.responseText, "error");
            }
        }
    };
    r.open("POST", "addProductProcess.php", true);
    r.send(form);
}

// =========================================================================
// UPDATE PRODUCT: Execution Sync Block FIXED Form element mapping references
// =========================================================================
function executeFullProductUpdate(productId) {
    // FIX: Element IDs synced perfectly with updated updateProduct.php view layer variables
    var category = document.getElementById("pCategory").value;
    var brand = document.getElementById("pBrand").value;
    var model = document.getElementById("pModel").value;
    var title = document.getElementById("pTitle").value.trim();
    var color = document.getElementById("pColor").value;
    var qty = document.getElementById("pQty").value.trim();
    var price = document.getElementById("pPrice").value.trim();
    var desc = document.getElementById("pDescription").value.trim();
    var images = document.getElementById("productImageFiles").files;

    if (category == "0" || brand == "0" || brand == "No Brand" || model == "0" || model == "No Model") {
        swal("Requirements Unmet", "Please map out a valid Category, Brand, and Model setup profile.", "error");
        return;
    }

    if (title == "") { swal("Validation Error", "Please provide a valid descriptive title.", "warning"); return; }
    if (color == "0") { swal("Validation Error", "Please assign an accent color scheme trait.", "warning"); return; }
    if (qty == "" || parseInt(qty) < 1) { swal("Validation Error", "Stock quantities must register at minimum 1 unit.", "warning"); return; }
    if (price == "" || parseFloat(price) <= 0) { swal("Validation Error", "Product unit pricing must be greater than Rs. 0.00.", "warning"); return; }
    if (desc == "") { swal("Validation Error", "Specifications content cannot be blank.", "warning"); return; }

    var form = new FormData();
    form.append("id", productId);
    form.append("category", category);
    form.append("brand", brand);
    form.append("model", model);
    form.append("title", title);
    form.append("color", color);
    form.append("qty", qty);
    form.append("price", price);
    form.append("desc", desc);

    for (var x = 0; x < images.length; x++) {
        form.append("imgFile" + x, images[x]);
    }

    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var text = r.responseText.trim();
            if (text == "success") {
                swal("Changes Saved!", "Inventory item profile was updated successfully.", "success").then(() => {
                    window.location.href = "manageProducts.php";
                });
            } else {
                swal("System Operation Failed", text, "error");
            }
        }
    };
    r.open("POST", "updateFullProductProcess.php", true);
    r.send(form);
}

// =========================================================================
// INVENTORY MANAGEMENT: Status Toggling & Record Deletions
// =========================================================================
function toggleProductStatus(productId) {
    var r = new XMLHttpRequest();
    r.onreadystatechange = function () {
        if (r.readyState == 4 && r.status == 200) {
            var response = r.responseText.trim();
            if (response == "activated" || response == "deactivated") {
                window.location.reload();
            } else {
                swal("Operation Failed", response, "error");
            }
        }
    };
    r.open("GET", "toggleStatusProcess.php?id=" + productId, true);
    r.send();
}

function deleteProductRecord(productId) {
    swal({
        title: "Are you certain?",
        text: "Warning! Deleting this inventory profile purges all associated pricing references and images permanently from the hard drive.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var r = new XMLHttpRequest();
            r.onreadystatechange = function () {
                if (r.readyState == 4 && r.status == 200) {
                    if (r.responseText.trim() == "success") {
                        swal("Purged!", "Product directory tracking record deleted.", "success").then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Operation Blocked", r.responseText, "error");
                    }
                }
            };
            r.open("GET", "deleteProductProcess.php?id=" + productId, true);
            r.send();
        }
    });
}