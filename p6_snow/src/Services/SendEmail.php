<?php


namespace App\Services;


use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SendEmail extends AbstractController
{
    private $emailFrom;
    private $emailTo;
    //private $message;


    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @param $userEmail
     * @param $token
     * @param $userLastname
     * @param $bodyMessage
     * @param $pathToEmailPage
     */
    public function sendEmail($userEmail, $token, $userLastname, $bodyMessage, $pathToEmailPage)
    {

        $message = (new Swift_Message('Mot de passe perdu'))//TODO: put a var from fc param instead raw text
        ->setFrom('send@example.com')
            ->setTo($userEmail)
            ->setBody(
                $this->renderView(
                    $pathToEmailPage,
                    ['token' => $token,
                        'lastName' => $userLastname,
                        'message' => $bodyMessage
                    ]),
                'text/html'
            );
        //return $this->getMessage();
        $this->mailer->send($message);

    }

    /**
     * @return mixed
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * @param mixed $emailFrom
     */
    public function setEmailFrom($emailFrom): void
    {
        $this->emailFrom = $emailFrom;
    }

    /**
     * @return mixed
     */
    public function getEmailTo()
    {
        return $this->emailTo;
    }

    /**
     * @param mixed $emailTo
     */
    public function setEmailTo($emailTo): void
    {
        $this->emailTo = $emailTo;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }


}