<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Mailers\AppMailer;
use Illuminate\Http\Request;
use App\User;
use DB;
use Carbon\Carbon;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    
    /**
     * Redirect to this specific path after password reset.
     * 
     * @var string 
     */
    protected $redirectPath = '/';
    protected $redirectTo = '/';
    

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request, AppMailer $mailer)
    {   
        // Validate email on the form.
        $this->validate($request, ['email' => 'required|email']);
        // Find the user with with the given email and which is not banned.
        $user = User::where('email', $request->email)
                ->where('banned', 0)
                ->first();
        // Return if the user does not exits.
        if (is_null($user)) {
            return back()->with([
                    'alert' => 'Email not found...',
                    'alert_class' => 'alert alert-warning'
                ]);
        }
        // Create new token
        $token = hash_hmac('sha256', str_random(40), getenv('APP_KEY'));
        // Delete existing token for user.
        DB::table('password_resets')->where('email', $user->email)->delete();
        // Create new token for user
        DB::table('password_resets')->insert(['email' => $user->email, 'token' => $token, 'created_at' => Carbon::now()]);
        
        // Send email with token reset link.
        $mailer->sendEmailResetLinkTo($user, $token);
        return back()->with([
                    'alert' => 'Password reset link sent. Check your mailbox.',
                    'alert_class' => 'alert alert-success'
                ]);
    }
}
