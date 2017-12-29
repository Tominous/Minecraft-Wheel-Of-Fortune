var currentDate = new Date();
currentDate.setHours(24);
currentDate.setMinutes(0);
currentDate.setSeconds(0);

var countDownDate = new Date(currentDate).getTime();

var x = setInterval(function() 
{
	var now = new Date().getTime();
	var distance = countDownDate - now;
	
	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	
	document.getElementById("wof-timecounter").innerHTML = "<div><span>" + hours + "<br/>godzin </span></div>"
	+ "<div><span>" + minutes + "<br/>minut </span></div>" + "<div><span>" + seconds + "<br/>sekund</span></div>";
	
	if (distance < 0) 
	{
		clearInterval(x);
		document.getElementById("demo").innerHTML = "Odśwież strone :)";
	}
}, 1000);