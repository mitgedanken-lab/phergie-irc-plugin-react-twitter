<?php
/**
 * Phergie plugin for displaying data from tweets
 * (https://github.com/phergie/phergie-irc-plugin-react-twitter)
 *
 * @link https://github.com/phergie/phergie-irc-plugin-react-twitter for the canonical source repository
 * @copyright Copyright (c) 2008-2014 Phergie Development Team (http://phergie.org)
 * @license http://phergie.org/license New BSD License
 * @package Phergie\Irc\Plugin\React\Twitter
 */

namespace Phergie\Irc\Plugin\React\Twitter;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Phergie\Irc\Bot\React\AbstractPlugin;
use Phergie\Irc\Bot\React\EventQueueInterface as Queue;
use Phergie\Irc\Client\React\LoopAwareInterface;
use Phergie\Irc\Event\UserEventInterface as Event;
use React\EventLoop\LoopInterface;
use WyriHaximus\React\Guzzle\HttpClientAdapter;

/**
 * Plugin class.
 *
 * @category Phergie
 * @package Phergie\Irc\Plugin\React\Twitter
 */
class Plugin extends AbstractPlugin implements LoopAwareInterface
{
    /**
     * HTTP client used to interact with the Twitter API
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * HTTP client plugin used to authenticate using OAuth
     *
     * @var \GuzzleHttp\Subscriber\Oauth\Oauth1
     */
    protected $oauth;

    /**
     * Event loop used by the HTTP client adapter
     *
     * @var \React\EventLoop\LoopInterface
     */
    protected $loop;

    /**
     * Accepts plugin configuration.
     *
     * Supported keys:
     *
     * consumer_key - OAuth consumer key
     *
     * consumer_secret - OAuth consumer secret
     *
     * token - OAuth token
     *
     * token_secret - OAuth token secret
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->oauth = $this->getOauth($config);
    }

    /**
     * Stores the event loop used by the bot for later use by the HTTP client
     * adapter.
     *
     * @param \React\EventLoop\LoopInterface $loop
     */
    public function setLoop(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * Returns a configured HTTP client.
     *
     * The client is instantiated lazily to allow the event loop to be injected
     * into the instance of this class so that it can be used here by the
     * client adapter.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(array(
                'base_url' => 'https://api.twitter.com/1.1/',
                'adapter' => new HttpClientAdapter($this->loop),
            ));
            $this->client->getEmitter()->attach($this->oauth);
        }
        return $this->client;
    }

    /**
     * Extracts OAuth credentials from configuration and creates a configured
     * OAuth plugin for the HTTP client.
     *
     * @param array $config
     * @return \GuzzleHttp\Subscriber\Oauth\Oauth1
     * @throws \RuntimeException if any OAuth credentials are missing or invalid
     */
    protected function getOauth(array $config)
    {
        $keys = array(
            'consumer_key',
            'consumer_secret',
            'token',
            'token_secret',
        );

        $params = array();
        foreach ($keys as $key) {
            if (empty($config[$key]) || !is_string($config[$key])) {
                throw new \RuntimeException(
                    '"' . $key . '" is not set or is not a string'
                );
            }
            $params[$key] = $config[$key];
        }

        return new Oauth1($params);
    }

    /**
     * Indicates that that plugin monitors messages for Twitter URLs.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'url.host.twitter.com' => 'handleUrl',
        );
    }

    /**
     * Handles a Twitter URL extracted from an IRC message.
     *
     * @param string $url
     * @param \Phergie\Irc\Event\UserEventInterface $event
     * @param \Phergie\Irc\Bot\React\EventQueueInterface $queue
     */
    public function handleUrl($url, Event $event, Queue $queue)
    {
        $parsed = parse_url($url);
        $path = $parsed['path'];
        if (!preg_match('#/status/(?P<id>[0-9]+)$#', $path, $match)) {
            return;
        }
        $id = $match['id'];

        $this->getClient()
            ->get('statuses/show/' . $id, array('auth' => 'oauth'))
            ->then(
                array($this, 'handleSuccess'),
                array($this, 'handleError')
            );
    }

    /**
     * Handles a successful fetch of tweet data.
     */
    public function handleSuccess()
    {
        var_dump(func_get_args());
    }

    /**
     * Handles a failed fetch of tweet data.
     */
    public function handleError()
    {
        var_dump(func_get_args());
    }
}
