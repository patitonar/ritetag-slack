# RiteTag Slack
##Ritetag slash commands integration for slack

![hashtags](https://cloud.githubusercontent.com/assets/4614574/15795422/c2073bc2-29c7-11e6-81b8-ffe6bb378594.png)

###1. Install via composer

`composer require patitonar/ritetag-slack:~0.1.0`

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

![hashtag](https://cloud.githubusercontent.com/assets/4614574/15795569/33fb456a-29c9-11e6-9774-8d8d17208409.png)
![hashtagsfor](https://cloud.githubusercontent.com/assets/4614574/15795570/341c1b32-29c9-11e6-90d0-3011d2526b2c.png)


##For more information visit

https://ritetag.com

http://docs.ritetag.apiary.io

https://api.slack.com/
