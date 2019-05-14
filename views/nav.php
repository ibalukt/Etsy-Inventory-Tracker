<nav class="navbar navbar-expand-sm bg-dark navbar-dark" style='height:45px !important;'>
    <!-- Brand -->
    <a class="navbar-brand mr-3" href="#"><h3>Etsy Inventory Tracker</h3></a>

    <!-- Links -->
    <?php if(isset($_SESSION['UserName']))
    { ?>
    <ul class="navbar-nav ml-auto">
         <li class="nav-item ">
            <a class="nav-link" href="inventory.php?main" >My Inventory </a>
        </li>
        <li class='nav-item'>
            <a class="nav-link" href="inventory.php?offsite" > OffSite Inventories </a>
        </li>
        <li class="nav-item ml-auto">
            <a class="nav-link" href="actions/logout.php" >Logout <i class="fas fa-sign-out-alt"></i></a>
        </li>
    </ul>
    <?php } ?>
</nav>