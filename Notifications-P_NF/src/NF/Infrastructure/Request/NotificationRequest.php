<?php

namespace App\NF\Infrastructure\Request;

use App\NF\Infrastructure\Exception\InvalidParemeterRequest;
use Symfony\Component\HttpFoundation\Request;

class NotificationRequest
{
    private function __construct(
        public readonly string $token,
        public readonly string $type
    ) {
    }

    public static function fromRequest(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $headers = $request->headers;
        if (
            !self::arrayHasKeys(
                $content,
                ['type']
            )
            ||
            !$headers->has('AUTHORIZATION')
        ) {
            throw new InvalidParemeterRequest();
        }
        $token = $headers->get('AUTHORIZATION');
        return new self(
            $token,
            $content['type']
        );
    }

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
