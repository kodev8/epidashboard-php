<!-- set up for an html template  -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="<?= static_url("styles/stylesc.css"); ?>" />
    <link rel="stylesheet" type="text/css" href="<?= static_url("styles/toast.css") ?>" />

    <script src="<?= static_url("scripts/_components.js") ?>" ></script>
    <script src="<?= static_url('scripts/main.js') ?>" ></script>
    <script src="<?= static_url('scripts/_modal.js') ?>" ></script>
    
    <script src="<?= static_url("scripts/_toast.js") ?>" defer ></script>   
    <script src="<?= static_url('scripts/_search.js') ?>"defer></script>

    <link rel="icon" type="image/x-icon" href="<?= static_url("assets/epitafr-icon.png") ?>" >
    <script src="<?= static_url('scripts/globalFunctionality.js') ?>" defer ></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    

    <title>Epita Admin <?= !empty($title) ? "- $title" : '' ?> </title>
