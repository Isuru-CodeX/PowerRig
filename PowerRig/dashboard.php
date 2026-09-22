<?php 
include "connection.php";
session_start();

if(isset($_SESSION["user"])){
  $user = $_SESSION["user"]["email"];
  ?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel | Tivotal</title>

    <!--font awesome-->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

    <!--css file-->
    <link rel="stylesheet" href="dashboard.css" />
  </head>
  <body>
    <?php include "navbar.php"?>

    <section class="content">
    <?php include "nav.php" ?>


      <main>
        <div class="head-title">

          <a href="#" class="download-btn">
            <i class="fas fa-cloud-download-alt"></i>
            <span class="text">Download Report</span>
          </a>
        </div>

        <div class="box-info">
          <li>
            <i class="fas fa-calendar-check"></i>
            <span class="text">
              <h3>1.5K</h3>
              <p>New Orders</p>
            </span>
          </li>
          <li>
            <i class="fas fa-people-group"></i>
            <span class="text">
              <h3>1M</h3>
              <p>Clients</p>
            </span>
          </li>
          <li>
            <i class="fas fa-dollar-sign"></i>
            <span class="text">
              <h3>$900k</h3>
              <p>Turnover</p>
            </span>
          </li>
        </div>

        <div class="table-data">
          <div class="order">
            <div class="head">
              <h3>Recent Orders</h3>
              <i class="fas fa-search"></i>
              <i class="fas fa-filter"></i>
            </div>

            <table>
              <thead>
                <tr>
                  <th>User</th>
                  <th>Order Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <img src="profile.png" alt="" />
                    <p>User Name</p>
                  </td>
                  <td>07-05-2023</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>
                <tr>
                  <td>
                    <img src="profile.png" alt="" />
                    <p>User Name</p>
                  </td>
                  <td>07-05-2023</td>
                  <td><span class="status pending">Pending</span></td>
                </tr>
                <tr>
                  <td>
                    <img src="profile.png" alt="" />
                    <p>User Name</p>
                  </td>
                  <td>07-05-2023</td>
                  <td><span class="status process">Process</span></td>
                </tr>
                <tr>
                  <td>
                    <img src="profile.png" alt="" />
                    <p>User Name</p>
                  </td>
                  <td>07-05-2023</td>
                  <td><span class="status process">Process</span></td>
                </tr>
                <tr>
                  <td>
                    <img src="profile.png" alt="" />
                    <p>User Name</p>
                  </td>
                  <td>07-05-2023</td>
                  <td><span class="status complete">Complete</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="todo">
            <div class="head">
              <h3>Todos</h3>
              <i class="fas fa-plus"></i>
              <i class="fas fa-filter"></i>
            </div>

            <ul class="todo-list">
              <li class="not-completed">
                <p>Todo List</p>
                <i class="fas fa-ellipsis-vertical"></i>
              </li>
              <li class="not-completed">
                <p>Todo List</p>
                <i class="fas fa-ellipsis-vertical"></i>
              </li>
              <li class="completed">
                <p>Todo List</p>
                <i class="fas fa-ellipsis-vertical"></i>
              </li>
              <li class="completed">
                <p>Todo List</p>
                <i class="fas fa-ellipsis-vertical"></i>
              </li>
              <li class="completed">
                <p>Todo List</p>
                <i class="fas fa-ellipsis-vertical"></i>
              </li>
            </ul>
          </div>
        </div>
      </main>
    </section>

    <script src="app.js"></script>
    <script src="script.js"></script>

  </body>
</html>
<?php
}else{
  header("Location: index.php");
}
?>
