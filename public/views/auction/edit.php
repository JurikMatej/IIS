<?php require_once "templates/user-operations.inc.php";?>

<div class="edit-auction">
    <h3>Edit your auction</h3>
    <form action="/auctions/<?=$auction->getId()?>/send-edit" method="post">
        <label for="name"> Name: </label>
        <input type="text" name="name" id="name" value="<?=$auction->getName()?>"><br>
        <label for="description"> Description: </label>
        <input type="text" name="description" id="desc" value="<?=$auction->getDescription()?>"><br>
        <label for="quantity">Time limit: </label>
        <input type="number" id="hours" name="hours" min="0" max="99" value="0">
        <label for="quantity"> hours</label>
        <input type="number" id="minutes" name="minutes" min="0" max="59" value="0">
        <label for="minutes"> minutes</label><br>
        <label for="starting_bid"> Starting bid ($): </label> 
        <input type="number" name="starting_bid" value="<?=$auction->getStartingBid()?>"><br>
        <label for="minimum_bid_increase"> Minimum bid increase ($): </label>
        <input type="number" name="minimum_bid_increase" value="<?=$auction->getMinimumBidIncrease()?>"><br>

        <label for="ruleset">Choose ruleset:</label>
        <select name="ruleset" id="ruleset" onchange="check()">
            <?php foreach ($rulesets as $ruleset){
                if ($auction->getRuleset() === $ruleset->ruleset){ 
                    echo 'selected="selected"';
                }
                
                echo "<option value=\"" . $ruleset->id ."\">". $ruleset->ruleset . "</option>";
            }?>
        </select>
        <br>

        <label for="type">Choose type:</label>
        <select name="type" id="type">
        <?php foreach ($types as $type){
            if ($auction->getType() === $type->type){ 
                echo 'selected="selected"';
            }

            echo "<option value=\"" . $type->id ."\">". $type->type . "</option>";
        }?>
        </select>
        <br>

        <input type="submit" value="Update auction">
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