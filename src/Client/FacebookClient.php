<?php

namespace App\Client;

use App\Model\FacebookPost;
use App\Model\FacebookPostAttachments;
use Contao\CoreBundle\Filesystem\VirtualFilesystemInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FacebookClient
{

    public function __construct(
        private readonly VirtualFilesystemInterface $filesStorage,
        private readonly string                     $newsDir,
        private readonly HttpClientInterface        $httpClient,
        private readonly string                     $accessToken)
    {
    }

    public function posts(): array
    {
        $posts = [];

        foreach ($this->fetchPosts() as $data) {
            $post = (new FacebookPost($data))->parse();
            $attachments = (new FacebookPostAttachments(
                $this->filesStorage,
                $this->newsDir,
                $this->httpClient,
                $this->accessToken
            ))->parse($data, $post['sid']);

            $posts[] = array_merge($post, $attachments);
        }

        return $posts;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function fetchPosts(): array
    {
        $response = $this->httpClient->request('GET', 'https://graph.facebook.com/ffwenns/posts', [
            'query' => [
                'limit' => 5,
                'access_token' => $this->accessToken,
                'fields' => 'id,created_time,updated_time,message,message_tags,attachments{target,type,subattachments}'
            ]
        ]);

        return $response->toArray()['data'];
    }

}
