<?php

namespace App\NF\Infrastructure\Request;

use App\NF\Infrastructure\Exception\InvalidParemeterRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationRequest
{
    private function __construct(
        public readonly string $token,
        public readonly string $type
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        $headers = $request->headers;
        if (!$headers->has('Authorization')) {
            throw new InvalidParemeterRequest(['Authorization']);
        }
        $token = $headers->get('Authorization');

        $content = json_decode((string) $request->getContent(), true);
        if (is_null($content)) {
            // $this->logger->critical("Wrong Request: File:" . __FILE__ . '  Line: ' . __LINE__);
            // @todo Przerobić na konkretny wyjątek
            throw new \Exception('Wrong request. File: '.__FILE__.'; Line: '.__LINE__, Response::HTTP_BAD_REQUEST);
        }

        if (
            !self::arrayHasKeys(
                $content,
                ['type']
            )
        ) {
            throw new InvalidParemeterRequest(['Type']);
        }

        return new self(
            $token, // @phpstan-ignore-line
            $content['type']
        );
    }

    /**
     * @param string[] $content
     * @param string[] $keys
     */
    private static function arrayHasKeys(array $content, array $keys): bool
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $content)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
