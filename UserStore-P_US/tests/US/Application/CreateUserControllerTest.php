<?php

declare(strict_types=1);

namespace App\Tests\US\Application;

use DateTimeImmutable;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Application
 */
class CreateUserControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private string $token;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createClient();
        $this->token = $this->getContainer()->getParameter('app_token');
    }

    /**
     * @group acuc
     *
     * @return void
     */
    public function testCreateUserWithValidData()
    {
        $uuid = 'bb67376a-3b55-42f8-b210-bf170c9d8052';
        $this->client->request(
            Request::METHOD_POST,
            '/users',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            json_encode([
                'uuid' => $uuid,
                'firstName' => 'Łukasz',
                'lastName' => 'Z',
                'email' => 'email.testowy@test.pl',
                'address' => [],
                'birthDate' => (new DateTimeImmutable())->format('d:m:Y'),
                'avatar' => '',
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = $this->client->getResponse();
        $context = json_decode($response->getContent(), true);
        $contextFle = json_decode(
            file_get_contents(
                __DIR__.'/../DataMock/NotificationSuccesResponse.json'
            ),
            true
        );
        $this->assertJson($response->getContent());
        $this->assertJson(file_get_contents(__DIR__.'/../DataMock/NotificationSuccesResponse.json'));
        $this->assertArrayHasKey('message', $context);
        $this->assertStringContainsString('User created: #', $context['message']);
        $this->assertSame(201, $context['code']);

        // .....
    }

    // public function testCreateUserWithInValidDataThrowException()
    // {
    //     $client = $this->createClient();
    // }
}
