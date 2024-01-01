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
        "events" => []
    );
    $tempArray = json_decode($data);
    array_push($tempArray, $extra);
    $jsonData = json_encode($tempArray);

    file_put_contents('../data/users.json', $jsonData);
}

function getPosts()
{
    $data = file_get_contents('../data/posts.json');
    $posts = json_decode($data, true);

    foreach ($posts as $post) {
        $str = `<div
        class="w-[49%] h-[32rem] bg-white flex flex-col justify-start items-center rounded-3xl"
      >
        <div
          class="w-full h-[50%] bg-gradient-to-t bg-cyan-600 rounded-t-3xl"
        ></div>
        <div class="p-5 w-full h-fit">
          <span class="text-xl font-bold mt-2">EVENT NAME</span><br />
          <span class="text-xl mt-2 font-bold text-blue-500"
            >DECEMBER 16, 2023</span
          >
          <span class="text-xl mt-2 w-full inline-block h-fit"
            >event description event description event description event
            description event description event description</span
          >
        </div>
        <button class="w-full h-16 bg-cyan-500">Register now!</button>
      </div>`;
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