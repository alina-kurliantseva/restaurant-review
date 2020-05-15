<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Alina Kurliantseva | Restaurant Review</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="PHP, XML, CSS, Adobe Photoshop">
        <meta name="keywords" content="PHP, XML, CSS, Adobe Photoshop">
        <link rel="stylesheet" href="SiteStyles.css"/>
    </head>
    
    <body>
        <?php
            $fileName = "restaurant-review.xml";
            $restaurants = simplexml_load_file("$fileName");
            if (isset($_POST)) {
                extract($_POST);
                if (!isset($drpRestaurant) || $drpRestaurant == -1) {
                    $drpRestaurant = -1;
                    $txtAddress = "";
                    $txtSummary = "";
                    $drpRating = 1;
                } else {
                    $r = $restaurants->restaurant[intval($drpRestaurant)];
                    $txtAddress = $r->location->address . ", "
                                    . $r->location->city . ", "
                                    . $r->location->province . " "
                                    . $r->location->postalCode;
                    if (!isset($btnSave)) {
                        $txtSummary = $r->summary;
                        $drpRating = intval($r->rating);
                    } else {
                        $r->rating = $drpRating;
                        $restaurants->asXML("./$fileName");
                        $confirmation = "Revised Restaurant Review has been saved to " . $fileName;
                    }                
                }
            }  
        ?>
        <h2>Restaurant Review</h2>
        <div class="profileForm">
            <form action="restaurant-review.php" method="post" id="restaurant-review-form">
                <div>
                    <label>Restaurants:</label>
                </div>
                <div>
                    <select name="drpRestaurant" id="drpRestaurant" onchange="this.form.submit();">
                        <option value="-1" <?php print ($drpRestaurant === "-1" ? "Selected" : "")?> >Select...</option>
                        <?php
                            $rs = $restaurants->restaurant;
                            for ($i = 0; $i < count($rs); $i++) {
                                $r = $rs[$i];
                                print "<option value = '$i' " . ($drpRestaurant == $i ? 'Selected' : '') . " >$r->name</option>";
                            }                       
                        ?>
                    </select>
                </div><br />
                <?php if ($drpRestaurant != -1) : ?>               
                    <div>
                        <label>Address:</label>
                    </div>
                    <div>
                        <textarea rows="3" cols="40" name="txtAddress" disabled>
                            <?php
                                print isset($txtAddress) ? $txtAddress : "";
                            ?>
                        </textarea>
                    </div><br />
                    <div>
                        <label>Summary:</label>
                    </div>
                    <div>
                        <textarea rows="17" cols="40" name="txtSummary">
                            <?php
                                print isset($txtSummary) ? $txtSummary : "";
                            ?>
                        </textarea>
                    </div><br />
                    <div>
                        <label>Rating:</label>
                    </div>
                    <div>
                        <select name="drpRating">
                            <?php
                                $r = $restaurants->restaurant;
                                for ($i = 1; $i <= 5; $i++) {
                                    print "<option value = '$i' " . ($drpRating == $i ? 'Selected' : '') . " >$i</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <input type="submit" name="btnSave" value="Save Changes" />
                    </div>
                    <div>
                        <?php
                            print $confirmation;
                        ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </body>
</html>
