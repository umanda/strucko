<!-- resources/views/emails/password.blade.php -->
<h3>Hello {{ $user->name }}</h3>
<p>
    We have received a password reset request for your account on
    <a href="http://strucko.com/">strucko.com</a>. If you didn't make this request,
    please disregard this email.
</p>
<p>
    Reset your password <a href="{{ url('password/reset/'. $token) }}">here</a>.
</p>
<p>
    Thanks,
    <br>
    Strucko Team
</p>