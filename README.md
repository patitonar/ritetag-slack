# RiteTag Slack
##Ritetag slash commands integration for slack

![hashtags](https://cloud.githubusercontent.com/assets/4614574/15795422/c2073bc2-29c7-11e6-81b8-ffe6bb378594.png)

###1. Install via composer

`composer install`

###2. Config RiteTag

  Edit config.php with your credentials of RiteTag https://ritetag.com/developer/dashboard

  ```
  define('CONSUMER_KEY', '????')
  define('CONSUMER_SECRET', '????')
  define('OAUTH_TOKEN', "????")
  define('OAUTH_TOKEN_SECRET',"????")
  ```

###3. Config Slash Command On Slack

  Use the same name for the commands defined on config.php

 ```
  define('HASHTAG_COMMAND',"/hashtag");
  define('HASHTAGSFOR_COMMAND',"/hashtagsfor");
  ```

![slack](https://cloud.githubusercontent.com/assets/4614574/12128827/953a8de2-b3de-11e5-9dca-98ca73b4be00.png)

##For more information visit

https://ritetag.com

http://docs.ritetag.apiary.io

https://api.slack.com/
