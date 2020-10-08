<?php declare (strict_types = 1);

namespace WarBot\Actions;

use Abraham\TwitterOAuth\TwitterOAuth;
use WarBot\Repositories\ConfigRepository;

class SendMessageAction
{
    /** @var ConfigRepository */
    private $config_repository;

    public function __construct(ConfigRepository $config_repository)
    {
        $this->config_repository = $config_repository;
    }

    public function handle(string $message, string $image): void
    {
        $connection = new TwitterOAuth(
            $this->config_repository->get('TWITTER_CONSUMER_KEY'),
            $this->config_repository->get('TWITTER_CONSUMER_SECRET'),
            $this->config_repository->get('TWITTER_ACCESS_TOKEN'),
            $this->config_repository->get('TWITTER_ACCESS_TOKEN_SECRET')
        );

        /** @var \stdClass */
        $media = $connection->upload('media/upload', ['media' => $image]);
        if (isset($media->errors)) {
            throw new \DomainException($media->errors[0]->message);
        }
        $connection->post("statuses/update", [
            "status"    => $message,
            'media_ids' => $media->media_id_string,
        ]);
    }
}
