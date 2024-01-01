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

function requestJoin()
{
  $data = file_get_contents('../data/users.json');
  $users = json_decode($data, true);

  $post_data = file_get_contents('../data/posts.json');
  $posts = json_decode($post_data, true);


  foreach ($posts as $post) {
    if ($post["postId"] == $_POST['postID']) {
      $postAuthor = $post["author"]["id"];
    }
  }

  foreach ($users as &$user) {
    if ($user["id"] == $postAuthor) {
      $end = end($user["notifications"]);
      $new_Id = (int) $end["postId"] + 1;

      $extra = array(
        "notificationId" => $new_Id,
        'type' => "join",
        'postId' => $_POST['postID'],
        'userId' => $_SESSION['id'],
      );

      foreach ($user["notifications"] as $noti) {
        $extraChecker = $extra;
        $notiChecker = $noti;
        unset($notiChecker["notificationId"]);
        unset($extraChecker["notificationId"]);
        if ($notiChecker === $extraChecker) {
          unset($user);
          echo "<script>alert('Already Applied!')</script>";
          return;
        }
      }
      array_push($user["notifications"], $extra);
    }
  }

  $jsonData = json_encode($users);
  file_put_contents('../data/users.json', $jsonData);

  unset($user);

  echo "<script>alert('Successfully Applied!')</script>";

}

function applyPost($postId, $userId)
{

  $post_data = file_get_contents('../data/posts.json');
  $posts = json_decode($post_data, true);
  $postDetails = null;

  $userIdDetails = getUserIdData($userId);


  foreach ($posts as &$post) {
    if ($post["postId"] == $postId) {
      foreach ($post["attendees"] as $user) {
        if ($user["id"] == $userId) {
          echo "<script>alert('User is already Registered!')</script>";
          unset($post);
          return;
        }
      }

      $userAttendee = $userIdDetails;
      unset($userAttendee["notifications"]);
      unset($userAttendee["events"]);

      array_push($post["attendees"], $userAttendee);


      $postDetails = $post;
      break;
    }
  }

  unset($postDetails["reviews"]);


  unset($post);

  // print_r($postDetails);

  $data = file_get_contents('../data/users.json');
  $id = json_decode($data, true);

  foreach ($id as &$item) {
    if ($item["id"] == $userId) {
      array_push($item["events"], $postDetails);
      break;
    }
  }

  // print_r($id);
  unset($item);

  $postData = json_encode($posts);
  file_put_contents('../data/posts.json', $postData);

  $jsonData = json_encode($id);
  file_put_contents('../data/users.json', $jsonData);

  echo "<script>alert('User has successfully Registered!')</script>";

  $data = file_get_contents('../data/users.json');
  $decode = json_decode($data, true);

  foreach ($decode as &$item) {
    if ($item["id"] == $_SESSION["id"]) {
      $i = 0;
      foreach ($item["notifications"] as $key => $notif) {
        if ($notif["notificationId"] == $_POST["notificationID"]) {
          array_splice($item["notifications"], $i, 1);

          $i = 1;
          foreach ($item["notifications"] as &$notif) {
            $notif["notificationId"] = $i;
            $i++;
          }

          $jsonData = json_encode($decode, JSON_PRETTY_PRINT);
          file_put_contents('../data/users.json', $jsonData);
          unset($item);
          // echo "<script>alert('Successfully Declined!')</script>";
        }
        $i++;
      }
    }
  }
}

function getOngoing()
{
  $data = file_get_contents('../data/users.json');
  $decode = json_decode($data, true);

  $str = '';

  foreach ($decode as $item) {
    if ($_SESSION["id"] == $item["id"]) {
      foreach ($item["events"] as $event) {
        if ($event["status"] === "Waiting")
          continue;

        $str .= '<div class="w-full h-12 flex flex-col">
          <div class="w-full h-12 bg-white flex items-center justify-between">
            <span class="ml-3 w-fit">' . $event["name"] . '</span>
            <div class="w-fit h-full flex justify-end items-center">
            <form method="post">
            <input type="hidden" value="' . $event["postId"] . '" name="postId">
            <button type="submit" name="onGoingCancel" class="h-full w-16 hover:text-red-600">
              cancel
            </button>
            </div>
          </div>
        </div>';

      }


    }




  }
  return $str;

}

