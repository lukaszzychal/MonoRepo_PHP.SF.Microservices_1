<?php

declare(strict_types=1);

namespace App\US\Infrastructure\Http\CreateUser;

use App\US\Application\Write\Command\CreateUser\CreateUserCommand;
use App\US\Infrastructure\TokenRequest\RequiredTokenRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use US\Infrastructure\Http\CreateUser\CreateUserRequest;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserController extends AbstractController implements RequiredTokenRequestInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    #[Route(path: '/users', methods: ['POST'])]
    public function createUser(
        Request $request
    ): JsonResponse {
        $createUserrRequest = CreateUserRequest::fromRequest($request, $this->serializer);
        $this->validator->validate($createUserrRequest);
        $this->messageBus->dispatch(new CreateUserCommand(
            (string) $createUserrRequest->uuid,
            $createUserrRequest->firstName,
            $createUserrRequest->lastName,
            $createUserrRequest->email
        ));

        return CreateUserResponse::create((string)$createUserrRequest->uuid);
    }
}
