<?php

namespace US\Application;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

/**
 * @group Application
 * 
 */
class CreateUserControllerTest extends WebTestCase
{
    /**
     * @group now123
     *
     * @return void
     */
    public function testCreateUserWithValidData()
    {
        $client = $this->createClient();
        $client->request(
            Request::METHOD_POST,
            '/users',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'CorrectToken',
            ],
            json_encode([
                'uuid' => UuidV4::v4(),
                'firstName' => 'Łukasz',
                'lastName' => 'Z',
                'email' => 'email.testowy@test.pl',
                'address' => [],
                'birthDate' => (new DateTimeImmutable())->format('d:m:Y'),
                'avatar' => ''
            ])

        );
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($response->getContent());
        $this->assertSame('User created', json_decode($response->getContent()));
    }


    // public function testCreateUserWithInValidData()
    // {
    //     $client = $this->createClient();
    // }
}
