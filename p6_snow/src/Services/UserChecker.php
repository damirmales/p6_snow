<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    private $flashMessage;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashMessage = $flashBag;
    }

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user)
    {
        $status = $user->getStatus();
        if ($status == 0) {
            $messageToUser = $this->flashMessage->set("status", "Avez vous validé votre inscription par l'email reçu ?");
            throw new DisabledException("Le status est 0," . $messageToUser);
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}