<section class="sidebar">
    <a href="home.php" class="logo" style="text-decoration: none;">
        <img src="./resources/system/logo/PowerRig.png" width="40" style="margin-left: 5px; margin-right: 20px;">
        <span class="text" style="font-family: var(--poppins); font-weight: 700; color: var(--text-primary);">User Panel</span>
    </a>

    <ul class="side-menu top" style="padding: 0; margin-top: 20px;">
        <li class="active">
            <a class="nav-link" href="userProfile.php">
                <i class="fas fa-user-gear"></i>
                <span class="text">My Profile</span>
            </a>
        </li>
        <li>
            <a href="purchasingHistory.php" class="nav-link">
                <i class="fas fa-border-all"></i>
                <span class="text">Purchased History</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu" style="padding: 0;">
        <li>
            <a href="#" onclick="logout()" class="logout" style="text-decoration: none;">
                <i class="fas fa-right-from-bracket"></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>