<?php
session_start();

function getSession()
{
    $data = file_get_contents('../data/users.json');
    $decode = json_decode($data, true);


    foreach ($decode as $item) {
        if ($item["username"] == $_POST["username"]) {
            if ($item["password"] == $_POST["password"]) {

                $_SESSION = $item;
                echo "<script>location.replace('foryoupage.php')</script>";
                return;
            }

        }
    }

    echo "<script>alert('Not Found!')</script>";
}

function getSessionInfo()
{
    $str = '<div class="w-full h-fit rounded-2xl bg-slate-600 p-16">
    <label for="username" class="text-white">Username</label>

    <input type="text" id="username" value="' . $_SESSION["username"] . '" class="w-full h-14 p-5 mb-7 bg-slate-600 border-2 text-white"
      readonly />

    <label for="username" class="text-white">Name</label>

    <input type="text" id="username" value="' . $_SESSION["fullname"] . '" class="w-full h-14 p-5 mb-7 bg-slate-600 border-2 text-white"
      readonly />

    <label for="status" class="text-white">Status</label>

    <input type="text" id="username" value="' . $_SESSION["type"] . '" class="w-full h-14 p-5 mb-7 bg-slate-600 border-2 text-white"
      readonly />
  </div>';

    return $str;
}

function logoutSession()
{
    session_destroy();
    echo "<script>location.replace('login.php')</script>";
}
?>