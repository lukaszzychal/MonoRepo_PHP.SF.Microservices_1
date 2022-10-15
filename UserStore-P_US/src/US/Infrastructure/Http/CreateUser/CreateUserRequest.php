<?php

namespace US\Infrastructure\Http\CreateUser;

use App\US\Infrastructure\Http\CreateUser\InvalidParemeterRequest;
use ErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @todo dodac logi
 */
final class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly Uuid $uuid,
        #[Assert\NotBlank]
        public readonly string $firstName,
        #[Assert\NotBlank]
        public readonly string $lastName,
        #[Assert\NotBlank]
        #[Assert\Email(
            message: 'The email {{ value }} is not a valid email.',
        )]
        public readonly string $email
    ) {
    }

    public static function fromRequest(
        Request $request,
        SerializerInterface $serializer,
    ): CreateUserRequest {
        try {
            $obj = $serializer->deserialize(
                $request->getContent(),
                CreateUserRequest::class,
                'json'
            );
            return $obj;
        } catch (ErrorException $th) {
            throw new InvalidParemeterRequest("Error Processing Request", 1);
        }
    }
}
