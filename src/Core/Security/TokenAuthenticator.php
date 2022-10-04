<?php

namespace App\Core\Security;

use App\Core\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    protected $serverService;
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request)
    {
        return array(
            'token' => $this->getBearerToken($request)
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'];

        if (null === $token ) {
            return;
        }

        return $this->getUserByToken($token);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function getBearerToken($request)
    {
        $headerToken = $request->headers->get('Authorization');

        $data = explode(' ', $headerToken);

        if(count($data) != 2 || $data[0] != 'Bearer')
        {
            return null;
        }

        return $data[1];
    }

    public function getUserByToken($token) {
        $user = $this->em->getRepository(User::class)->findOneBy(['token' => $token]);
        return $user;
    }
}