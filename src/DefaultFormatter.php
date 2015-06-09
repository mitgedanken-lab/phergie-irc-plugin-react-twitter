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

use Carbon\Carbon;

/**
 * Default tweet formatter implementation.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\Twitter
 */
class DefaultFormatter implements FormatterInterface
{
    /**
     * Pattern used to format tweets
     *
     * @param string
     */
    protected $pattern;

    /**
     * Pattern used to format date values within tweets
     *
     * @var string
     */
    protected $datePattern;

    /**
     * Default pattern used to format tweets
     *
     * @param string
     */
    protected $defaultPattern = '<@%user.screen_name%> %text% - %created_at.relative% (%url%)';

    /**
     * Accepts format pattern.
     *
     * @param string $pattern
     * @param string $datePattern
     */
    public function __construct($pattern = null, $datePattern = null)
    {
        $this->pattern = $pattern ? $pattern : $this->defaultPattern;
        $this->datePattern = $datePattern ? $datePattern : \DateTime::ISO8601;
    }

    /**
     * Implements FormatterInterface->format().
     *
     * @param object $tweet Tweet to format
     * @return string Formatted tweet
     */
    public function format(\stdClass $tweet)
    {
        $created_at = new \DateTime($tweet->created_at);
        $created_at_relative = Carbon::instance($created_at)->diffForHumans();

        $user_created_at = new \DateTime($tweet->user->created_at);
        $user_created_at_relative = Carbon::instance($user_created_at)->diffForHumans();

        $url = 'https://twitter.com/' . $tweet->user->screen_name . '/status/' . $tweet->id_str;

        $replacements = array(
            '%coordinates%' => $tweet->coordinates,
            '%created_at.formatted%' => $created_at->format($this->datePattern),
            '%created_at.relative%' => $created_at_relative,
            '%created_at%' => $tweet->created_at,
            '%geo%' => $tweet->geo,
            '%id_str%' => $tweet->id_str,
            '%in_reply_to_screen_name%' => $tweet->in_reply_to_screen_name,
            '%in_reply_to_status_id_str%' => $tweet->in_reply_to_status_id_str,
            '%in_reply_to_user_id_str%' => $tweet->in_reply_to_user_id_str,
            '%place%' => $tweet->place,
            '%retweet_count%' => $tweet->retweet_count,
            '%source%' => $tweet->source,
            '%text%' => html_entity_decode($tweet->text),
            '%url%' => $url,
            '%user.created_at.formatted%' => $user_created_at->format($this->datePattern),
            '%user.created_at.relative%' => $user_created_at_relative,
            '%user.created_at%' => $tweet->user->created_at,
            '%user.description%' => $tweet->user->description,
            '%user.favourites_count%' => $tweet->user->favourites_count,
            '%user.followers_count%' => $tweet->user->followers_count,
            '%user.friends_count%' => $tweet->user->friends_count,
            '%user.id_str%' => $tweet->user->id_str,
            '%user.lang%' => $tweet->user->lang,
            '%user.listed_count%' => $tweet->user->listed_count,
            '%user.location%' => $tweet->user->location,
            '%user.name%' => $tweet->user->name,
            '%user.profile_background_color%' => $tweet->user->profile_background_color,
            '%user.profile_background_image_url_https%' => $tweet->user->profile_background_image_url_https,
            '%user.profile_background_image_url%' => $tweet->user->profile_background_image_url,
            '%user.profile_image_url_https%' => $tweet->user->profile_image_url_https,
            '%user.profile_image_url%' => $tweet->user->profile_image_url,
            '%user.profile_link_color%' => $tweet->user->profile_link_color,
            '%user.profile_sidebar_border_color%' => $tweet->user->profile_sidebar_border_color,
            '%user.profile_sidebar_fill_color%' => $tweet->user->profile_sidebar_fill_color,
            '%user.profile_text_color%' => $tweet->user->profile_text_color,
            '%user.screen_name%' => $tweet->user->screen_name,
            '%user.statuses_count%' => $tweet->user->statuses_count,
            '%user.time_zone%' => $tweet->user->time_zone,
            '%user.url%' => $tweet->user->url,
            '%user.utc_offset%' => $tweet->user->utc_offset,
        );

        $formatted = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->pattern
        );

        return $formatted;
    }
}
