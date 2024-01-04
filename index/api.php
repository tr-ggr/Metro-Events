<?php
include("sessionAPI.php");

// users JSON
$usersJSON = '../data/users.json';

// posts JSON
$postsJSON = '../data/posts.json';

// function get users from json
function registerUser()
{
  $data = file_get_contents('../data/users.json');
  $id = json_decode($data, true);
  $end = end($id);
  $new_Id = (int) $end["id"] + 1;
  $extra = array(
    'id' => $new_Id,
    // 'id' => 1,
    'username' => $_POST['username'],
    'password' => $_POST["password"],
    'fullname' => $_POST["fullname"],
    'type' => "Normal",
    "notifications" => [],
    "events" => [],
    "status" => "Waiting"
  );
  $tempArray = json_decode($data);
  array_push($tempArray, $extra);
  $jsonData = json_encode($tempArray);

  file_put_contents('../data/users.json', $jsonData);
}

function createPosts()
{
  $data = file_get_contents('../data/posts.json');
  $posts = json_decode($data, true);
  if (count($posts) == 0) {
    $new_Id = 1;
  } else {
    $end = end($posts);
    $new_Id = (int) $end["postId"] + 1;
  }

  $author = $_SESSION;

  unset($author["notifications"]);
  unset($author["events"]);

  $extra = array(
    'postId' => $new_Id,
    // 'postId' => 1,
    'author' => $author,
    'name' => $_POST['name'],
    'description' => $_POST["description"],
    'date' => $_POST["date"],
    'attendees' => [],
    'max_people' => $_POST["people"],
    "status" => "Waiting",
    "reviews" => [],
    "votes" => [],
    "location" => $_POST["location"]
  );

  $tempArray = json_decode($data);
  array_push($tempArray, $extra);
  $jsonData = json_encode($tempArray);

  file_put_contents('../data/posts.json', $jsonData);
}



function getReplies($postId)
{
  $posts_data = file_get_contents('../data/posts.json');
  $posts = json_decode($posts_data, true);

  $str = '';

  foreach ($posts as $post) {
    if ($post['postId'] == $postId) {
      $i = 0;

      foreach ($post["reviews"] as $reviews) {
        $userDetails = getUserIdData($reviews["userId"]);
        if ($reviews["userId"] == $_SESSION["id"]) {
          $str .= '<div class="w-full h-fit flex gap-3 border-l-4 p-4 border-blue-500">
          <i class="fa-solid fa-circle-user text-white text-2xl"></i>
          <div class="flex flex-col w-fit h-fit">
            <span class="text-white">' . $userDetails["fullname"] . ' | ' . $reviews["date"]["date"] . '</span>
            <span class="text-white">' . $reviews["message"] . '
            </span>
            <form method="post">
            <input type = "hidden" value = "' . $postId . '" name = "postId">

              <input type = "hidden" value = "' . $i . '" name = "reviewId">
              <button type="submit" name = "delete" class="bg-red-500 mt-2 p-2 rounded-xl text-white">Delete</button>
            </form>
          </div>
        </div>';
        } else {
          $str .= '
          <div class="w-full h-fit flex gap-3 p-4 ">
            <i class="fa-solid fa-circle-user text-white text-2xl"></i>
            <div class="flex flex-col w-fit h-fit">
              <span class="text-white">' . $userDetails["fullname"] . ' | ' . $reviews["date"]["date"] . '</span>
              <span class="text-white">' . $reviews["message"] . '
              </span>
            </div>
          </div>';
        }

        $i++;
      }
    }
  }
  return $str;
}
function countVotes($postId)
{
  $posts_data = file_get_contents('../data/posts.json');
  $posts = json_decode($posts_data, true);

  $sum = 0;


  foreach ($posts as &$post) {
    if ($post['postId'] == $postId) {
      foreach ($post["votes"] as &$review) {
        if ($review["type"] == "up") {
          $sum++;
        }

        if ($review["type"] == "down") {
          $sum--;
        }
      }
    }
  }

  return $sum;
}

