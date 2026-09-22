<nav>
    <i class="fas fa-bars menu-btn"></i>

    <div style="display: flex; width: 100%; justify-content: flex-end; align-items: center;">
        <a href="userProfile.php" class="profile" style="margin-left:20px; display: inline-block; width: 36px; height: 36px; border-radius: 50%; overflow: hidden;">
            <?php
            $img_rs = Database::search("SELECT * FROM `profile_img` WHERE `user_email`='" . $_SESSION["user"]["email"] . "'");
            if ($img_rs->num_rows == 0) {
            ?>
                <img src="./resources/system/logo/PowerRig.png" style="width: 100%; height: 100%; object-fit: cover;" />
            <?php
            } else {
                $img_data = $img_rs->fetch_assoc();
            ?>
                <img src="<?php echo $img_data["img_path"]?>" style="width: 100%; height: 100%; object-fit: cover;" />
            <?php
            }
            ?>
        </a>
    </div>
</nav>