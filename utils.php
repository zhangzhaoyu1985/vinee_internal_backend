<?php
function getPriceString($priceInCents, $currencyIndex) {
  // currencyId => currencyName string
  $currencyMap = array(
                  1 => "$",
                  );
  $result = "";
  $price = (float)$priceInCents/100;
  $result = $result.$price;
  if (in_array($currencyIndex, $currencyMap)) {
    $unit = $currencyMap[$currencyIndex];
    $result = "$unit ".$result;
  } else {
    $result = "$ ".$result;
  }
  return $result;
}

function secs_to_h($secs)
{
        $units = array(
                "day"    =>   24*3600,
                "hour"   =>      3600,
                "minute" =>        60,
        );
        $s = "";
        foreach ( $units as $name => $divisor ) {
                if ( $quot = intval($secs / $divisor) ) {
                        $s .= "$quot $name";
                        $s .= (abs($quot) > 1 ? "s" : "");
                        break;
                }
        }
        if(empty($s)) {
          $s = "just now";
          return;
        }

        return $s." ago";
}

function sendEmailAboutPassword($email_address, $user_name, $pw, $user_id) {
  $to  = $email_address;
  print("1");
  // subject
  $subject = 'Your account information for WineMate';
  // message
  $message = sprintf('
  <html>
    <head>
      <title>Your account information for WineMate</title>
    </head>
    <body>
      <p>Here is your account information for WineMate!</p>
      <table>
        <tr>
          <th>User Name </th><th> Email </th><th> Password </th><th> User Id </th>
        </tr>
        <tr>
          <td>%s</td><td>%s</td><td>%s</td><td>%d</td>
        </tr>
      </table>
    </body>
  </html>', $user_name, $email_address, $pw, $user_id);

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
#$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
$headers .= 'From: WineMate Administration Group' . "\r\n";
$headers .= 'Cc: yliu182@gmail.com' . "\r\n";
#$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
// Mail it
mail($to, $subject, $message, $headers);
//mail('yliu182@gmail.com', 'Test Subject', 'Test message');

}

function mergeScoreAndReview($conn, $score_array, $review_array) {
  // merge sort, ordered by time
  $p1 = 0;
  $p2 = 0;
  $result = array();
  while($p1 < count($score_array) && $p2 < count($review_array)) {
    $score = $score_array[$p1];
    $review = $review_array[$p2];
    $data = new \wineMateThrift\WineReviewAndRatingData;
    if ($score['timeStamp'] > $review['timeStamp']) {
      $sql = "SELECT user_name, sex FROM user_account_info WHERE user_id = ".$score['reviewerId'];
      $data->userId = $score['reviewerId'];
      $sql = $conn->query($sql);
      if($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        $data->reviewerName = $row['user_name'];
        $data->sex = ($row['sex'] == 'm' ? \wineMateThrift\ReviewerSex::MALE : \wineMateThrift\ReviewerSex::FEMALE);
        $data->reviewContent = '';
        $data->rate = $score['score'];
        $elapsedSeconds = time() - $score['timeStamp'];
        if ($elapsedSeconds > 0) {
          $data->timeElapsed = secs_to_h($elapsedSeconds);
        }
      }
      $result[] = $data;
      $p1 ++;
    } else if ($score['timeStamp'] < $review['timeStamp']) {
      $sql = "SELECT user_name, sex FROM user_account_info WHERE user_id = ".$review['reviewerId'];
      $data->userId = $review['reviewerId'];
      $sql = $conn->query($sql);
      if($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        $data->reviewerName = $row['user_name'];
        $data->sex = ($row['sex'] == 'm' ? \wineMateThrift\ReviewerSex::MALE : \wineMateThrift\ReviewerSex::FEMALE);
        $data->reviewContent = $review['reviewContent'];
        $data->rate = 0.0;
        $elapsedSeconds = time() - $review['timeStamp'];
        if ($elapsedSeconds > 0) {
          $data->timeElapsed = secs_to_h($elapsedSeconds);
        }
      }
      $result[] = $data;
      $p2 ++;
    } else {
      if ($score['reviewerId'] == $review['reviewerId']) {
        $sql = "SELECT user_name, sex FROM user_account_info WHERE user_id = ".$review['reviewerId'];
        $data->userId = $review['reviewerId'];
        $sql = $conn->query($sql);
        if($sql->num_rows > 0) {
          $row = $sql->fetch_assoc();
          $data->reviewerName = $row['user_name'];
          $data->sex = ($row['sex'] == 'm' ? \wineMateThrift\ReviewerSex::MALE : \wineMateThrift\ReviewerSex::FEMALE);
          $data->reviewContent = $review['reviewContent'];
          $data->rate = $score['score'];
          $elapsedSeconds = time() - $review['timeStamp'];
          if ($elapsedSeconds > 0) {
            $data->timeElapsed = secs_to_h($elapsedSeconds);
          }
        }
        $result[] = $data;
        $p1 ++;
        $p2 ++;
      } else {
        $sql = "SELECT user_name, sex FROM user_account_info WHERE user_id = ".$review['reviewerId'];
        $data->userId = $review['reviewerId'];
        $sql = $conn->query($sql);
        if($sql->num_rows > 0) {
          $row = $sql->fetch_assoc();
          $data->reviewerName = $row['user_name'];
          $data->sex = ($row['sex'] == 'm' ? \wineMateThrift\ReviewerSex::MALE : \wineMateThrift\ReviewerSex::FEMALE);
          $data->reviewContent = $review['reviewContent'];
          $data->rate = 0.0;
          $elapsedSeconds = time() - $review['timeStamp'];
          if ($elapsedSeconds > 0) {
            $data->timeElapsed = secs_to_h($elapsedSeconds);
          }
        }
        $result[] = $data;
        $p2 ++;
      }
    }
  }

  if($p1 == count($score_array)) {
    while ($p2 < count($review_array)) {
      $data = new \wineMateThrift\WineReviewAndRatingData;
      $review = $review_array[$p2];
      $sql = "SELECT user_name, sex FROM user_account_info WHERE user_id = ".$review['reviewerId'];
      $data->userId = $review['reviewerId'];
      $sql = $conn->query($sql);
      if($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        $data->reviewerName = $row['user_name'];
        $data->sex = ($row['sex'] == 'm' ? \wineMateThrift\ReviewerSex::MALE : \wineMateThrift\ReviewerSex::FEMALE);
        $data->reviewContent = $review['reviewContent'];
        $data->rate = 0.0;
        $elapsedSeconds = time() - $review['timeStamp'];
        if ($elapsedSeconds > 0) {
          $data->timeElapsed = secs_to_h($elapsedSeconds);
        }
      }
      $result[] = $data;
      $p2 ++;
    }
  } else {
    while ($p1 < count($score_array)) {
      $score = $score_array[$p1];
      $data = new \wineMateThrift\WineReviewAndRatingData;
      $sql = "SELECT user_name, sex FROM user_account_info WHERE user_id = ".$score['reviewerId'];
      $data->userId = $score['reviewerId'];
      $sql = $conn->query($sql);
      if($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        $data->reviewerName = $row['user_name'];
        $data->sex = ($row['sex'] == 'm' ? \wineMateThrift\ReviewerSex::MALE : \wineMateThrift\ReviewerSex::FEMALE);
        $data->reviewContent = '';
        $data->rate = $score['score'];
        $elapsedSeconds = time() - $score['timeStamp'];
        if ($elapsedSeconds > 0) {
          $data->timeElapsed = secs_to_h($elapsedSeconds);
        }
      }
      $result[] = $data;
      $p1 ++;
    }
  }
  return $result;
}

function insert_system_posts($response) {
  $data = new \wineMateThrift\NewsFeedData;
  $data->feedType = \wineMateThrift\FeedType::SYSTEMFEED;
  $data->authorName = "WineMate";
  $data->feedTitle = "System Suggestion";
  $data->contentTitle = "5 Wines from the World's Smallest Wine Country";
  $data->contentAbstract = "Belgium is known throughout the world for its chocolate and beer, but did you know wine grapes have grown on Belgian soil since the early Middle Ages? ";
  $data->date = "2016-08-09";
  $data->picUrl = "http://54.67.111.151/pics/newsfeed_contents/five_wines_from_the_world_smallest_wine_country/5_wines_from_the_world_smallest_wine_country.png";
  $data->contentUrl = "http://54.67.111.151/pics/newsfeed_contents/five_wines_from_the_world_smallest_wine_country/five_wines_from_the_world_smallest_wine_country.html";
  array_push($response->response, $data);

  $data1 = new \wineMateThrift\NewsFeedData;
  $data1->feedType = \wineMateThrift\FeedType::SYSTEMFEED;
  $data1->authorName = "WineMate";
  $data1->feedTitle = "System Suggestion";
  $data1->contentTitle = "7 Ways to Keep Wine Cool This Summer";
  $data1->contentAbstract = "For wine lovers, there's one major challenge every summer. Hot weather make it hard to achieve the most enjoyable serving temperature for wine.";
  $data1->date = "2016-08-09";
  $data1->picUrl = "http://54.67.111.151/pics/newsfeed_contents/seven_ways_to_keep_wine_cool/7_ways_to_keep_wine_cool_this_summer.png";
  $data1->contentUrl = "http://54.67.111.151/pics/newsfeed_contents/seven_ways_to_keep_wine_cool/seven_ways_to_keep_wine_cool_this_summer.html";
  array_push($response->response, $data1);

  $data2 = new \wineMateThrift\NewsFeedData;
  $data2->feedType = \wineMateThrift\FeedType::SYSTEMFEED;
  $data2->authorName = "WineMate";
  $data2->feedTitle = "System Suggestion";
  $data2->contentTitle = "The Best Wine For Hot Dogs and Sausages";
  $data2->contentAbstract = "When summer BBQs and baseball games start filling up the calendar, it's safe to say hot dogs will be on the menu, and wine can be there too!";
  $data2->date = "2016-08-08";
  $data2->picUrl = "http://54.67.111.151/pics/newsfeed_contents/the_best_wine_this_summer/the_best_wine_this_summer.png";
  $data2->contentUrl = "http://54.67.111.151/pics/newsfeed_contents/the_best_wine_this_summer/the_best_wine_this_summer.html";
  array_push($response->response, $data2);
}
