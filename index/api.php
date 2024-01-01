<?php


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
  $end = end($posts);
  $new_Id = (int) $end["postId"] + 1;

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
    "votes" => 0,
    "location" => $_POST["location"]
  );

  $tempArray = json_decode($data);
  array_push($tempArray, $extra);
  $jsonData = json_encode($tempArray);

  file_put_contents('../data/posts.json', $jsonData);
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
        <div class="w-[5%] min-h-full bg-slate-900 flex flex-col items-center p-2 ">
          <button><i class="fa-solid fa-thumbs-up text-2xl text-white"></i></button>
          <span class="text-2xl text-green-500">' . $post["votes"] . '</span>
          <button><i class="fa-solid fa-thumbs-down text-2xl text-white"></i></button>
        </div>
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
                  </h3>



                  <div class="w-full h-fit flex gap-3 p-4 ">
                    <i class="fa-solid fa-circle-user text-white text-2xl"></i>
                    <div class="flex flex-col w-fit h-fit">
                      <span class="text-white">author | date</span>
                      <span class="text-white">asjndjoasndasd ojasndo jasn djoasn n dasnddn joasndasnjod nasjod njoas
                        dnoj/div>asojnd ioasjd hoias
                        asojdh uaso dhuash sjndjoasndasd ojasndo jasn djoasn n dasnddn joasndasnjod nasjod njoas
                        dnoj/div>asojnd ioasjd hoias
                        asojdh uaso dhuash
                      </span>
                    </div>
                  </div>

                  <div class="w-full h-fit flex gap-3 border-l-4 p-4 border-blue-500">
                    <i class="fa-solid fa-circle-user text-white text-2xl"></i>
                    <div class="flex flex-col w-fit h-fit">
                      <span class="text-white">author | date</span>
                      <span class="text-white">asjndjoasndasd ojasndo jasn djoasn n dasnddn joasndasnjod nasjod njoas
                        dnoj/div>asojnd ioasjd hoias
                        asojdh uaso dhuash sjndjoasndasd ojasndo jasn djoasn n dasnddn joasndasnjod nasjod njoas
                        dnoj/div>asojnd ioasjd hoias
                        asojdh uaso dhuash
                      </span>
                      <form method="post">
                        <button type="submit" class="bg-red-500 mt-2 p-2 rounded-xl text-white">Delete</button>
                      </form>
                    </div>
                  </div>

                  <form>
                    <label for=" chat" class="sr-only">Your message</label>
                    <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                      <button type="button"
                        class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 20 18">
                          <path fill="currentColor"
                            d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z" />
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 1H2a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z" />
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z" />
                        </svg>
                        <span class="sr-only">Upload image</span>
                      </button>
                      <button type="button"
                        class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z" />
                        </svg>
                        <span class="sr-only">Add emoji</span>
                      </button>
                      <textarea id="chat" rows="1"
                        class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Your message..."></textarea>
                      <button type="submit"
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