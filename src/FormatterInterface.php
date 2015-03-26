<?php
/**
 * Phergie plugin for displaying data from tweets
 * (https://github.com/phergie/phergie-irc-plugin-react-twitter)
 *
 * @link https://github.com/phergie/phergie-irc-plugin-react-twitter for the canonical source repository
 * @copyright Copyright (c) 2008-2014 Phergie Development Team (http://phergie.org)
 * @license http://phergie.org/license Simplified BSD License
 * @package Phergie\Irc\Plugin\React\Twitter
 */

namespace Phergie\Irc\Plugin\React\Twitter;

/**
 * Interface for objects used to format data from tweets prior to syndication.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\Twitter
 */
interface FormatterInterface
{
    /**
     * Formats data from an individual tweet for syndication.
     *
     * @param object $tweet Tweet to format
     * @return string Formatted tweet
     */
    public function format(\stdClass $tweet);
}
