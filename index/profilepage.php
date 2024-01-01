<?php include("sessionAPI.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="main.css" />
  <link rel="stylesheet" href="fypgrid.css" />
  <link rel="stylesheet" href="profilegrid.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="main-screen bg-gray-700 w-screen h-screen">
    <side-bar class="bg-gray-900">
      <form method="post" class="bg-gray-900 flex flex-col justify-start items-center pt-5 gap-3">
        <button name="user"
          class="w-[4rem] h-[4rem]  bg-cyan-500 rounded-full flex justify-center items-center hover:cursor-default">
          <i class="fa-solid fa-user text-4xl"></i>
        </button>
        <hr class="text-white bg-white border-white border-2 w-[80%] opacity-5 rounded-lg" />
        <button name="fyp"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-calendar-days text-4xl"></i>
        </button>

        <button name="create"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-calendar-check text-4xl"></i>
        </button>

        <button name="admin"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-user-tie text-4xl"></i>
        </button>
        <button name="notifications"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-bell text-4xl"></i>
        </button>
        <button name="logout"
          class="w-[4rem] h-[4rem] bg-red-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-door-open text-4xl"></i>
        </button>
      </form>

    </side-bar>
    <?php
    if (isset($_POST["fyp"]) && $_POST[""] == "") {
      echo "<script>location.replace('foryoupage.php')</script>";
    }

    if (isset($_POST["create"]) && $_POST[""] == "") {
      if ($_SESSION["type"] === "Normal") {
        echo "<script>location.replace('organizerrequest.php')</script>";
      } else {
        echo "<script>location.replace('create-event.php')</script>";
      }
    }

    if (isset($_POST["user"]) && $_POST[""] == "") {
      if ($_SESSION["type"] === "Normal") {
        echo "<script>location.replace('profilepage.php')</script>";
      } else {
        echo "<script>location.replace('organizerpage.php')</script>";
      }
    }

    if (isset($_POST["admin"]) && $_POST[""] == "") {
      if ($_SESSION["type"] === "Normal" || $_SESSION["type"] === "Organizer") {
        echo "<script>location.replace('adminrequest.php')</script>";
      } else {
        echo "<script>location.replace('adminpage.php')</script>";
      }
    }

    if (isset($_POST["notifications"]) && $_POST[""] == "") {
      echo "<script>location.replace('notifications.php')</script>";
    }

    if (isset($_POST["logout"]) && $_POST[""] == "") {
      echo logoutSession();
    }
    ?>
    <main-page
      class="bg-gray-700 flex gap-5 justify-center items-start p-10 pr-20 flex-wrap h-full w-full overflow-auto">
      <span class="w-full h-[8rem] text-9xl font-bold text-white">My Account</span>

      <?php
      echo getSessionInfo();
      ?>

      <!-- <div class="w-full h-fit rounded-2xl bg-slate-600 p-16">
        <label for="username" class="text-white">Username</label>

        <input type="text" id="username" value="kasmdkmda" class="w-full h-14 p-5 mb-7 bg-slate-600 border-2 text-white"
          readonly />

        <label for="username" class="text-white">Name</label>

        <input type="text" id="username" value="kasmdkmda" class="w-full h-14 p-5 mb-7 bg-slate-600 border-2 text-white"
          readonly />

        <label for="status" class="text-white">Status</label>

        <input type="text" id="username" value="Normal" class="w-full h-14 p-5 mb-7 bg-slate-600 border-2 text-white"
          readonly />
      </div> -->

      <div class=""></div>
      <!-- START OF POSTS -->
      <div class="container">
        <organizer>
          <span class="w-full h-[8rem] text-xl font-bold text-white">Events Joined (Waiting)</span>
          <div class="w-full h-full flex-col flex gap-2 overflow-scroll">
            <!-- <div class="w-full h-12 bg-white flex items-center justify-between">
                <span class="ml-3 w-fit">Charles Darwin</span>
                <span class="ml-3 w-fit text-blue-600">Date: December 10 2020</span>
                <div class="w-fit h-full flex justify-end items-center">
                  <form method="post">
                    <input type="hidden" value="" name="postId">
                    <button type="submit" name="onWaitingCancel" class="h-full w-16 hover:text-red-600">
                      cancel
                    </button>
                  </form>
                </div>
              </div> -->
            <?php
            echo getWaiting();
            ?>
          </div>

        </organizer>
        <admin>
          <span class="w-full h-[8rem] text-xl font-bold text-white">Events Joined (Ongoing)</span>
          <!-- <div class="w-full h-full flex-col flex gap-2 overflow-scroll">
            <div class="w-full h-12 flex flex-col">
              <div class="w-full h-12 bg-white flex items-center justify-between">
                <span class="ml-3 w-fit">Charles Darwin</span>
                <div class="w-fit h-full flex justify-end items-center">
                  <form method="post">
                    <input type="hidden" value="" name="postId">
                    <button type="submit" name="onGoingCancel" class="h-full w-16 hover:text-red-600">
                      cancel
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div> -->
          <?php
          echo getOngoing();
          ?>
        </admin>
      </div>
      <!-- END OF POSTS -->
    </main-page>
  </div>
</body>

</html>