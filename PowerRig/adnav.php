<nav>
    <i class="fas fa-bars menu-btn"></i>

    <div style="display: flex; width: 100%; justify-content: flex-end;">


        <a href="#" class="profile" style="margin-left:20px">
            <?php
            $img_rs = Database::search("SELECT * FROM `profile_img` WHERE `user_email`='" . $_SESSION["admin"]["email"] . "'");
            if ($img_rs->num_rows == 0) {
            ?>
                <img src="./resources/system/logo/Swift-art.png" />

            <?php
            } else {
                $img_data = $img_rs->fetch_assoc();
            ?>
                <img src="<?php echo $img_data["img_path"]?>" />

            <?php
            }
            ?>
        </a>
    </div>
</nav>