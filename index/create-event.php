<?php
include("sessionAPI.php");
include("api.php"); ?>
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
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
</head>

<body>
  <div class="main-screen bg-gray-700 w-screen h-screen">
    <side-bar class="bg-gray-900">
      <form method="post" class="bg-gray-900 flex flex-col justify-start items-center pt-5 gap-3">
        <button disabled name="create"
          class="w-[4rem] h-[4rem]  bg-cyan-500 rounded-full flex justify-center items-center hover:cursor-default">
          <i class="fa-solid fa-calendar-check text-4xl"></i>
        </button>
        <hr class="text-white bg-white border-white border-2 w-[80%] opacity-5 rounded-lg" />
        <button name="fyp"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-calendar-days text-4xl"></i>
        </button>

        <button name="user"
          class="w-[4rem] h-[4rem] bg-cyan-900 rounded-full flex justify-center items-center hover:bg-white hover:cursor-pointer">
          <i class="fa-solid fa-user text-4xl"></i>
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
      <span class="w-full h-[8rem] text-9xl font-bold text-white">Create Event</span>

      <div class="w-full h-fit rounded-2xl bg-slate-600 p-16">
        <form method="post">
          <label for="name" class="text-white">Event Name</label>

          <input type="text" id="name" name="name"
            class="w-full font-bold h-14 p-5 mb-7 bg-slate-600 border-2 text-white" />

          <label for="description" name="location" class="text-white">Location</label>

          <input type="text" id="location" name="location"
            class="w-full font-bold h-14 p-5 mb-7 bg-slate-600 border-2 text-white" />

          <label for="description" name="description" class="text-white">Event Description</label>

          <textarea type="text" id="description" name="description"
            class="w-full h-28 p-5 mb-7 bg-slate-600 border-2 text-white"></textarea>

          <label for="number" class="text-white">Maximum Attendees</label>

          <input type="number" id="people" name="people"
            class="w-full font-bold h-14 p-5 mb-7 bg-slate-600 border-2 text-white" />

          <label for="date" class="text-white">Date</label>

          <input type="date" id="date" name="date"
            class="w-full font-bold h-14 p-5 mb-7 bg-slate-600 border-2 text-white" />

          <button name="post" type="submit" class="bg-cyan-500 rounded-2xl w-full h-14">
            <i class="fa-solid fa-plus"></i>
            Create Event
          </button>
        </form>

        <?php
        if (isset($_POST["post"])) {
          // echo '<script>alert(1)</script>';
          if (($_POST['name']) && $_POST['description']) {
            echo createPosts();
          } else {
            echo '<script>alert("Please input the fields!")</script>';
          }

        }
        ?>

      </div>
    </main-page>
  </div>
</body>

</html>