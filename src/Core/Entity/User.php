<?php

namespace App\Core\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User extends Entity implements UserInterface {
    private $email;
    private $password;
    private $userType;
    private $token;
    private $tokenExpire;
    private $passwordResetToken;
    private $registrationToken;
    private $passwordResetTokenRequestedAt;
    private $firstName;
    private $lastName;
    private $fullName;

    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email): void{
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getToken(){
        return $this->token;
    }

    public function setToken($token): void{
        $this->token = $token;
    }

    public function getTokenExpire(){
        return $this->tokenExpire;
    }

    public function setTokenExpire($tokenExpire): void{
        $this->tokenExpire = $tokenExpire;
    }

    public function getPasswordResetToken(){
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken($passwordResetToken): void{
        $this->passwordResetToken = $passwordResetToken;
    }

    public function getRegistrationToken(){
        return $this->registrationToken;
    }

    public function setRegistrationToken($registrationToken): void{
        $this->registrationToken = $registrationToken;
    }

    public function getPasswordResetTokenRequestedAt(){
        return $this->passwordResetTokenRequestedAt;
    }

    public function setPasswordResetTokenRequestedAt($passwordResetTokenRequestedAt): void{
        $this->passwordResetTokenRequestedAt = $passwordResetTokenRequestedAt;
    }


    public function getUserType(){
        return $this->userType;
    }


    public function setUserType($userType){
        $this->userType = $userType;
    }

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername():string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setFirstName($firstName): void{
        $this->firstName = $firstName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function setLastName($lastName): void{
        $this->lastName = $lastName;
    }


    public function getFullName(){
        return $this->getFirstName() .' '. $this->getLastName();
    }

}