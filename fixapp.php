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
        <p>1) What's your favorite song?</p>
       <input type="text" name="song" placeholder="song" autocomplete="off"><br>
        <p>2) Who's your favorite artist?</p>
        <input type="text" name="artist" placeholder="artist" autocomplete="off"> <br>
        <p>3) What's your favorite genre?</p>
        <input type="text" name="genre" placeholder="genre" autocomplete="off"> <br>
        <p>4) On a scale of 0 - 1 (use decimals) Do you Like songs that are heavily instrumental?</p>
        <input type="text" name="inst" placeholder="inst" autocomplete="off"><br>
        <p>4) On a scale of 0 - 1 Do you Like songs that are danceable?</p>
        <input type="text" name="dance" placeholder="dance" autocomplete="off"><br>
        <p>5) How long do you like your songs (use milliseconds: 60,000ms = 1minute)</p>
        <input type="text" name="time" placeholder="time" autocomplete="off"><br>
        <input type="submit" name="submit" value="SAVE">
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
        'seed_tracks' => $_POST['song'],
        'target_instrumentalness' => $_POST['inst'],
        'target_danceability' => $_POST['dance'],
        'min_duration_ms' => $_POST['time']
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
    echo "</pre>";

    foreach ($play as $container) {
        foreach ($container as $object => $value) {
            echo $value->id . "\n";
            $api->addPlaylistTracks($playlistid, $value->id);
        }
    }
}
print_r("\n\n 20q20songs playlist gen success");
