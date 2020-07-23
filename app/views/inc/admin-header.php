<!DOCTYPE html>
<html>
	<head>
        <title><?= SITENAME . ' / '?><?= isset($title) ? h($title) : 'Dashboard' ?></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="SHORTCUT ICON" href="<?= APPICON ?>" />
        <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/admin-style.css">
        <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/main.css">
        <script src="<?= URLROOT; ?>/js/lib/chart.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
	</head>
	<body>
        <?php require APPROOT . '/views/inc/admin-top-navbar.php'; ?>
        <?php require APPROOT . '/views/inc/admin-side-navbar.php'; ?>
        
                        