<html>
<head>
	<title>ThaiCreate.Com JSP Tutorial</title>
</head>
<body>
	<%
	//int
	int num1 = 5;
	int num2;
	num2 = 10;
	int num3 = 20 , num4 = 30;
	
	out.print("<br>num1 = " + num1);
	out.print("<br>num2 = " + num2);
	out.print("<br>num3 = " + num3);
	out.print("<br>num4 = " + num4);
	
	//String
	String name = "Win";
	String firstname, lastname;
	firstname = "Weerachai";
	lastname = "Nukitram";
	out.print("<br>name = " + name);
	out.print("<br>firstname = " + firstname);
	out.print("<br>lastnam = " + lastname);
	%>
</body>
</html>