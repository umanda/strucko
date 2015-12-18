## Strucko - Open, Free and Community Driven Expert Dictionary

The idea behind Strucko is to create open, free and community driven expert dictionary. 
Anyone can register and contribute to the dictionary by suggesting new terms, 
translations and definitions for existing terms. 
While suggestions are visible to registered (and logged in) users, the 
user with administrator role can approve suggestions, which makes them publicly 
visible.

Terms can be entered in different languages, and added to different scientific fields.
The user chooses the language, scientific field and part of speech for the term.
Administrator checks the suggestion, and if everything is OK, he approves the term.

## Official Live Strucko Web App

Official Strucko web app is available here:  <http://strucko.com/>

## Installation and Usage

Strucko The Expert Dictionary is built using [Laravel 5.1 PHP Framework](http://laravel.com/docs/5.1/).
We used [MySQL](https://www.mysql.com/) for database storage, [Twitter Bootstrap](http://getbootstrap.com/)
as HTML, CSS, and JS framework and [jQuery](https://jquery.com/) for some specific JavaScript tasks.

To start using Strucko:
1. Clone this repository. 
2. In your shell/cmd go to your new repo folder and run commands *composer install* and *npm install* 
to get all dependencies.
3. Create a MySQL database which will be used by your new app (or you can use different database, 
check [Laravel database documentation](http://laravel.com/docs/5.1/database).
4. Create a .env file in your root folder and enter username and pass for your database. Reffer to 
[Environment Configuration](http://laravel.com/docs/5.1/installation#environment-configuration)
on how to do that.
4. Run command: *php artisan migrate*. This will create needed tables in your database.
5. Run command: *php artisan db:seed*. This will seed your database with default languages,
scientific fields, part of speeches and admin user.
6. Change email and password for the admin user. To do that, enter commands:
..1. *php artisan tinker*
..2. *$user = App\User::where('email', 'admin@example.com')->firstOrFail();*
..3. *$user->email = 'your_email@domain.com';*
..4. *$user->password = bcrypt('your_password');*
..5. *$user->verified = 1;*
..6. *$user->save();*
7. You can now run your new Strucko Expert Dictionary. Don't know how? Try googling LAMP stack, or you can 
use PHP builtin web server. If you use Netbeans, take a look at this article on how to 
easily run Laravel app in it.


## Contributing

Thank you for considering contributing to the Expert Dictionary project!
Consider using "Issues" and/or "Pull requests" feature on GitHub or 
send an e-mail to admin@strucko.com and tell us about your ideas.

## Security Vulnerabilities

If you discover a security vulnerability within Strucko, please use "Issues" and/or "Pull requests" 
feature on GitHub, or send an e-mail to admin@strucko.com and tell us about the problem.

## License

Strucko The Expert Dictionary is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
