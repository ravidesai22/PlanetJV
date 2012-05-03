<?php

// Enforce https on production
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == "http" && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}

/**
 * This sample app is provided to kickstart your experience using Facebook's
 * resources for developers.  This sample app provides examples of several
 * key concepts, including authentication, the Graph API, and FQL (Facebook
 * Query Language). Please visit the docs at 'developers.facebook.com/docs'
 * to learn more about the resources available to you
 */

// Provides access to Facebook specific utilities defined in 'FBUtils.php'
require_once('FBUtils.php');
// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');
// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');

/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

// Log the user in, and get their access token
$token = FBUtils::login(AppInfo::getHome());
if ($token) {

  // Fetch the viewer's basic information, using the token just provided
  $basic = FBUtils::fetchFromFBGraph("me?access_token=$token");
  $my_id = assertNumeric(idx($basic, 'id'));

  // Fetch the basic info of the app that they are using
  $app_id = AppInfo::appID();
  $app_info = FBUtils::fetchFromFBGraph("$app_id?access_token=$token");

  // This fetches some things that you like . 'limit=*" only returns * values.
  // To see the format of the data you are retrieving, use the "Graph API
  // Explorer" which is at https://developers.facebook.com/tools/explorer/
  $likes = array_values(
    idx(FBUtils::fetchFromFBGraph("me/likes?access_token=$token&limit=4"), 'data')
  );

  // This fetches 4 of your friends.
  $friends = array_values(
    idx(FBUtils::fetchFromFBGraph("me/friends?access_token=$token&limit=4"), 'data')
  );

  // And this returns 16 of your photos.
  $photos = array_values(
    idx($raw = FBUtils::fetchFromFBGraph("me/photos?access_token=$token&limit=16"), 'data')
  );

  // Here is an example of a FQL call that fetches all of your friends that are
  // using this app
  $app_using_friends = FBUtils::fql(
    "SELECT uid, name, is_app_user, pic_square FROM user WHERE uid in (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1",
    $token
  );

  // This formats our home URL so that we can pass it as a web request
  $encoded_home = urlencode(AppInfo::getHome());
  $redirect_url = $encoded_home . 'close.php';

  // These two URL's are links to dialogs that you will be able to use to share
  // your app with others.  Look under the documentation for dialogs at
  // developers.facebook.com for more information
  $send_url = "https://www.facebook.com/dialog/send?redirect_uri=$redirect_url&display=popup&app_id=$app_id&link=$encoded_home";
  $post_to_wall_url = "https://www.facebook.com/dialog/feed?redirect_uri=$redirect_url&display=popup&app_id=$app_id";
} else {
  // Stop running if we did not get a valid response from logging in
  exit("Invalid credentials");
}
?>

<!-- This following code is responsible for rendering the HTML   -->
<!-- content on the page.  Here we use the information generated -->
<!-- in the above requests to display content that is personal   -->
<!-- to whomever views the page.  You would rewrite this content -->
<!-- with your own HTML content.  Be sure that you sanitize any  -->
<!-- content that you will be displaying to the user.  idx() by  -->
<!-- default will remove any html tags from the value being      -->
<!-- and echoEntity() will echo the sanitized content.  Both of  -->
<!-- these functions are located and documented in 'utils.php'.  -->
<!DOCTYPE html>


<html lang="en">
  <head>

    <meta charset="utf-8">

    <!-- We get the name of the app out of the information fetched -->
    <title><?php echo(idx($app_info, 'name')) ?></title>
    <link rel="stylesheet" href="stylesheets/screen.css" media="screen">

    <!-- These are Open Graph tags.  They add meta data to your  -->
    <!-- site that facebook uses when your content is shared     -->
    <!-- over facebook.  You should fill these tags in with      -->
    <!-- your data.  To learn more about Open Graph, visit       -->
    <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
    <meta property="og:title" content=""/>
    <meta property="og:type" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:site_name" content=""/>
    <?php echo('<meta property="fb:app_id" content="' . AppInfo::appID() . '" />'); ?>



<!-- START  Edited by Ravi Desai at 05-11-2011 17:16:56   -->

<script language="JavaScript">

var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
var days = new Array("S", "M", "T", "W", "T", "F", "S");

today = new getToday();	
var element_id;
var temp;

var yr,mnt,dt;

function getDays(month, year) 
{
	// Test for leap year when February is selected.
	if (1 == month)
		return ((0 == year % 4) && (0 != (year % 100))) ||
			(0 == year % 400) ? 29 : 28;
	else
		return daysInMonth[month];
}

