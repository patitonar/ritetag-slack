<?php
const URL="https://ritetag.com/hashtag-stats/";
$STATUS=[
    0 => "*Underused*",
    1 => "*Overused*",
    2 => "*Long* *Life*",
    3 => "*Hot* *Now*"
];

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';
require_once 'config.php';

$text = trim($_POST['text'],'#');

$client = new \Ritetag\API\Client(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
$body= json_decode($client->hashtagStats($text)->getBody());

$tagStatus=$STATUS[$body->stats->color];
$images=round($body->stats->images*100);
$links=round($body->stats->links*100);
$mentions=round($body->stats->mentions*100);

$reply= '*[* '.$tagStatus.' *]* *#'.$body->stats->tag.'*'
        .'  -  *Tweets* : '.$body->stats->tweets
        .'  -  *Exposure* : '.$body->stats->exposure
        .'  -  *ReTweets* : '.$body->stats->retweets
        .'  -  *Images* : '.$images.'%'
        .'  -  *Links* : '.$links.'%'
        .'  -  *Mentions* : '.$mentions.'%'."\xA"
        .'All values are per hour. '."\xA"
        .'See full stats at '.URL.$text;

echo $reply;
