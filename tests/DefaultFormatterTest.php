<?php

namespace Phergie\Irc\Tests\Plugin\React\Twitter;

use Phake;
use Phergie\Irc\Plugin\React\Twitter\DefaultFormatter;

/**
 * Tests for the Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\Twitter
 */
class DefaultFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->formatter = new DefaultFormatter('<@%user.screen_name%> %text% - (%url%)');
    }

    private function getTweet()
    {
        $json = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'tweet.json');
        $tweet = json_decode($json);
        return $tweet;
    }

    public function testWhenPlaceIsNull()
    {
        $tweet = $this->getTweet();
        $this->assertNull($tweet->place);
        $this->assertEquals("<@twitterapi> Along with our new #Twitterbird, we've also updated our Display Guidelines: https://t.co/Ed4omjYs  ^JC - (https://twitter.com/twitterapi/status/210462857140252672)", $this->formatter->format($tweet));
    }
}
