# Ninjatt by Phil

Ninjatt is a simple Chatapp where real Ninjas can meet eachother :octocat:

## Technologies

*Frontend
    -HTML
    -CSS
    -Javascript
    -JQuery

*Backend
    -MySQL
    -PHP
    -AJAX
    -[ReactPHP][1]
    -[Ratchet][2]

[1]: https://reactphp.org/ "ReactPHP"
[2]: http://socketo.me/ "Ratchet Websocket"

## Requirements

-To use Ninjatt you have to be familiar with Composer!!!
-XAMPP or similar (MySQL)

If you don't know how to use Composer go to: https://getcomposer.org/
## Getting Started

-Clone or download gitrepo and add the folder "ninjatt" to your localhost (e.g. htdocs)
-Start XAMPP Server + MySQL DB

-Create a database named "ninjatt" and add the users (file in ninjatt/db)
-Open your terminal, navigate to ninjatt(root) and hit the following cmd:

```bash
composer update
```
-It will add a new folder named vendor and downloads all the needed files which are required

-Now navigate to ninjatt/bin/ and start the server/socket by:

```bash
php server.php
```

-Now we're good to go! Open your browser and go to "localhost/ninjatt/index.php"

-Login with one of the following users:
    -Usermail: akali@ionia.com, password: 1234
    -Usermail: martinja@ninjatt.com, password: 1234 :trollface:
    -Usermail: shrouded@moon.com, password: 1234

-User Arrowkeys to navigate on the canvas

**TO TEST SOCKET OPEN ANOTHER BROWSER e.g. "edge" AND LOGIN WITH ANOTHER USER**
**PLEASE LOGOUT WITH LOGOUT BUTTON**

## Database

SQL Databasename: "ninjatt"

## Authors

Philipp Koch phil.koch@gmx.ch +41 79 262 18 39 SAE Zuerich WDD320

## Version History

* 1.0
    * Initial Release

## KNOWN BUGS PLEASE READ!!!

- Logout does not work properly when browser or tab gets closed :shit:
- Ninjaoutfit can not be changed for now