function getPosts()
{
  $data = file_get_contents('../data/posts.json');
  $posts = json_decode($data, true);

  // print_r($posts);

  $str = '';

  foreach ($posts as $post) {
    if ($_SESSION['id'] == $post['author']["id"] || $post['status'] !== "Waiting")
      continue;
    $str .= '<div class="w-[49%] min-h-96 max-h-fit bg-slate-300 flex gap-7 ">
        <form method = "post" class="w-[5%] min-h-full bg-slate-900 flex flex-col items-center p-2 ">
        <input type = "hidden" value = "' . $post["postId"] . '" name = "postId">
          <button type = "submit" name = "upvote"><i class="fa-solid fa-thumbs-up text-2xl text-white"></i></button>
          <span class="text-2xl text-green-500">' . countVotes($post["postId"]) . '</span>
          <button type = "submit" name = "downvote"><i class="fa-solid fa-thumbs-down text-2xl text-white"></i></button>
          </form>

        <div class="flex flex-col w-[95%] min-h-full p-2 justify-between gap-5">
          <div class="flex flex-col w-full h-fit">
            <span class="text-7xl font-bold mt-2">' . $post["name"] . '</span><br>
            <span class="text-xl font-bold mt-2">Author: ' . $post["author"]["fullname"] . '</span><br>

            <span class="text-xl mt-2 font-bold text-blue-500">Event Date: ' . $post["date"] . '</span><br>
            <span class="text-xl mt-2 font-bold text-blue-500"><i class="fa-solid fa-person"></i> ' . count($post["attendees"]) . ' / ' . $post["max_people"] . '</span>
          </div>
          <span class="text-xl mt-2 w-full inline-block h-fit">' . $post["description"] . '</span>
          <button data-modal-target="' . $post["postId"] . '-modal" data-modal-toggle="' . $post["postId"] . '-modal"
            class="block w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            View Details
          </button>

          <div id="' . $post["postId"] . '-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
              <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                  ' . $post["name"] . '
                  </h3>
                  <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="' . $post["postId"] . '-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                      viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                  <span class="text-xl mt-2 font-bold text-white">Author: ' . $post["author"]["fullname"] . '</span><br>
                  <span class="text-xl mt-2 font-bold text-blue-500">Event Date: ' . $post["date"] . '</span><br>
                  <span class="text-xl mt-2 font-bold text-blue-500">CAPACITY: <i class="fa-solid fa-person"></i> ' . count($post["attendees"]) . ' / ' . $post["max_people"] . '</span>
                  <span class="text-xl mt-2 w-full inline-block h-fit text-white">' . $post["description"] . '</span>
                  <form method="post">
                    <input type="hidden" name="postID" value=' . $post["postId"] . '>
                    <button type="submit" name="register" class="bg-blue-500 w-full p-2 rounded-xl text-white">Register
                      Now!</button>
                  </form>

                </div>
                <div class="flex gap-3 flex-col p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Reviews
                  </h3>' . getReplies($post["postId"]) . '<form method = "post">
                  <input type="hidden" name="postID" value=' . $post["postId"] . '>
                    <label for=" chat" class="sr-only">Your message</label>
                    <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">

                      <textarea id="chat" rows="1" name="message"
                        class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Your message..."></textarea>
                      <button type="submit" name = "send"
                        class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600">
                        <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" aria-hidden="true"
                          xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                          <path
                            d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z" />
                        </svg>
                        <span class="sr-only">Send message</span>
                      </button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>';
  }

  return $str;
}

function getLoginData()
{
  $loggedin = $_GET["username"];
  $data = file_get_contents('../data/users.json');
  $decode = json_decode($data, true);

  foreach ($decode as $item) {
    if ($item["username"] == $loggedin) {
      return $item;
    }
  }

  return [];
}

function getUsersData()
{
  global $usersJSON;
  if (!file_exists($usersJSON)) {
    echo 1;
    return [];
  }

  $data = file_get_contents($usersJSON);
  return json_decode($data, true);
}

// function get posts from json
function getPostsData()
{
  global $postsJSON;
  if (!file_exists($postsJSON)) {
    echo 1;
    return [];
  }

  $data = file_get_contents($postsJSON);
  return json_decode($data, true);
}


?>