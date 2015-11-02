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
    protected $to;
    protected $view;
    protected $data = [];
    
    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }
    
    public function deliver()
    {
        $this->mailer->send($this->view, $this->data, function($message){
            $message->from($this->from, 'Strucko Admin')
                    ->to($this->to);
        });
    }
    
    public function sendEmailConfirmationTo(User $user)
    {
        $this->to = $user->email;
        $this->view = 'emails.confirm';
        $this->data = compact('user');
        
        $this->deliver();
    }
}
