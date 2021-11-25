<!-- GET /auctions/create -->
<!-- Shows form to fill and send , creates an auction -->

<?php require_once "templates/user-operations.inc.php";?>

<div class="create-auction">
    <p> Date will be chosen by the approver !</p>
    <form action="/auctions/send" method="post" enctype="multipart/form-data">
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
        <input type="number" name="minimum_bid_increase" id="bid_inc" value="0"><br><br>
        <label for="imgs">Select images:</label>
        <input type="file" id="imgs" name="imgs[]" accept="image/*" multiple><br><br>

        <label for="ruleset">Choose ruleset:</label>
        <select name="ruleset" id="ruleset" onchange="checkRuleset()">
            <?php foreach ($rulesets as $ruleset){

                echo "<option value=\"" . $ruleset->id ."\">". $ruleset->ruleset . "</option>";
            }?>
        </select>
        <br>

        <label for="type">Choose type:</label>
        <select name="type" id="type" onchange="checkType()">
        <?php foreach ($types as $typ){

            echo "<option value=\"" . $typ->id ."\">". $typ->type . "</option>";
        }?>
        </select>
        <br><br>

        <input type="submit" value="Send auction to approval" name="submit"><br><br>
    </form>

    <script>
        document.getElementById("name").required = true;
        document.getElementById("desc").required = true;

        function checkRuleset()
        {
            if (document.getElementById("ruleset").value === '2')
            {
                document.getElementById('bid_inc').disabled = true;
                document.getElementById('bid_inc').value = 0;
            }
            else 
            {
                document.getElementById('bid_inc').disabled = false;
            }
        }
        function checkType()
        {
            if (document.getElementById("type").value === '2')
            {
                document.getElementById('bid_inc').disabled = true;
                document.getElementById('bid_inc').value = 0;
            }
            else
            {
                document.getElementById('bid_inc').disabled = false;
            }
        }

        // file size validation
        var MAX_FILE_SIZE = 3 * 1024 * 1024; // 3MB
        $(document).ready( function()
        {
            $('#imgs').change( function()
            {
                for (let i = 0; i < this.files.length; ++i)
                {
                    fileSize = this.files[i].size;
                    if (fileSize > MAX_FILE_SIZE)
                    {
                        this.setCustomValidity("File must not exceed 3 MB!");
                        this.reportValidity();
                    } else
                    {
                        this.setCustomValidity("");
                    }
                }
            });
        });
    </script>
</div>