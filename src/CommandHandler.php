<?php
namespace Ritetag\API;
/**
 * Handle the command and generate a response
 *
 * @author Gerardo Nardelli <patitonardelli@gmail.com>
 */
class CommandHandler
{
    private $url = "https://ritetag.com/hashtag-stats/";
    private $urlFor = "https://ritetag.com/best-hashtags-for/";
    private $recommendation = [
        0 => "Do not use this hashtag, very few people are following it",
        1 => "Do not use this hashtag, your tweets will disappear in the crowd",
        2 => "Use this hashtag to get seen over time",
        3 => "Use this hashtag to get seen now"
    ];
    private $recommendationFor = [
        0 => "Do not use these hashtags, very few people are following them",
        1 => "Do not use these hashtags, your tweets will disappear in the crowd",
        2 => "Use these hashtags to get seen over time",
        3 => "Use these hashtags to get seen now"
    ];
    private $statusColor = [
        0 => "#e0e0e0",
        1 => "#ff8080",
        2 => "#a0beff",
        3 => "#91c954"
    ];
    private $public = true;

    public function processCommand($command, $text)
    {
        if ($text == "" || $text == "help" )
        {
            return $this->sendHelp();
        }

        switch ($command)
        {
          case HASHTAG_COMMAND:
            return $this->getHashtag($text);
            break;

          case HASHTAGSFOR_COMMAND:
            return $this->hashtagFor($text);
            break;

          default:
            return $this->sendHelp();
            break;
        }
    }

    private function sendHelp()
    {
        $reply["text"] = "You can type `".HASHTAG_COMMAND." test` to receive stats about the hashtag *#test*. \xA"
                       . "You can type `".HASHTAG_COMMAND." football` to receive popular twitter hashtags for *football* topic. \xA"
                       . "You can include `-p` flag if you want a private response. `".HASHTAG_COMMAND." football -p`";

        return  json_encode($reply);
    }

    private  function getHashtag($text)
    {
        $text = $this->checkPrivacy($text);
        $text = str_replace(" ","",rtrim(ltrim(trim($text,'#'))));

        $client = new \Ritetag\API\Client(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
        $body = json_decode($client->hashtagStats($text)->getBody());

        $reply = $this->setHashtagResponse($text,$body);

        return json_encode($reply);
    }

    private  function hashtagFor($text)
    {
        $text = $this->checkPrivacy($text);

        $text = rtrim(ltrim(trim($text,'#')));
        $topic = "Popular Twitter hashtags for ".$text;
        $text = str_replace(" ","",$text);

        $client = new \Ritetag\API\Client(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
        $body= json_decode($client->hashtagStats($text)->getBody());

        $reply = $this->setHashtagForResponse($text,$topic,$body);

        return json_encode($reply);
    }

    private function checkPrivacy($text)
    {
        if (strpos($text,"-p") !== false)
        {
            $text = str_replace("-p","",$text);
            $this->public = false;
        }

        return $text;
    }

    private function setHashtagResponse($text,$body)
    {
        $reply = [];
        $attachments =  array();
        $header = array();

        $attachColor = $this->statusColor[ $body->stats->color ];
        $images = round($body->stats->images*100);
        $links = round($body->stats->links*100);
        $mentions = round($body->stats->mentions*100);
        $recommendation = $this->recommendation[ $body->stats->color ];

        $attTitle = '#'.$body->stats->tag.' - See full stats';
        $attLink = $this->url.$text;

        if ($this->public)
        {
            $reply["response_type"] = "in_channel";
        }

        $header["fields"] = array(array(
            "title" => $recommendation,
            "short" => false
        ));
        $reply["text"] = '';
        $attachments["fields"] = array(
            array(
                "title" => "Tweets per hour",
                "value" => $body->stats->tweets,
                "short" => true
            ),
            array(
                "title" => "%Tweets With Images",
                "value" => $images.'%',
                "short" => true
            ),
            array(
                "title" => "Exposure per hour",
                "value" => $body->stats->exposure,
                "short" => true
            ),
            array(
                "title" => "%Tweets With Links",
                "value" => $links.'%',
                "short" => true
            ),
            array(
                "title" => "Re-tweets per hour",
                "value" => $body->stats->retweets,
                "short" => true
            ),
            array(
                "title" => "%Tweets With Mentions",
                "value" => $mentions.'%',
                "short" => true
            )
        );

        $header["color"] = $attachColor;
        $header["title"] = $attTitle;
        $header["title_link"] = $attLink;
        $header["fallback"]   = $recommendation;
        $attachments["color"] = $attachColor;
        $reply["attachments"] = array($header,$attachments);

        return $reply;
    }

    private function setHashtagForResponse($text,$topic,$body)
    {
        $hashtags[0] = array();
        $hashtags[1] = array();
        $hashtags[2] = array();
        $hashtags[3] = array();
        $reply = [];

        foreach ($body->associatedHashtags as $associatedHashtag){
            array_push($hashtags[$associatedHashtag->color],array(
                "value" => '<'.$this->url.$associatedHashtag->tag.'|#'.$associatedHashtag->tag.'>',
                "short" => false
            ));
        }

        if ($this->public)
        {
            $reply["response_type"] = "in_channel";
        }

        $reply["text"] = '<'.$this->urlFor.$text.'|*'.$topic.'*>';
        $reply["attachments"] = array(
            array(
                "fallback" => $topic,
                "title"  => count($hashtags[3]) > 0 ? $this->recommendationFor[3] : "",
                "color"  => $this->statusColor[3],
                "fields" => $hashtags[3]
            ),
            array(
                "title"  => count($hashtags[2]) > 0 ? $this->recommendationFor[2] : "",
                "color"  => $this->statusColor[2],
                "fields" => $hashtags[2]
            ),
            array(
                "title"  => count($hashtags[1]) > 0 ? $this->recommendationFor[1] : "",
                "color"  => $this->statusColor[1],
                "fields" => $hashtags[1]
            ),
            array(
                "title"  => count($hashtags[0]) > 0 ? $this->recommendationFor[0] : "",
                "color"  => $this->statusColor[0],
                "fields" => $hashtags[0]
            )
        );

        return $reply;
    }
}