function getNotifications()
{
  $data = file_get_contents('../data/users.json');
  $users = json_decode($data, true);



  $str = '';

  foreach ($users as $user) {
    if ($user["id"] == $_SESSION["id"]) {


      foreach ($user["notifications"] as $notif) {
        $postId = $notif["postId"];
        $userId = $notif["userId"];


        $userDetails = getUserIdData($userId);
        $postDetails = getPostIdData($postId);

        if ($notif["type"] === "join") {
          $str .= '<div class="w-full h-12 flex flex-col">
          <div class="w-full h-12 bg-white flex items-center justify-start">
    
            <span class="ml-3 w-1/2"><i class="fa-solid fa-person-circle-question"></i><span class = "text-blue-900 italic underline"> ' . $userDetails["fullname"] . '</span> requested to <span
                class="font-bold"> JOIN </span> <span class = "text-green-900 italic underline"> ' . $postDetails["name"] . '</span></span>
            <form method="post" class="flex gap-5 justify-end items-end w-1/2 h-full mr-3">
            <input type="hidden" value="' . $notif["notificationId"] . '" name="notificationID">
              <input type="hidden" value="' . $postDetails["postId"] . '" name="postID">
              <input type="hidden" value="' . $userDetails["id"] . '" name="userID">
              <button type="submit" name="start" class="h-full w-fit hover:text-blue-600">
                Accept
              </button>
              <button type="submit" name="cancel" class="h-full w-fit hover:text-red-600">
                Decline
              </button>
            </form>
          </div>
    
        </div>';
        }
      }


    }
  }

  unset($userDetails);
  unset($postDetails);
  return $str;
}

function acceptNotification()
{
  applyPost($_POST["postID"], $_POST["userID"]);
  echo "<script>location.reload()</script>";
  // deleteNotification();
}

function deleteNotification()
{
  $data = file_get_contents('../data/users.json');
  $decode = json_decode($data, true);

  foreach ($decode as &$item) {
    if ($item["id"] == $_SESSION["id"]) {
      $i = 0;
      foreach ($item["notifications"] as $notif) {
        if ($notif["notificationId"] == $_POST["notificationID"]) {
          if (count($notif) == 1) {
            $item["notifications"] = [];
          } else {
            unset($item["notifications"][$i]);


            $i = 1;
            foreach ($item["notifications"] as &$notif) {
              $notif["notificationId"] = $i;
              $i++;
            }

          }




          $jsonData = json_encode($decode, JSON_PRETTY_PRINT);
          file_put_contents('../data/users.json', $jsonData);
          unset($item);
          echo "<script>alert('Successfully Declined!')</script>";
        }
        $i++;
      }
    }
  }

  echo "<script>location.reload()</script>";

}
function getWaiting()
{
  $data = file_get_contents('../data/users.json');
  $decode = json_decode($data, true);

  $str = '';

  foreach ($decode as $item) {
    if ($_SESSION["id"] == $item["id"]) {
      foreach ($item["events"] as $event) {
        if ($event["status"] !== "Waiting")
          continue;

        $str .= '
          <div class="w-full h-12 bg-white flex items-center justify-between">
            <span class="ml-3 w-1/3">' . $event["name"] . '</span>
            <span class="ml-3 w-1/3 text-blue-600">Date: ' . $event["date"] . '</span>
            <form method="post" class="w-fit h-full flex justify-end items-center">
            <input type="hidden" value="' . $event["postId"] . '" name="postId">
            <button type="submit" name="onWaitingCancel" class="h-full w-16 hover:text-red-600">
              cancel
            </button>
          </form>
          </div>
        ';

      }


    }


  }
  return $str;
}

