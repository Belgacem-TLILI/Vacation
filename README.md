
# Vacation days calculator


### Tech

The technologies i used in this application are:

* [PHP] - Version 7.2.10
* [PHPUnit] - Version 7.5.11


### How to run it?

Clone the repository then


Install dependencies

```
$ composer install
```

To generate the output data file run
```
$ php -r 'include "Run.php"; Run::export(2006);'
```

This will generate a CSV file inside the data folder with the year name, you can try different years, just change 2006 by your desired year

To un the unit test you can just run
```
$ phpunit
```