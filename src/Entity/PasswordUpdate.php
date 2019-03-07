<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PasswordUpdate
{
  
    private $id;

 
    private $oldPassword;

    /**
     * @Assert\Length(min=8,minMessage="Votre mot de passe doit faire au moins 8 caractères")
     *
     * @var [type]
     */
    private $newPassword;
   /**
     * @Assert\EqualTo(propertyPath="newPassword",message="Vous n'avez pa correctement confirmer votre mot de passe")
     *
     * @var [type]
     */
    private $confimPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfimPassword(): ?string
    {
        return $this->confimPassword;
    }

    public function setConfimPassword(string $confimPassword): self
    {
        $this->confimPassword = $confimPassword;

        return $this;
    }
}
