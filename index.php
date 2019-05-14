
<?php
session_start();
//this statement includes an instance of the database connection file
include_once("classes/Crud.php");
//include an instance of the crud methods so they are available for use
$crud = new Crud();
//fetch the data from the database

/*$query = "SELECT * FROM Inventory WHERE State = ? ORDER BY ItemName ASC";
$params = array('active');
$results = $crud->prep_getData($query,"s",$params);*/


echo "<script> var message = ''; </script>";
if (isset($_POST['new_exists']))
{
    if ($_POST['num_new'] > 0)
    {
        echo "<script> message = '$_POST[num_new] new items were inserted into the database'; </script>";
    } 
    else
    {
        echo "<script> message = ''; </script>";
    }  
    //echo var_dump($_POST);
    //echo "<script> approve_new_items(); </script>";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP & MYSQL OOP CRUD SYSTEM </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->

    <style>
            @font-face {
            font-family: 'ibachmodernscriptmedium';
            src: url('style/ibachmodernscript-webfont.woff2') format('woff2'),
                url('style/ibachmodernscript-webfont.woff') format('woff');
            font-weight: normal;
            font-style: normal;
            }

            h3 {
                font-family:'ibachmodernscriptmedium';
            }

            h3 {
            }

            body {
                background-color:#e1eae0;
            }

            .container {
                /*background-color:#f0d5aa;*/
                background-color:white;
                width:67%;
            }

            .icon {
                font-size:3.5em;
                color: gray;
                padding-top:25px;
                margin-left:auto;
                margin-right:auto;
            }
    </style>

</head>
    <body style="position:relative;">
    <div id="box1" style="position:absolute; top:0px; left:-40px; width:50px; height:50px;">
        <img src="Images/Box1.png" style="z-index:-200; width:200px;" />
    </div>
    <div id="box2" style="position:absolute; top:0px; left:80px; width:50px; height:50px;">
        <img src="Images/Box4.png" style="z-index:-200; width:200px;" />
    </div>
    <div id="box3" style="position:absolute; top:0px; left:700px; width:50px; height:50px;">
        <img src="Images/Box3.png" style="z-index:-200; width:200px;" />
    </div>
    <div id="box4" style="position:absolute; top:0px; left:1300px; width:50px; height:50px;">
        <img src="Images/Box2.png" style="z-index:-200; width:200px;" />
    </div>
    <div id="box5" style="position:absolute; top:0px; left:1275px; width:50px; height:50px;">
        <img src="Images/Box6.png" style="z-index:-200; width:170px;" />
    </div>
            <nav class="navbar navbar-expand-sm bg-dark navbar-dark" style='height:45px !important;'>
            <!-- Brand -->
            <a class="navbar-brand mr-3" href="#"><h3>Etsy Inventory Tracker</h3></a>

            <!-- Links -->
            <?php if(isset($_SESSION['UserName']))
            { ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="index.php?main" >My Inventory </a>
                </li>
                <li class='nav-item'>
                    <a class="nav-link" href="index.php?offsite" > OffSite Inventories </a>
                </li>
                <li class="nav-item ml-auto">
                    <a class="nav-link" href="actions/logout.php" >Logout <i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
            <?php } ?>
        </nav>
        
        <div class="container" style="position:relative; min-height:800px;">
            <!---------------------TABLE START---------------------->
            <?php 

               if (isset($_SESSION['UserName']))
                {
                    if (isset($_GET['offsite']))
                    {
                        include_once('views/offsite_inventories.php');
                    }
                    elseif (isset($_GET['offsite_items']))
                    {
                        include_once('views/offsite_items.php');
                    }      
                    elseif (isset($_GET['edit_offsite_items']))
                    {
                        include_once('views/edit_offsite_items.php');
                    }
                    elseif (isset($_GET['withdraw']))
                    {
                        include_once('views/withdraw.php');
                    }
                    elseif (isset($_GET['edit_item']))
                    {
                        include_once('views/edit_item.php');
                    }
                    elseif (isset($_GET['change_pass']))
                    {
                        include_once('views/password_change.php');
                    }
                    else
                    {
                        include_once('views/main_inventory.php'); 
                        
                    }
                }
                else
                {
                    include_once('views/login.php'); 
                }
            ?>
                        <!----------------\"delete.php?id=$res[id]\"--LOOP ENDS-------------------->
        
        <footer style='height:30px;'>
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        
    <!-- bootbox code -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

        <script> 
            if (message != "")
            {
                bootbox.alert(message);
            }

            $('.btn_delete').click(function(event){
                event.preventDefault();
                 var destination = ($(this).attr("href"));
                 //alert(destination);
                bootbox.confirm({
                    message: "Are you sure that you want to Delete this record?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                        
                    },
                    callback: function (result) {
                        if (result==true)
                        {
                            window.location=destination;
                        }
                    }
                });
            });

        </script>
        <script>

        time=1;

        function getRandomInt(min, max)
        {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function fall()
        {
            setInterval(function ()
            {
                for (var i = 1; i < 6; i++)
                {
                    if (time == 1)
                    {
                        var ran = getRandomInt(-250, 800);
                        document.getElementById("box"+(i)).style.top = ran+"px";

                    }
                    else
                    {
                        var num = document.getElementById("box"+(i)).style.top
                        var num = num.split("px");
                        var num = Number(num[0]);
                        if (num < 700)
                        {
                            //console.log(num);
                            num+= 2;
                        }
                        else
                        {
                            num = -250;
                        }
                        document.getElementById("box"+(i)).style.top = num + "px";
                    }

                }
                time=2;
            }, 50);
        }

        fall();

        </script>
    </body>
</html>


