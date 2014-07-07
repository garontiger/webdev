<html>
    <head>
        <title>ThaiCreate.Com JSP Tutorial</title>
    </head>
    <body>
        <%
            int a = 123;
            Integer b = new Integer(123);
            out.print("<br>a = " + a);
            out.print("<br>b = " + b);
        
            String ab = "String 1";
            String bc = new String("String 2");
            String c;
            c = "String 3";

            out.print("<br>a = " + ab);
            out.print("<br>b = " + bc);
        %>
    </body>
</html>