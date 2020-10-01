# Warbot for Twitter

![Version](https://img.shields.io/badge/version-1.0-blue.svg)
![GitHub](https://img.shields.io/github/license/driade/warbot)
[![GitHub issues](https://img.shields.io/github/issues/driade/warbot)](https://github.com/driade/warbot/issues)
[![GitHub stars](https://img.shields.io/github/stars/driade/warbot)](https://github.com/driade/warbot/stargazers)

Twitter bot that randomly selects two users in a list and make them fight, provided a way from another list, posting the result on Twitter with an image.

## Requirements

The bot works with php 7.3, nothing more. Mysql support, provided in the first version, has been removed to make it even easier to install.

### Subjects

You can edit the list of participants in the file subjects.csv.

### Way of diying

You can edit the list of participants in the file ways.csv.

### Message

You can set your own message modifying the function createMessage in WarBot.php.

### Testing

You can test all the possible scenarios running

```
vendor/bin/phpunit 
```

Not a proper test, but it works.

PHPStan support is provided too running

```
composer test
```

## Installation

Upload the code, rename env.example to .env and configure it with the Twitter credentials of the bot.

Then, make sure to edit your crontab file so the runs each X hours (2 in this case)

```
0 */2 * * * cd /PATH_TO_THE_CODE; php bin/tweet.php
```

## Notes

Originally used on https://twitter.com/cosmerewars