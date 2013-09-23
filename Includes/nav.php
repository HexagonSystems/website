<!--
    * File Name         :       nav.php *
    * Author            :       Michael S MacDonald *
    * ID                :       6333834 *
    * Date Made         :       25/03/2013 *
    * Date Modified     :       13/03/2013 *
    * Description       :       This is where all the options for the navbar will go *



-->
<?php
     echo '<link rel="stylesheet" href="theme/standardtheme/adminstyle.css" type="text/css" media="screen" />';
     //Grab the config file
     require_once '../includes/config.php';


     echo '<div id="mainNav">';
     echo '<ul>';
     //Check to see if they are viewing the Main backend page
     if($thisLocation == "controlPanel")
     {
        //echo '<li class="home">Admin Home</li>';
        echo '<li><a class="activeLink" href=index.php>Admin Home</a></li>';
     }
     else
     {
       echo '<li><a href=index.php>Admin Home</a></li>';
     }
     //Check to see if they are viewing Product management page
     if ($thisLocation == "productMan")
     {
        //echo '<li class="products">View Products</li>';
        echo '<li><a class="activeLink" href=products.php>Manage Products</a>';
        echo '<ul>';
        echo '<li><a href="products.php#addProd">Add a Product</a></li>';
        echo '<li><a href="products.php#remProd">Remove a Product</a></li>';
        echo '<li><a href="products.php#editProd">Edit a Product</a></li>';
        echo '</ul>';
        echo '</li>';
     }
     else
     {
        echo '<li><a href=products.php>Manage Products</a>';
        echo '<ul>';
        echo '<li><a href="products.php#addProd">Add a Product</a></li>';
        echo '<li><a href="products.php#remProd">Remove a Product</a></li>';
        echo '<li><a href="products.php#editProd">Edit a Product</a></li>';
        echo '</ul>';
        echo '</li>';
     }
     //Check to se if they are viewing the Category Management
     if ($thisLocation == "categoryMan")
     {
       //echo '<li class="about">About Us</li>';
       echo '<li><a class="activeLink" href=categories.php>Manage Categories</a>';
       echo '<ul>';
       echo '<li><a href=categories.php>Add a Category</a></li>';
       echo '<li><a href=categories.php>Remove a Category</a></li>';
       echo '<li><a href=categories.php>Edit a Category</a></li>';
       echo '</ul>';
       echo '</li>';
     }
     else
     {
       echo '<li><a href=categories.php>Manage Categories</a>';
       echo '<ul>';
       echo '<li><a href=products.php>Add a Category</a></li>';
       echo '<li><a href=products.php>Remove a Category</a></li>';
       echo '<li><a href=products.php>Edit a Category</a></li>';
       echo '</ul>';
       echo '</li>';
     }
     //Check to see if they are viewing Page Management
     if ($thisLocation == "pageMan")
     {
       echo '<li><a class="activeLink" href=pages.php>Manage Pages</a></li>';
     }
     else
     {
       echo '<li><a href=pages.php>Manage Pages</a></li>';
     }
     //Check to see if they have access to the main admin pages
     if(isset($_SESSION['Id']) && isset($_SESSION['loginType']))
     {
       //Check to see if they are viewing the user Management page
        if ($thisLocation == "userMan")
       {
         echo '<li><a class="activeLink">Manage Users</a></li>';
       }
       else
       {
         echo '<li><a>Manage Users</a></li>';
       }
       //Check to see if they are viewing the admin Management page
       if ($thisLocation == "adminMan")
       {
         echo '<li><a class="activeLink">Manage Admins</a></li>';
       }
       else
       {
         echo '<li><a>Manage Admins</a></li>';
       }
     }
     echo '</ul>';
     echo '</div>';

     //Account Navigation Section
     echo '<div id="accountNav">';
     echo '<ul class="accountNavUl">';
     echo '<li><a href=index.php><img src="../images/icons/logout.png" alt="Account Information"></a>';
          echo '<ul>';
          echo '<li><a href=index.php><img src="../images/icons/logout.png" alt="Password Change" />Edit Password</a></li>';
          echo '<li><a href=index.php><img src="../images/icons/logout.png" alt="Password Change"></a></li>';
          echo '<li><a href=index.php><img src="../images/icons/logout.png" alt="Password Change"></a></li>';
          echo '<li><a href=index.php><img src="../images/icons/logout.png" alt="Password Change"></a></li>';
          echo '</ul>';
     echo '</li>';
     echo '<li><a href=logout.php><img src="../images/icons/logout.png" alt="Log out"></a></li>';
     echo '</ul>';
     echo '</div>';

?>