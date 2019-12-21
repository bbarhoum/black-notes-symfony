<?php


namespace App\Bridge;


use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class PlaceholderBridge
 * @package App\Bridge
 */
class PlaceholderBridge
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPosts(int $page = 1, int $limit = 100)
    {
        try {
            $response = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/posts');
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getContent(), true);
            } else {
                return [];
            }
        } catch (TransportExceptionInterface $e) {
            return [];
        }
    }
}