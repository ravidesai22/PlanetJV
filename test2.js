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
-->

</script>



<style>
.today {
	COLOR: black;
	FONT-FAMILY: sans-serif;
	FONT-SIZE: 10pt;
	FONT-WEIGHT: bold
}

.days {
	COLOR: navy;
	FONT-FAMILY: sans-serif;
	FONT-SIZE: 10pt;
	FONT-WEIGHT: bold;
	TEXT-ALIGN: center
}

.dates {
	COLOR: black;
	FONT-FAMILY: sans-serif;
	FONT-SIZE: 10pt
}
</style>