function getEventsMade()
{
  $data = file_get_contents('../data/posts.json');
  $decode = json_decode($data, true);

  $str = '';

  foreach ($decode as $post) {
    if ($post["author"]["id"] == $_SESSION['id']) {


      $str .= '<div class="w-full h-12 flex flex-col">
<div class="w-full h-12 bg-white flex items-center justify-between">
  <span class="ml-3 w-1/5">' . $post["name"] . '</span>
  <span class="ml-3 w-1/5 text-blue-600">Date: ' . $post["date"] . '</span>
  <span class="ml-3 w-1/5 text-blue-600">Status: ' . $post["status"] . '</span>
  <span class="text-xl mt-2 w-1/5 flex items-center font-bold text-blue-500"><i
      class="fa-solid fa-person"></i>: ' . count($post["attendees"]) . ' /
      ' . $post["max_people"] . '</span>

  <form method = "post" class = "flex gap-5 justify-end items-end w-1/5 h-full">
  <input type="hidden" value="' . $post["postId"] . '" name="postID">
  <button type="submit" name="start" class="h-full w-fit hover:text-blue-600">
    start event!
  </button>
  <button type="submit" name="cancel" class="h-full w-fit hover:text-red-600">
    cancel event
  </button>
</form>

</div>
</div>';
    }
  }

  return $str;
}

function getUserIdData($num)
{
  $data = file_get_contents('../data/users.json');
  $decode = json_decode($data, true);

  foreach ($decode as &$item) {
    if ($item["id"] == $num) {
      return $item;
    }
  }
}

function getpostIdData($num)
{
  $data = file_get_contents('../data/posts.json');
  $decode = json_decode($data, true);

  foreach ($decode as &$item) {
    if ($item["postId"] == $num) {
      return $item;
    }
  }
}

function getPostList()
{
  $data = file_get_contents('../data/posts.json');
  $decode = json_decode($data, true);
  $str = '';
  foreach ($decode as $item) {
    $str .= '<div class="w-full h-12 flex flex-col">
<div class="w-full h-12 bg-white flex items-center justify-start">
  <div class="h-full w-6 bg-green-700 hover:bg-red-700"></div>
  <span class="ml-3">' . $item["name"] . '</span>
  <div class="w-full h-full flex justify-end items-center">
    <form method="post">
      <input type="hidden" name="postID" value="' . $item["postId"] . '">
      <button name = "delete" class="h-full w-16 hover:text-red-600">
        remove
      </button>
    </form>
  </div>
</div>
</div>';
  }

  return $str;
}

function deletePost()
{
  $data = file_get_contents('../data/posts.json');
  $decode = json_decode($data, true);

  $i = 0;

  foreach ($decode as $post) {
    if ($post["postId"] == $_POST["postID"]) {
      array_splice($decode, $i, 1);

      $i = 1;
      foreach ($decode as &$post) {
        $post["postId"] = $i;
        $i++;
      }


      $postData = json_encode($decode);
      file_put_contents('../data/posts.json', $postData);
      echo "<script>alert('Successfully Removed!')</script>";

      return;
    }
    $i++;
  }
}

function applyOrganizer()
{
  $data = file_get_contents('../data/organizer-requests.json');
  $decode = json_decode($data, true);

  foreach ($decode as $person) {
    if ($person["userId"] == $_SESSION["id"]) {
      echo "<script>alert('Already Applied!')</script>";
      return;
    }
  }


  $extra = array(
    "userId" => $_SESSION["id"]
  );

  array_push($decode, $extra);

  $jsonData = json_encode($decode);
  file_put_contents('../data/organizer-requests.json', $jsonData);
  echo "<script>alert('Succefully Applied!')</script>";
}

