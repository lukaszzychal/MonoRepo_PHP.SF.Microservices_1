<?php

declare(strict_types=1);

namespace App\Tests\US\Unit;

use App\US\Shared\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group now12
 */
class ExceptionTest extends TestCase
{
    public function testToArray()
    {
        $exception = new Exception(
            Response::HTTP_BAD_REQUEST,
            'BAD REQUEST',
            'DETAILS BAD REQUEST',
            'file name, line name'
        );

        $eArray = $exception->toArray();
        $this->assertArrayHasKey('code', $eArray);
        $this->assertSame(Response::HTTP_BAD_REQUEST, (int) $eArray['code']);

        $this->assertArrayHasKey('title', $eArray);
        $this->assertSame('BAD REQUEST', $eArray['title']);

        $this->assertArrayHasKey('details', $eArray);
        $this->assertSame('DETAILS BAD REQUEST', $eArray['details']);

        $this->assertArrayHasKey('source', $eArray);
        $this->assertSame('file name, line name', $eArray['source']);
    }

    public function testToJsonResponse(): void
    {
        $exception = new Exception(
            Response::HTTP_BAD_REQUEST,
            'BAD REQUEST',
            'DETAILS BAD REQUEST',
            'file name, line name'
        );

        $eJsonResponse = $exception->toJsonResponse();
        $this->assertSame(Response::HTTP_BAD_REQUEST, $eJsonResponse->getStatusCode());
        $eArray = json_decode($eJsonResponse->getContent(), true);
        $this->assertArrayHasKey('title', $eArray);
        $this->assertSame('BAD REQUEST', $eArray['title']);

        $this->assertArrayHasKey('details', $eArray);
        $this->assertSame('DETAILS BAD REQUEST', $eArray['details']);

        $this->assertArrayHasKey('source', $eArray);
        $this->assertSame('file name, line name', $eArray['source']);
    }

    public function testException(): void
    {
        $exception = new Exception(
            Response::HTTP_BAD_REQUEST,
            'BAD REQUEST',
            'DETAILS BAD REQUEST',
            'file name, line name'
        );

        $this->assertInstanceOf(Exception::class, $exception);
    }
}
