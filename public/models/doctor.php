<?php
require __DIR__ . '/../../index.php';

$doctorsTable = new ORM($pdo, 'doctor', 'id');

$doctors = $doctorsTable->read(['id', 'name', 'titleid', 'joining_date']);
