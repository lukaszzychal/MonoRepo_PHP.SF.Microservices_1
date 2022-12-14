<?php

declare(strict_types=1);

namespace App\Tests\Unit\NF\Infrastructure;

use App\NF\Domain\Enum\TypeEnum;
use App\NF\Infrastructure\Request\NotificationRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group unit
 */
class NotificationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $request = Request::create(
            '/',
            'GET',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'CorrectToken',
            ],
            json_encode([
                'type' => 'email',
            ])
        );
        $NfRequest = NotificationRequest::fromRequest($request);

        $this->assertSame('CorrectToken', $NfRequest->token);
        $this->assertSame(TypeEnum::EMAIL->value, $NfRequest->type);
    }
}
