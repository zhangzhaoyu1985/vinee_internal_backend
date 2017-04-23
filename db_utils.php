<?php

function db_connect() {
  // Always connect to china master
  $servername = "54.223.152.54";
  $username = "root";
  $password = "TagTalk78388!";
  $dbname = "wineTage1";

  // Create connection
  $conn = new \mysqli($servername, $username, $password, $dbname);
  $conn->set_charset("utf8");
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

function get_user_name_from_id($conn, $user_id) {
  $sql = "SELECT user_name FROM user_account_info WHERE user_id = ".$user_id;
  $sql_result = $conn->query($sql);
  if ($sql_result->num_rows != 1) {
    return "";
  } else {
    $row = $sql_result->fetch_assoc();
    return $row['user_name'];
  }
}

function get_wine_pic_url_from_id($conn, $wine_id) {
  $sql = "SELECT wine_pic_url FROM wine_basic_info_english WHERE wine_id = ".$wine_id;
  $sql_result = $conn->query($sql);
  if ($sql_result->num_rows != 1) {
    return "";
  } else {
    $row = $sql_result->fetch_assoc();
    return $row['wine_pic_url'];
  }
}

function create_follow_relation($user, $user_to_follow) {
  $conn = db_connect();
  $sql = sprintf("SELECT * FROM user_following_relation WHERE user_id = %d AND follower_id = %d", $user_to_follow, $user);
  $sql_result = $conn->query($sql);
  if ($sql_result->num_rows == 1) {
    print("already existing");
    return;
  } else {
    $sql = sprintf("INSERT INTO user_following_relation (user_id, follower_id) VALUES (%d, %d)", $user_to_follow, $user);
    print($sql);
    $sql_result = $conn->query($sql);
    return;
  }
}

// has user1 already followed user2 ??
function has_followed($user1, $user2) {
  $conn = db_connect();
  $sql = sprintf("SELECT * FROM user_following_relation WHERE user_id = %d AND follower_id = %d", $user2, $user1);
  $sql_result = $conn->query($sql);
  if ($sql_result->num_rows == 1) {
    return true;
  } else {
    return false;
  }
}
