<?php

namespace App\Bundles;




class MyMail
{ 
    public function __construct()
    {
        //TODO: ADD normal email send system
        $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        ->setAuthMode('login')
        ->setUsername('klimworkss@gmail.com')
        ->setPassword('4040720a');
        
        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('klimworkss@gmail.com')
        ->setTo('klimprograms@gmail.com')
        ->setBody('Here is the message itself')
            ;
        
        $mailer->send($message);
        
        
    }
}