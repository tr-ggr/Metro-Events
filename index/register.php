<?php include("api.php") ?>

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
  <div class="w-[26rem] h-[34rem] bg-gray-700 rounded-3xl flex flex-col justify-start items-center gap-6 pt-8">
    <span class="text-5xl text-center font-extrabold text-white">REGISTER</span>
    <form method="post" class="text-white w-full p-5">
      <label for="username">Username:</label><br />
      <input type="text" id="username" name="username" class="w-full h-1 text-black p-6 rounded-lg" /><br />
      <label for="password">Password:</label><br />
      <input type="password" id="password" name="password" class="w-full h-1 text-black p-6 rounded-lg" /><br />
      <label for="fullname">Fullname:</label><br />
      <input type="text" id="fullname" name="fullname" class="w-full h-1 text-black p-6 rounded-lg" /><br />

      <button type="submit" name="register" class="w-full bg-blue-500 mt-4 h-10 rounded-lg">
        register
      </button>
    </form>
    <?php
    if (isset($_POST["register"])) {
      echo registerUser();
    }
    ?>
    <div class="w-fit h-fit">
      <span class="text-white">Already registered?</span>
      <a href="login.php" class="text-blue-800">Login here</a>
    </div>
  </div>
</body>

</html>