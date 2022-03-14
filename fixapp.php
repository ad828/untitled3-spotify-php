<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SpotifyStuff</title>
</head>
<body>
<div>
    <div>
        Spotify Song
    </div>
    <form method="post">
       <input type="text" name="song" placeholder="song" autocomplete="off"><br>
        <input type="text" name="artist" placeholder="artist" autocomplete="off"> <br>
        <input type="text" name="genre" placeholder="genre" autocomplete="off"> <br>
        <input type="submit" name="submit" value="SAVE">
        <p>1) Do you Like songs that are acoustic?</p>
        <label>
            <input type="radio" name="acoustic" value="yes"> Yes
        </label>
        <label>
            <input type="radio" name="acoustic" value="no"> No
        </label>
        <p>2) Do you Like songs that are danceable?</p>
        <label>
            <input type="radio" name="dance" value="yes"> Yes
        </label>
        <label>
            <input type="radio" name="dance" value="no"> No
        </label>
        <p>3) Do you Like songs that are long?</p>
        <label>
            <input type="radio" name="long" value="yes"> Yes
        </label>
        <label>
            <input type="radio" name="long" value="no"> No
        </label>
        <p>4) Do you Like songs that are full of energy?</p>
        <label>
            <input type="radio" name="energy" value="yes"> Yes
        </label>
        <label>
            <input type="radio" name="energy" value="no"> No
        </label>
        <p>5) Do you Like songs that are instrumental?</p>
        <label>
            <input type="radio" name="instrumental" value="yes"> Yes
        </label>
        <label>
            <input type="radio" name="instrumental" value="no"> No
        </label>
    </form>
</div>
</body>
</html>


<?php


if (isset($_POST['song'])) {
    $song = "Song:" . $_POST['song'];
    $artist = "Artist:" . $_POST['artist'];
    $genre = "Genre:" . $_POST['genre'];
    echo $song . $artist . $genre;
}


include(__DIR__ . '/src/SpotifyWebAPI.php');
require 'vendor/autoload.php';
/*
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
*/
session_start();
$api = new SpotifyWebAPI\SpotifyWebAPI();

// Fetch the saved access token from somewhere. A session for example.
$api->setAccessToken($_SESSION['access']);

//pretty print for account data to access for php scripting (use when programming)
/*
echo "<h3>" . "Data Dump of Auth Token" . "</h3>";
echo "________________________________________________________________________";
echo "<pre>";
print_r(
    $api->me()
);
echo "</pre>";
*/


//format for getting things from the php array ex: this gets the userid of the authenticated user.
$me = $api->me();
$userid = $me->id;
echo "<h3>" . "The Users ID is " . "$userid" . "</h3>";
$playlists = $api->getUserPlaylists($userid, [
    'limit' => 5
]);
/*
foreach ($playlists->items as $playlist) {
    echo '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';
}
echo "________________________________________________________________________";
echo "<br><br><br><br>";
*/

$playlist = $api->createPlaylist([
    'name' => '20q20songs Inputted 20 Song'
]);
echo "created Playlist Named" . $playlist->name;

$playlistid = $playlist->id;
//format for getting things from the php array ex: this gets the userid of the authenticated user.
echo "<h3>" . "The playlist ID is " . "$playlistid" . "</h3>";

/*echo "Pretty Print of the playlist info:" . "<pre>";
print_r($playlists);
echo "</pre>";
*/

$options = [];
echo "Recommendations for song:";
$play = $api->getRecommendations([
    'limit' => '19', //Starts counting at 0
    'market' => 'ES',
    'seed_artist' => $_POST['artist'],
    'seed_genre' => $_POST['genre'],
    'seed_tracks' => $_POST['song']
]);

/*
echo "<pre>";
print_r($play);
echo "</pre>";
*/
//$adding = $api -> addPlaylistTracks($playlistid, ['3qQVUOHJdgIFWJd0jrG9GE', '3qQVUOHJdgIFWJd0jrG9GE']);
echo "<pre>";
print_r(
    $play
);
echo"</pre>";

foreach ($play as $container) {
    foreach ($container as $object => $value) {
        echo $value->id . "\n";
        $api -> addPlaylistTracks($playlistid, $value->id);
    }
}



print_r("\n\n 20q20songs playlist gen success");
