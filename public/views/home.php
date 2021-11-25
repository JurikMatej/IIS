<!-- Home page for registered users-->


<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>
<?php require_once "templates/user-operations.inc.php";?>

<div class="home-header">
    <h1>Welcome to home page</h1>
</div>
        

<div class="scroll-container">
    <style>
    /* Set width of scrollbar*/
    ::-webkit-scrollbar {width: 15px;}

    /* Track */
    ::-webkit-scrollbar-track {
    background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
    background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
    background: #555;
    }

    </style>

    <div class="home-auction">
    <?php foreach($auctions as $auction) {
        require "templates/show_auction.inc.php";
    } ?>

</div>

<?php require_once "templates/footer.inc.php";?>