function getToday()
{
	// Generate today's date.
	this.now = new Date();
	this.year = this.now.getFullYear() ; // Returned year XXXX
	this.month = this.now.getMonth();
	this.day = this.now.getDate();
	//rv alert("aal ka din::"+days[this.now.getDay()]);
	temp = days[this.now.getDay()];
}

 
function newCalendar() 
{
	var parseYear = parseInt(document.all.year  [document.all.year.selectedIndex].text);
 
	var newCal = new Date(parseYear , document.all.month.selectedIndex, 1);
	var day = -1;
	var startDay = newCal.getDay();
	
	var daily = 0; 

	today = new getToday(); // 1st call
	if ((today.year == newCal.getFullYear() ) &&   (today.month == newCal.getMonth()))
	   day = today.day;
	// Cache the calendar table's tBody section, dayList.
	var tableCal = document.all.calendar.tBodies.dayList;

	var intDaysInMonth =
	   getDays(newCal.getMonth(), newCal.getFullYear() );

	for (var intWeek = 0; intWeek < tableCal.rows.length;  intWeek++)
		   for (var intDay = 0;
			 intDay < tableCal.rows[intWeek].cells.length;
			 intDay++)
	 {
		  var cell = tableCal.rows[intWeek].cells[intDay];

		  // Start counting days.
		  if ((intDay == startDay) && (0 == daily))
			 daily = 1;

		  // Highlight the current day.
		  cell.style.color = (day == daily) ? "red" : "";
		  if(day == daily)
		  {
				document.all.todayday.innerText= "Today: " +  day + "/" + 
					(newCal.getMonth()+1) + "/" + newCal.getFullYear() ;
		  }
		  // Output the day number into the cell.
		  if ((daily > 0) && (daily <= intDaysInMonth))
			 cell.innerText = daily++;
		  else
			 cell.innerText = "";
	   }

}
	  
	 function getTodayDay()
	 {
			    document.all[element_id].value = today.day + "/" + (today.month+1) + 
					"/" + today.year; 
		        //document.all.calendar.style.visibility="hidden";
				document.all.calendar.style.display="none";
				document.all.year.selectedIndex =100;   
	            document.all.month.selectedIndex = today.month; 
	 }
 
        function getDate() 
		 {
            // This code executes when the user clicks on a day
            // in the calendar.
            if ("TD" == event.srcElement.tagName)
               // Test whether day is valid.
               if ("" != event.srcElement.innerText)
			   { 
				 var mn = document.all.month.selectedIndex+1;
    			 var Year = document.all.year [document.all.year.selectedIndex].text;

					
				 //document.all[element_id].value=event.srcElement.innerText+"/"+mn +"/"  +Year;
		         
				//Modified bya ravi uncooment the upper line to restrore and comment the next 1 line(s)

				document.all[element_id].value=Year+mn+event.srcElement.innerText;
				yr=Year;
				mnt=mn;
				dt=event.srcElement.innerText;

				
				//document.all.calendar.style.visibility="hidden";
				 document.all.calendar.style.display="none";
			 }
		 }
 
function GetBodyOffsetX(el_name, shift)
{
	var x;
	var y;
	x = 0;
	y = 0;

	var elem = document.all[el_name];
	do 
	{
		x += elem.offsetLeft;
		y += elem.offsetTop;
		if (elem.tagName == "BODY")
			break;
		elem = elem.offsetParent; 
	} while  (1 > 0);

	shift[0] = x;
	shift[1] = y;
	return  x;
}	

function SetCalendarOnElement(el_name)
{
	if (el_name=="") 
	el_name = element_id;
	var shift = new Array(2);
	GetBodyOffsetX(el_name, shift);
	document.all.calendar.style.pixelLeft  = shift[0]; //  - document.all.calendar.offsetLeft;
	document.all.calendar.style.pixelTop = shift[1] + 25 ;
}
	  
 	  
	           
function ShowCalendar(elem_name)
{
		if (elem_name=="")
		elem_name = element_id;

		element_id	= elem_name; // element_id is global variable
		newCalendar();
		SetCalendarOnElement(element_id);
		//document.all.calendar.style.visibility = "visible";
		document.all.calendar.style.display="inline";
}

function HideCalendar()
{
	//document.all.calendar.style.visibility="hidden";
	document.all.calendar.style.display="none";
}

function toggleCalendar(elem_name)
{
	//if (document.all.calendar.style.visibility == "hidden")
	if(document.all.calendar.style.display=="none")
		ShowCalendar(elem_name);
	else 
		HideCalendar();
}

//Added by RaviDesai to convert into url @ 31/10/11 11:01:07 

