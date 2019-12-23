<?php
require_once __DIR__ . "/../fhcc_includes/DatabaseConnection.php";
require __DIR__ . '/vendor/autoload.php';

$doctorsTable = new ORM($pdo, 'doctor', 'id');
$titlesTable = new ORM($pdo, 'title', 'id');

/* Use the following insert function to insert a few titles from this
link: http://www.pamf.org/physicians/titles.html
I've added one for you so you can check out the insert function format */
$titlesTable->insert([
    'name' => 'The rapist',
    'code' => 'A.U',
]);

/* Add different doctors.. keep in mind that the titleid is a foreign key
to the table `title` and `xplevel` ranges from 1-10
 */
$doctorsTable->insert([
    'name' => 'Shakoora Taylor',
    'titleid' => 0,
    'xplevel' => 3,
]);

/* Similarly, study the ORM class and conduct different tests..
Also when running a specific test, be sure to comment out the other ones:
like for e.g if you're testing doctor's insert function then comment out
the title's function
 */
