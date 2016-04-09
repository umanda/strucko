<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Header Language Lines
    |--------------------------------------------------------------------------
    |
    |
    */
    
    'confirm.email.subject' => 'Verify your email',
    'confirm.view.title' => 'Thanks for registering',
    'confirm.view.description' => 'Strucko confirmation email',
    'confirm.view.header' => 'Hello :name, thanks for signing up!',
    'confirm.view.body' => '<p>
        We just need you to <a href=":url" target="_blank">confirm your email</a> real quick!
        </p>
        <p>
            As a registered user you can now vote for existing terms and also suggest 
            new terms and definitions.
        </p>
        <p>
        Thanks,
        <br>
        Strucko Team
        </p>',
    
    'reset.email.subject' => 'Password reset link',
    'reset.view.title' => 'Password reset email',
    'reset.view.description' => 'Password reset email on Strucko IT Dictionary',
    'reset.view.header' => 'Hello :name,',
    'reset.view.body' => '<p>
        We have received a password reset request for your account on
        strucko.com. If you did not make this request,
        please disregard this email.
    </p>
    <p>
        You can reset your password <a href=":url" target="_blank">here</a>.
    </p>
    <p>
        Thanks,
        <br>
        Strucko Team
    </p>',
];