function ConvertURL(form)
{
/*TO DO :
try to use : 
var d = new Date();
alert(d.getDay(02,10,2011));
alert(d.getDay());
03-11-2011 13:14:29 
*/

//  http://www.gujaratsamachar.com/20111030/purti/ravipurti/specto.html
var gurl = myForm.MyDate.value;
//alert("selected date is:"+gurl);
mnt=mnt-1;

var chosenDate=new Date();
chosenDate.setFullYear(yr,mnt,dt); //e.g: myDate.setFullYear(2010,00,14); returns 2010 January 14 's day ==>  4
//alert("Day:"+chosenDate.getDay()); 
var rv = chosenDate.getDay();

if(rv==0){
myWindow = open("http://www.gujaratsamachar.com/"+gurl+"/purti/ravipurti/specto.html");
}
else if(rv==3){
myWindow = open("http://www.gujaratsamachar.com/"+gurl+"/purti/shatdal/anavrut.html");

//http://www.gujaratsamachar.com/20111102/purti/shatdal/anavrut.html
}else{
alert("Jaybhai writes only on Sundays and Wednesdays..!! :)");
}




}
</script>

<!-- STOP-->










    <script>
      function popup(pageURL, title,w,h) {
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        var targetWin = window.open(
          pageURL,
          title,
          'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left
          );
      }
    </script>
    <!--[if IE]>
      <script>
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->


  </head>
  <body>
    <header class="clearfix">
      <!-- By passing a valid access token here, we are able to display -->
      <!-- the user's images without having to download or prepare -->
      <!-- them ahead of time -->
      <p id="picture" style="background-image: url(https://graph.facebook.com/me/picture?type=normal&access_token=<?php echoEntity($token) ?>)"></p>

      <div>
        <h1>Welcome, <strong><?php echo idx($basic, 'name'); ?></strong></h1>
        <p class="tagline">
          This is your app
          <a href="<?php echo(idx($app_info, 'link'));?>"><?php echo(idx($app_info, 'name')); ?></a>
        </p>
        <div id="share-app">
          <p>Share your app:</p>
          <ul>
            <li>
              <a href="#" class="facebook-button" onclick="popup('<?php echo $post_to_wall_url ?>', 'Post to Wall', 580, 400);">
                <span class="plus">Post to Wall</span>
              </a>
            </li>
            <li>
              <a href="#" class="facebook-button speech-bubble" onclick="popup('<?php echo $send_url ?>', 'Send', 580, 400);">
                <span class="speech-bubble">Send to Friends</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <section id="get-started">


<!-- START  Edited by Ravi Desai at 05-11-2011 17:16:56   -->
<!--      <p>Welcome to your Facebook app, running on <span>heroku</span>!</p>
      <a href="http://devcenter.heroku.com/articles/facebook" class="button">Learn How to Edit This App</a>
-->
	<p>Welcome to PlanetJV.</p>

	<FORM name=myForm>
	<INPUT id=MyDate name=MyDate size=15> 
	<!-- <a href="JavaScript:;" onClick="toggleCalendar('MyDate')">Calendar</a> -->
	<!-- modified -->
	<button type="button" onClick="toggleCalendar('MyDate')">Calendar</button>
	<button type="button" onClick="ConvertURL(this.form)">Submit</button>
	<!-- modified -->
	</FORM>




<TABLE bgColor=#ffffff border=1 cellPadding=0 cellSpacing=3 id=calendar style="DISPLAY: none; POSITION: absolute; Z-INDEX: 4">
  <TBODY>
  <TR>
    <TD colSpan=7 vAlign=center>
	<!-- Month combo box -->
	<SELECT id=month onchange=newCalendar()> 
    	<SCRIPT language=JavaScript>
		// Output months into the document.
		// Select current month.
		for (var intLoop = 0; intLoop < months.length; intLoop++)
			document.write("<OPTION " +	(today.month == intLoop ? "Selected" : "") + ">" + months[intLoop]);
		</SCRIPT>
	</SELECT> 
	<!-- Year combo box -->
	<SELECT id=year onchange=newCalendar()> 
        <SCRIPT language=JavaScript>
		// Output years into the document.
		// Select current year.
		for (var intLoop = 1900; intLoop < 2028; intLoop++)
			document.write("<OPTION " + (today.year == intLoop ? "Selected" : "") + ">" + intLoop);
		</SCRIPT>
	</SELECT> 

	</TD>
  </TR>


	
  <TR class=days>
	<!-- Generate column for each day. -->
    <SCRIPT language=JavaScript>
	// Output days.
	for (var intLoop = 0; intLoop < days.length; intLoop++)
		document.write("<TD>" + days[intLoop] + "</TD>");
	</SCRIPT>
  </TR>


  <TBODY class=dates id=dayList onclick="getDate('')" vAlign=center>
  <!-- Generate grid for individual days. -->
  <SCRIPT language=JavaScript>
	for (var intWeeks = 0; intWeeks < 6; intWeeks++)
	{
		document.write("<TR>");
		for (var intDays = 0; intDays < days.length; intDays++)
			document.write("<TD></TD>");
		document.write("</TR>");
	}
  </SCRIPT>

  <!-- Generate today day. --></TBODY>
  <TBODY>
  <TR>
    <TD class=today colSpan=5 id=todayday onclick=getTodayDay()></TD>
    <TD align=right colSpan=2><A href="javascript:HideCalendar();"><SPAN style="COLOR: black; FONT-SIZE: 10px">
    <B>Hide</B></SPAN></A></TD>
  </TR>
  </TBODY>

