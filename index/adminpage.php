<?php
include("sessionAPI.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="main.css" />
  <link rel="stylesheet" href="fypgrid.css" />
  <link rel="stylesheet" href="admingrid.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="main-screen bg-gray-700 w-screen h-screen">
    <side-bar class="bg-gray-900">
      <form method="post" class="bg-gray-900 flex flex-col justify-start items-center pt-5 gap-3">
        <button disabled name="admin"
          class="w-[4rem] h-[4rem] bg-cyan-500 rounded-full flex justify-center items-center hover:cursor-default">
          <i class="fa-solid fa-user-tie text-4xl"></i>
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
        <button name="user"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-user text-4xl"></i>
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
      if ($_SESSION["type"] === "Normal") {
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
      <span class="w-full h-[8rem] text-9xl font-bold text-white">Admin panel</span>
      <!-- START OF POSTS -->
      <div class="container">
        <events class="w-full h-full">
          <span class="w-full h-[8rem] text-xl font-bold text-white">List of events</span>
          <div class="w-full h-full flex-col flex gap-2 overflow-scroll">
            <div class="w-full h-12 flex flex-col">
              <div class="w-full h-12 bg-white flex items-center justify-start">
                <div class="h-full w-6 bg-green-700 hover:bg-red-700"></div>
                <span class="ml-3">Christmas Party</span>
                <div class="w-full h-full flex justify-end items-center">
                  <button class="h-full w-16 hover:text-red-600">
                    remove
                  </button>
                </div>
              </div>
            </div>
          </div>
        </events>
        <organizer>
          <span class="w-full h-[8rem] text-xl font-bold text-white">Admin requests</span>
          <div class="w-full h-full flex-col flex gap-2 overflow-scroll">
            <div class="w-full h-12 flex flex-col">
              <div class="w-full h-12 bg-white flex items-center justify-start">
                <div class="h-full w-6 bg-green-700 hover:bg-red-700"></div>
                <span class="ml-3 w-96">Charles Darwin</span>
                <div class="w-full h-full flex justify-end items-center">
                  <button class="h-full w-16 hover:text-blue-600">
                    accept
                  </button>
                  <button class="h-full w-16 hover:text-red-600">
                    decline
                  </button>
                </div>
              </div>
            </div>
          </div>
        </organizer>
        <admin>
          <span class="w-full h-[8rem] text-xl font-bold text-white">Organizer requests</span>
          <div class="w-full h-full flex-col flex gap-2 overflow-scroll">
            <div class="w-full h-12 flex flex-col">
              <div class="w-full h-12 bg-white flex items-center justify-start">
                <div class="h-full w-6 bg-green-700 hover:bg-red-700"></div>
                <span class="ml-3 w-96">Charles Darwin</span>
                <div class="w-full h-full flex justify-end items-center">
                  <button class="h-full w-16 hover:text-blue-600">
                    accept
                  </button>
                  <button class="h-full w-16 hover:text-red-600">
                    decline
                  </button>
                </div>
              </div>
            </div>
          </div>
        </admin>
      </div>
      <!-- END OF POSTS -->
    </main-page>
  </div>
</body>

</html>