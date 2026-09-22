<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    $user = $_SESSION["admin"]["email"];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PowerRig - Admin Management Platform</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="home.css" />
        <link rel="stylesheet" href="dashboard.css" />
    </head>

    <body>
        <?php include "adminNavbar.php" ?>

        <section class="content">
            <?php include "adnav.php" ?>

            <main>
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>User Registrations Ledger</h3>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th>User Profile</th>
                                    <th>Email Address</th>
                                    <th>Joined Date</th>
                                    <th class="text-center">Administrative Controls</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $query = "SELECT * FROM `user`";
                                $pageno;

                                if (isset($_GET["page"])) {
                                    $pageno = $_GET["page"];
                                } else {
                                    $pageno = 1;
                                }

                                $user_rs = Database::search($query);
                                $user_num = $user_rs->num_rows;

                                $result_per_page = 20;
                                $number_of_pages = ceil($user_num / $result_per_page);

                                $page_results = ($pageno - 1) * $result_per_page;
                                $selected_rs = Database::search($query . " LIMIT " . $result_per_page . " OFFSET " . $page_results . "");

                                $selected_num = $selected_rs->num_rows;

                                for ($x = 0; $x < $selected_num; $x++) {
                                    $selected_data = $selected_rs->fetch_assoc();
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $profile_img_rs = Database::search("SELECT * FROM `profile_img` WHERE 
                                            `user_email`='" . $selected_data["email"] . "'");
                                            $profile_img_num = $profile_img_rs->num_rows;

                                            if ($profile_img_num == 1) {
                                                $profile_img_data = $profile_img_rs->fetch_assoc();
                                                ?>
                                                <img src="<?php echo $profile_img_data["img_path"]; ?>" alt="Profile Picture" />
                                                <?php
                                            } else {
                                                ?>
                                                <img src="./resources/system/fox8.jpg" alt="Default Profile Picture" />
                                                <?php
                                            }
                                            ?>
                                            <p><?php echo $selected_data["fname"] . " " . $selected_data["lname"]; ?></p>
                                        </td>
                                        <td><?php echo $selected_data["email"]; ?></td>
                                        <?php
                                        $splitDate = explode(" ", $selected_data["joined_date"])
                                        ?>
                                        <td><?php echo $splitDate[0]; ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php
                                                if ($selected_data["status_status_id"] == 1) {
                                                    ?>
                                                    <button class="status complete style-btn" style="border: none; cursor: pointer;"
                                                            onclick="blockUser('<?php echo $selected_data['email']; ?>')">
                                                        <i class="fas fa-user-check me-1"></i> Block
                                                    </button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="status process style-btn" style="border: none; cursor: pointer;"
                                                            onclick="blockUser('<?php echo $selected_data['email']; ?>')">
                                                        <i class="fas fa-user-slash me-1"></i> Unblock
                                                    </button>
                                                    <?php
                                                }
                                                ?>
                                                <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                        onclick="deleteUser('<?php echo $selected_data['email']; ?>')">
                                                    <i class="fas fa-trash-can me-1"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Custom Themed Pagination Component -->
<div class="d-flex justify-content-center align-items-center mt-5 mb-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            
            <!-- Previous Button Context Control -->
            <li class="page-item <?php if ($pageno <= 1) { echo 'disabled'; } ?>">
                <a class="page-link" href="<?php echo ($pageno <= 1) ? '#' : '?page=' . ($pageno - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true"><i class="fas fa-chevron-left fa-sm"></i></span>
                </a>
            </li>

            <!-- Page Number Core Selector Loop -->
            <?php
            for ($x = 1; $x <= $number_of_pages; $x++) {
                if ($x == $pageno) {
                    ?>
                    <li class="page-item active">
                        <a class="page-link" href="?page=<?php echo $x; ?>"><?php echo $x; ?></a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $x; ?>"><?php echo $x; ?></a>
                    </li>
                    <?php
                }
            }
            ?>

            <!-- Next Button Context Control -->
            <li class="page-item <?php if ($pageno >= $number_of_pages) { echo 'disabled'; } ?>">
                <a class="page-link" href="<?php echo ($pageno >= $number_of_pages) ? '#' : '?page=' . ($pageno + 1); ?>" aria-label="Next">
                    <span aria-hidden="true"><i class="fas fa-chevron-right fa-sm"></i></span>
                </a>
            </li>

        </ul>
    </nav>
</div>
            </main>
        </section>

        <script src="app.js"></script>
        <script src="script.js"></script>
        <script src="bootstrap.bundle.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>

    </html>
    <?php
} else {
    header("Location: adminLogin.php");
}
?>