function applyAdmin()
{
  $data = file_get_contents('../data/admin-requests.json');
  $decode = json_decode($data, true);

  foreach ($decode as $person) {
    if ($person["userId"] == $_SESSION["id"]) {
      echo "<script>alert('Already Applied!')</script>";
      return;
    }
  }

  $extra = array(
    "userId" => $_SESSION["id"]
  );

  array_push($decode, $extra);

  $jsonData = json_encode($decode);
  file_put_contents('../data/admin-requests.json', $jsonData);
  echo "<script>alert('Succefully Applied!')</script>";
}

function getAdminRequests()
{
  $data = file_get_contents('../data/admin-requests.json');
  $decode = json_decode($data, true);

  $str = '';

  foreach ($decode as $person) {
    $personDetails = getUserIdData($person["userId"]);

    $str .= '<div class="w-full h-12 flex flex-col">
    <div class="w-full h-12 bg-white flex items-center justify-start">
      <div class="h-full w-6 bg-green-700 hover:bg-red-700"></div>
      <span class="ml-3 w-96">' . $personDetails["fullname"] . '</span>
      <form method="post" class="w-full h-full flex justify-end items-center">
        <input type = "hidden" name="userId" value="' . $personDetails["id"] . '">
        <button name="accept" class="h-full w-16 hover:text-blue-600">
          accept
        </button>
        <button name="decline" class="h-full w-16 hover:text-red-600">
          decline
        </button>
      </form>
    </div>
  </div>';
  }

  return $str;
}

function acceptAdminRequest()
{
  $user_data = file_get_contents('../data/users.json');
  $users = json_decode($user_data, true);

  foreach ($users as &$user) {
    if ($user["id"] == $_POST["userId"]) {

      $user["type"] = "Admin";
      break;

    }
  }

  unset($user);
  $postData = json_encode($users);
  file_put_contents('../data/users.json', $postData);
  echo "<script>alert('Successfully Accepted!')</script>";
}

function removeAdminRequest()
{
  $data = file_get_contents('../data/admin-requests.json');
  $decode = json_decode($data, true);

  $i = 0;

  foreach ($decode as $post) {
    if ($post["userId"] == $_POST["userId"]) {
      array_splice($decode, $i, 1);

      $postData = json_encode($decode);
      file_put_contents('../data/admin-requests.json', $postData);

      return;
    }
    $i++;
  }
}


function getOrganizerRequests()
{
  $data = file_get_contents('../data/organizer-requests.json');
  $decode = json_decode($data, true);

  $str = '';

  foreach ($decode as $person) {
    $personDetails = getUserIdData($person["userId"]);

    $str .= '<div class="w-full h-12 flex flex-col">
    <div class="w-full h-12 bg-white flex items-center justify-start">
      <div class="h-full w-6 bg-green-700 hover:bg-red-700"></div>
      <span class="ml-3 w-96">' . $personDetails["fullname"] . '</span>
      <form method="post" class="w-full h-full flex justify-end items-center">
      <input type = "hidden" name="userId" value="' . $personDetails["id"] . '">
        <button name="organizer-accept" class="h-full w-16 hover:text-blue-600">
          accept
        </button>
        <button name="organizer-decline" class="h-full w-16 hover:text-red-600">
          decline
        </button>
      </form>
    </div>
  </div>';
  }

  return $str;
}

function acceptOrganizerRequest()
{
  $user_data = file_get_contents('../data/users.json');
  $users = json_decode($user_data, true);

  foreach ($users as &$user) {
    if ($user["id"] == $_POST["userId"]) {

      $user["type"] = "Organizer";
      break;

    }
  }

  unset($user);
  $postData = json_encode($users);
  file_put_contents('../data/users.json', $postData);
  echo "<script>alert('Successfully Accepted!')</script>";
}

function removeOrganizerRequest()
{
  $data = file_get_contents('../data/organizer-requests.json');
  $decode = json_decode($data, true);

  $i = 0;

  foreach ($decode as $post) {
    if ($post["userId"] == $_POST["userId"]) {
      array_splice($decode, $i, 1);

      $postData = json_encode($decode);
      file_put_contents('../data/organizer-requests.json', $postData);

      return;
    }
    $i++;
  }
}



?>