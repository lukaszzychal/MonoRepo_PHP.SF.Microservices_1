<?php

namespace App\Tests\US\Application;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetDetailsUserControllerTest
{

    public function testGiveUUIDWhenSendReqestThenReturnDetailsUser()
    {
        $userUUID = AppFixtures::USER_UUID;

        $client = $this->createClient();
        $client->request(
            Request::METHOD_GET,
            "/users/{$userUUID}",
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => '1234567890',
            ]
        );

        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($response->getContent());
        $responseAray = json_decode($response->getContent(), true);
        $this->assertIsArray($responseAray);
        $this->assertArrayHasKey('email', $responseAray);
        $this->assertSame(AppFixtures::USER_EMAIIL, $responseAray['email']);
        // ... uzupełnić o koleejne testy
    }
}