</TABLE>


<!-- STOP -->	


    </section>

    <section id="samples" class="clearfix">
      <h1>Examples of the Facebook Graph API</h1>

      <div class="list">
        <h3>A few of your friends</h3>
        <ul class="friends">
          <?php
            foreach ($friends as $friend) {
              // Extract the pieces of info we need from the requests above
              $id = assertNumeric(idx($friend, 'id'));
              $name = idx($friend, 'name');
              // Here we link each friend we display to their profile
              echo('
                <li>
                  <a href="#" onclick="window.open(\'http://www.facebook.com/' . $id . '\')">
                    <img src="https://graph.facebook.com/' . $id . '/picture?type=square" alt="' . $name . '">'
                    . $name . '
                  </a>
                </li>');
            }
          ?>
        </ul>
      </div>

      <div class="list inline">
        <h3>Recent photos</h3>
        <ul class="photos">
          <?php
            foreach ($photos as $key => $photo) {
              // Extract the pieces of info we need from the requests above
              $src = idx($photo, 'source');
              $name = idx($photo, 'name');
              $id = assertNumeric(idx($photo, 'id'));
              $class = ($key%4 === 0) ? ' class="first-column"' : '';

              // Here we link each photo we display to it's location on Facebook
              echo('
                <li style="background-image: url(' . $src . ')"' . $class . '>
                  <a href="#" onclick="window.open(\'http://www.facebook.com/' .$id . '\')">
                    ' . $name . '
                  </a>
                </li>'
              );
            }
          ?>
        </ul>
      </div>

      <div class="list">
        <h3>Things you like</h3>
        <ul class="things">
          <?php
            foreach ($likes as $like) {
              // Extract the pieces of info we need from the requests above
              $id = assertNumeric(idx($like, 'id'));
              $item = idx($like, 'name');
              // This display's the object that the user liked as a link to
              // that object's page.
              echo('
                <li>
                  <a href="#" onclick="window.open(\'http://www.facebook.com/' .$id .'\')">
                    <img src="https://graph.facebook.com/' . $id . '/picture?type=square" alt="' . $item . '">
                    ' . $item . '
                  </a>
                </li>');
            }
          ?>
        </ul>
      </div>

      <div class="list">
        <h3>Friends using this app</h3>
        <ul class="friends">
          <?php
            foreach ($app_using_friends as $auf) {
              // Extract the pieces of info we need from the requests above
              $uid = assertNumeric(idx($auf, 'uid'));
              $pic = idx($auf, 'pic_square');
              $name = idx($auf, 'name');
              echo('
                <li>
                  <a href="#" onclick="window.open(\'http://www.facebook.com/' .$uid . '\')">
                    <img src="https://graph.facebook.com/' . $uid . '/picture?type=square" alt="' . $name . '">
                    ' . $name . '
                  </a>
                </li>');
            }
          ?>
        </ul>
      </div>
    </section>

    <section id="guides" class="clearfix">
      <h1>Learn More About Heroku &amp; Facebook Apps</h1>
      <ul>
        <li>
          <a href="http://www.heroku.com/" class="icon heroku">Heroku</a>
          <p>Learn more about <a href="http://www.heroku.com/">Heroku</a>, or read developer docs in the Heroku <a href="http://devcenter.heroku.com/">Dev Center</a>.</p>
        </li>
        <li>
          <a href="http://developers.facebook.com/docs/guides/web/" class="icon websites">Websites</a>
          <p>
            Drive growth and engagement on your site with
            Facebook Login and Social Plugins.
          </p>
        </li>
        <li>
          <a href="http://developers.facebook.com/docs/guides/mobile/" class="icon mobile-apps">Mobile Apps</a>
          <p>
            Integrate with our core experience by building apps
            that operate within Facebook.
          </p>
        </li>
        <li>
          <a href="http://developers.facebook.com/docs/guides/canvas/" class="icon apps-on-facebook">Apps on Facebook</a>
          <p>Let users find and connect to their friends in mobile apps and games.</p>
        </li>
      </ul>
    </section>
  </body>
  </body>
</html>
