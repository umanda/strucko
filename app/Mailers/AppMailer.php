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
    
    public function sendEmailConfirmationTo(User $user, $locale = 'en')
    {
        $this->to = $user->email;
        $this->view = 'emails.confirm';
        $this->data = compact('user', 'locale');
        $this->subject = trans('emails.confirm.email.subject');
        $this->deliver();
    }
    
    public function sendEmailResetLinkTo (User $user, $token, $locale = 'en')
    {
        $this->to = $user->email;
        $this->view = 'emails.password2';
        $this->data = compact('user', 'token', 'locale');
        $this->subject = trans('emails.reset.email.subject');;
        $this->deliver();
    }
}
