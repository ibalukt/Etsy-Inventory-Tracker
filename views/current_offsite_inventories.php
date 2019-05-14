<?php
    $query = "SELECT * FROM OffSite OS JOIN Icon I ON OS.IconID = I.IconID WHERE EndDate IS NULL";
    $OffSites = $crud->getData($query); 
?>

        <h3 class='text-secondary text-center pt-5 pb-4'></h2>
            <div class='col-sm-12 ' style="max-height:170px;">
                <div id="demo" class="carousel slide"  data-interval="false" data-ride="none">
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class='carousel-item active'>
                            <div class='col-sm-12 ml-auto mr-auto'>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <a href='index.php?withdraw' style='text-decoration:none;'>
                                            <div class='card'>
                                                <i class='fas fa-plus ml-auto mr-auto pt-3 text-secondary' style='font-size:3.5em;'></i>
                                                <div class='card-body text-center'> 
                                                <p class='card-text'>Add an Offsite Inventory</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                        <?php
                        foreach ($OffSites as $key => $OffSite)
                        {
                            if ((($key % 3) == 0) && ($key > 0))
                            {
                            echo "
                                            </div>
                                        </div>
                                    </div>
                                    <div class='carousel-item'>
                                        <div class='col-sm-12 ml-auto mr-auto'>
                                            <div class='row'>";
                            }
                            echo    
                                "
                                <div class='col-sm-3'>
                                    <a href='index.php?offsite_items&inventory_id=$OffSite[OffSiteID]' style='text-decoration:none'>
                                    <div class='card'>
                                        $OffSite[Icon_HTML]
                                        <div class='card-body text-center'> 
                                        <p class='card-text'>$OffSite[GoingWhere]</p>
                                        </div>
                                        <br/>
                                    </div>
                                    </a>
                                </div>";  

                        }
                        ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Left and right controls -->
                <a class="carousel-control-prev" style="position:absolute; left:-80px;"  href="#demo" data-slide="prev">
                    <span><i class="fa fa-angle-left text-secondary" style='font-size:3em;' aria-hidden="true"></i></span>
                </a>
                <a class="carousel-control-next" style="position:absolute; right:-80px;" href="#demo" data-slide="next">
                    <span><i class="fa fa-angle-right text-secondary" style='font-size:3em;' aria-hidden="true"></i></span>
                </a>
            </div>
        </div>