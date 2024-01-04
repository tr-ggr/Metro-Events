<?php
include("sessionAPI.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="main.css" />
</head>

<body class="bg-gray-800 w-screen h-screen flex justify-center items-center">
  <div class="w-[24rem] h-[32rem] bg-gray-700 rounded-3xl flex flex-col justify-start items-center gap-6 pt-9">
    <span class="text-7xl text-center font-extrabold text-white">METRO EVENTS</span>
    <form method="post" class="text-white w-full p-5">
      <label for="username">Username:</label><br />
      <input type="text" id="username" name="username" class="w-full h-1 text-black p-6 rounded-lg" /><br />

      <label for="password">Password:</label><br />
      <input type="password" id="password" name="password" class="w-full h-1 text-black p-6 rounded-lg" /><br />

      <button type="submit" name="login" class="w-full bg-blue-500 mt-4 h-10 rounded-lg">
        login
      </button>
    </form>
    <?php
    if (isset($_POST["login"])) {
      getSession();
    }

    ?>
    <div class="mt-16 w-fit h-fit">
      <span class="text-white">Not yet registered?</span>
      <a href="register.php" class="text-blue-800">Register here</a>
    </div>
  </div>
</body>

</html>