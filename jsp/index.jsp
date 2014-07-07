
<%@page import="java.util.Date" %>
<%@page import="java.util.ArrayList" %>
<html>
<head>
	<title>ThaiCreate.Com JSP Tutorial</title>
</head>
<body>
    <%@include file="banner.jsp" %>
    <%
        ArrayList<String> abc = new ArrayList<String>();
        abc.add("a");
        abc.add("d");
        abc.add("e");
        Date date = new Date();
    %>
    
    This time is <%=date%>
    MY First Array = 
    <%
        out.print("<br>");
        for(int i = 0; i<abc.size(); i++){
            out.print(abc.get(i)+"<br>");
        }
    %>
    
</body>
</html>