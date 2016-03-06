<?php
// start the session
session_start(); 

if ( !$_SESSION['admin'] )
{
	header ( "location: adminlogin.php" );
}

require_once ( "classes/dbController.class.php" );
require_once ( "classes/pageMenu.class.php" );
require_once ( "classes/quickForm.class.php" );
require_once ( "classes/adminAddEvent.class.php" );
require_once ( "classes/adminEditEvent.class.php" );
require_once ( "classes/adminDeleteEvent.class.php" );
require_once ( "classes/dateFormat.class.php" );
require_once ( "classes/adminMenuClass.class.php" );
require_once ( "classes/newTournament.class.php" );
require_once ( "classes/adminAddMember.class.php" );
require_once ( "classes/adminEditMember.class.php" );
require_once ( "classes/adminDeleteMember.class.php" );
require_once ( "classes/adminAddAdmin.class.php" );
require_once ( "classes/adminEditAdmin.class.php" );
require_once ( "classes/adminDeleteAdmin.class.php" );

$pmargs['adminMenu'] = 1;
$pm = new pageMenu ($pmargs);
$menu = $pm->buildPageMenu ();


//add an event
if ( $_GET['pageid'] == 2 && $_GET['submenuid'] == 1 )
{
	$add = new adminAddEvent ();
	$pagecontent = $add->buildAddEvent ();
}
//edit an event
else if ( $_GET['pageid'] == 2 && $_GET['submenuid'] == 2 )
{
	$edit = new adminEditEvent ();
	$pagecontent = $edit->viewEventList ();
}
//delete an event
else if ( $_GET['pageid'] == 2 && $_GET['submenuid'] == 3 )
{
	$delete = new adminDeleteEvent ();
	$pagecontent = $delete->viewEventList ();
}
//menu management
else if ( $_GET['pageid'] == 3 && $_GET['submenuid'] == 4 )
{
	$pagecontent = "<div class='contentcontainer'><div class = 'contentheader' >Menu Admin</div><div class='maincontentdiv' id='maincontentdiv'>";
	$mc = new adminMenuClass ();
	$pagecontent.= $mc->buildMenuEntry (false);//echo $pagecontent;
	$pagecontent.= "</div></div>";
}
//create a tournament
else if ( $_GET['pageid'] == 4 && $_GET['submenuid'] == 5 )
{
	$pagecontent = "<div class=contentcontainer><div class = contentheader >Create Tournament</div><div class=maincontentdiv>";
	$tc = new newTournament ();
	$pagecontent.= $tc->setNumberOfPlayers (false);//echo $pagecontent;
	$pagecontent.= "</div></div>";
}
//add players to a tournament
else if ( $_GET['pageid'] == 4 && $_GET['submenuid'] == 1 )
{
	$tcargs['tournamentid'] = $_GET['tournamentid'];
	$tcargs['players'] = $_GET['players'];
	$tc = new newTournament ($tcargs);
	$pagecontent = $tc->addPlayersToTournament (false);//echo $pagecontent;
}
//edit tournament layout
else if ( $_GET['pageid'] == 4 && $_GET['submenuid'] == 2 )
{
	$tcargs['tournamentid'] = $_GET['tournamentid'];
	$tcargs['players'] = $_GET['players'];
	$tc = new newTournament ($tcargs);
	$pagecontent = $tc->editTournamentLayout (false);//echo $pagecontent;
}
//add member
else if ( $_GET['pageid'] == 5 && $_GET['submenuid'] == 6 )
{
	$am = new adminAddMember ();
	$pagecontent = $am->addMemberInput ();
}
//edit member
else if ( $_GET['pageid'] == 5 && $_GET['submenuid'] == 7 )
{
	$am = new adminEditMember ();
	$pagecontent = $am->viewMembersList ();
}
//delete member
else if ( $_GET['pageid'] == 5 && $_GET['submenuid'] == 8 )
{
	$am = new adminDeleteMember ();
	$pagecontent = $am->viewMembersList ();
}
//add admin user
else if ( $_GET['pageid'] == 6 && $_GET['submenuid'] == 9 )
{
	$am = new adminAddAdmin ();
	$pagecontent = $am->addAdminInput ();
}
//edit admin user
else if ( $_GET['pageid'] == 6 && $_GET['submenuid'] == 10 )
{
	$am = new adminEditAdmin ();
	$pagecontent = $am->viewAdminList ();
}
//delete admin user
else if ( $_GET['pageid'] == 6 && $_GET['submenuid'] == 11 )
{
	$am = new adminDeleteAdmin ();
	$pagecontent = $am->viewAdminList ();
}
else
{
$pagecontent = "<div class='contentcontainer'><div class = 'contentheader' >Admin Section</div><div class='maincontentdiv' id='maincontentdiv'>
Welcome to the Admin Section of Club 3000. <br /><br />
The Admin Section is the main engine of the site. This is where you can customise the Menu, add some Events to the Events Calendar and also administer Members and Admin users.<br /><br />
Click the Menu on the left hand side for the area of which you want to administer.<br /><br />
By logging in as an Administrator you are also now given the ability to add, edit and delete content from the main site. To add content, simply click on the \"Add Item to This Page\" link. You will then be able to add an item to that page. <br /><br />
To edit or delete some content, click the edit or delete options in the header section of the item you wish to edit or delete.<br /><br />
</div></div>";
}




echo<<<EOHTML
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<link rel='stylesheet' type='text/css' href='styles/styles.css' />
<script type='text/javascript' src='javascript/menu.js'></script>
<script type='text/javascript' src='javascript/jQuery-lib.js'></script>
<script type='text/javascript' src='javascript/jQuery.js'></script>
<script type='text/javascript' src='javascript/ajaxClass.js'></script>
<script type='text/javascript' src='javascript/club3000.js'></script>
<link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />
  <script type="text/javascript" src="http://jqueryui.com/latest/jquery-1.3.2.js"></script>
  <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.core.js"></script>
  <script type="text/javascript" src="http://jqueryui.com/latest/ui/ui.datepicker.js"></script>


<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Carnoustie Caledonia Golf Club</title>
</head>
<body>
<form id="form1" method="post" action="">
<div id='container'>
	<div id='container2'>
		<div id="banner">
			<span><img src = 'images/logo2.png'></span>
		</div>
		<div id='statusbar'>
		</div>
		<div id="maincontent">
			<div id="nav">
			$menu
			</div>
			<div id="content">
			$pagecontent
			</div>
		</div>
	</div>
	<div id='footer'>
	Club3000 | www.carnoustiecaledonia.co.uk | 2009 RalphyBoy
	</div>
</div>



$meminfo
</form>
</body>
</html>

EOHTML;
?>


