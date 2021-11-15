<!-- GET /auctions/create -->
<!-- Shows form to fill and send , creates an auction -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<form action="/auctions/send" method="post">
    <label for="date"> Date:</label> <input type="datetime-local" name="date" ><br>
    <label for="name"> Name:</label> <input type="text" name="name" ><br>
    <label for="description"> Description:</label> <input type="text" name="description"><br>
    <label for="starting_bid"> Starting bid ($):</label> <input type="number" name="starting_bid"><br>
    <label for="minimum_bid_increase"> Minimum bid increase ($):</label> <input type="number" name="minimum_bid_increase"><br>

    <label for="ruleset">Choose a ruleset:</label>
    <select name="ruleset" id="ruleset">
        <?php foreach ($rulesets as $ruleset){

            echo "<option value=\"" . $ruleset->ruleset ."\">". $ruleset->ruleset . "</option>";
        }?>
    </select>
    <br>

    <label for="type">Choose a type:</label>
    <select name="type" id="type">
    <?php foreach ($types as $type){

        echo "<option value=\"" . $type->type ."\">". $type->type . "</option>";
    }?>
    </select>
    <br>
    <!-- TODO javascript cannot be close and descending bid -->
    <!-- TODO javascript on hover help -->
    
    <input type="submit" value="Send auction to approval">
</form>
<p <?php if(!isset($_GET['empty_fields'])){ echo "hidden";}?> style="color:red;"> All labels must be filled ! </p>

<?php require_once "templates/footer.inc.php";?>