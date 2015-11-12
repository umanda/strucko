<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Mailers;

use Illuminate\Contracts\Mail\Mailer;
use App\User;

/**
 * Description of AppMailer
 *
 * @author ivancic
 */
class AppMailer {
    
    protected $mailer;
    protected $from = 'admin@strucko.com';
    protected $fromName = 'Strucko Admin';
    protected $to;
    protected $subject;
    protected $view;
    protected $data = [];
    
    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }
    
    protected function deliver()
    {
        $this->mailer->send($this->view, $this->data, function($message){
            $message->from($this->from, $this->fromName)
                    ->to($this->to)
                    ->subject($this->subject);
        });
    }
    
    public function sendEmailConfirmationTo(User $user)
    {
        $this->to = $user->email;
        $this->view = 'emails.confirm';
        $this->data = compact('user');
        $this->subject = 'Verify your email';
        $this->deliver();
    }
    
    public function sendEmailResetLinkTo (User $user, $token)
    {
        $this->to = $user->email;
        $this->view = 'emails.password2';
        $this->data = compact('user', 'token');
        $this->subject = 'Password reset link';
        $this->deliver();
    }
}
