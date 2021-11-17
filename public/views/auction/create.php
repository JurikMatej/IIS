<!-- GET /auctions/create -->
<!-- Shows form to fill and send , creates an auction -->

<?php require_once "templates/header.inc.php";?>
<?php require_once "templates/navbar.inc.php";?>

<div class="create-auction">
    <p> Date will be chosen by the approver !</p>
    <form action="/auctions/send" method="post">
        <label for="name"> Name: </label>
        <input type="text" name="name" id="name"><br>
        <label for="description"> Description: </label>
        <input type="text" name="description" id="desc"><br>
        <label for="quantity">Time limit: </label>
        <input type="number" id="hours" name="hours" min="0" max="99" value="0">
        <label for="quantity"> hours</label>
        <input type="number" id="minutes" name="minutes" min="0" max="59" value="0">
        <label for="minutes"> minutes</label><br>
        <label for="starting_bid"> Starting bid ($): </label> 
        <input type="number" name="starting_bid" value="1"><br>
        <label for="minimum_bid_increase"> Minimum bid increase ($): </label>
        <input type="number" name="minimum_bid_increase" value="0"><br>
        <label for="biding_minutes">Biding interval: </label>
        <input type="number" id="biding_minutes" name="biding_minutes" min="0" max="60" value="0">
        <label for="biding_minutes"> minutes</label><br>

        <label for="ruleset">Choose ruleset:</label>
        <select name="ruleset" id="ruleset" onchange="check()">
            <?php foreach ($rulesets as $ruleset){

                echo "<option value=\"" . $ruleset->id ."\">". $ruleset->ruleset . "</option>";
            }?>
        </select>
        <br>

        <label for="type">Choose type:</label>
        <select name="type" id="type">
        <?php foreach ($types as $type){

            echo "<option value=\"" . $type->id ."\">". $type->type . "</option>";
        }?>
        </select>
        <br>

        <input type="submit" value="Send auction to approval">
    </form>

    <script>
        document.getElementById("name").required = true;
        document.getElementById("desc").required = true;

        function check()
        {
            if (document.getElementById("ruleset").value === '2')
            {
                document.getElementById('type').value = '1';
                document.getElementById('type').disabled = true;
            }
            else 
            {
                document.getElementById('type').disabled = false;
            }
        }
        
        
    </script>
</div>

<?php require_once "templates/footer.inc.php";?>