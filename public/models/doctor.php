<?php
require __DIR__ . '/../../index.php';

$doctorsTable = new ORM($pdo, 'doctor', 'id');
$titlesTable = new ORM($pdo, 'title', 'id');
$experiencesTable = new ORM($pdo, 'experience', 'id');
$branchesTable = new ORM($pdo, 'branch', 'id');

$doctors = $doctorsTable->read(['id', 'name', 'titleid', 'xplevel', 'branchid', 'joining_date']);
$titles = $titlesTable->read(['id', 'name']);
$experiences = $experiencesTable->read(['id', 'name']);
$branches = $branchesTable->read(['id', 'name']);

$titles_id = array_column($titles, 'name', 'id');
$experiences_id = array_column($experiences, 'name', 'id');
$branches_id = array_column($branches, 'name', 'id');

foreach ($doctors as $doctor) {
	$doctor['titleid'] = $titles_id[$doctor['titleid']];
	$doctor['branchid'] = $branches_id[$doctor['branchid']];
	$doctor['xplevel'] = $experiences_id[$doctor['xplevel']];

	$doctors_list[] = $doctor;
}