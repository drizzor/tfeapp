<!DOCTYPE html>
<html>
        <head>
                <title><?= SITENAME . ' / '?><?= isset($title) ? h($title) : '' ?></title>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <link rel="SHORTCUT ICON" href="<?= APPICON ?>" />
                <!-- <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">         -->
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/bootstrap.css">
                <!-- <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"> -->
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/all.min.css">
                <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/> -->
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/dataTables.bootstrap4.min.css">
                <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/user-style.css">
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/main.css">
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/gallery.css">
                <link rel="stylesheet" type="text/css" href="<?= URLROOT; ?>/css/baguetteBox.min.css">
                <link href="https://fonts.googleapis.com/css?family=Amatic+SC|Open+Sans+Condensed:300&display=swap" rel="stylesheet"> 
        </head>

	<body>
                <?php require APPROOT . '/views/inc/navbar.php'; ?>                