<html>
    <head>
        <title>ThaiCreate.Com JSP Tutorial</title>
    </head>
    <body>
        <%
            String[] sCountry = new String[]{
                "Belgium", "France", "Italy", "Germany", "Spain"
            };

            for (int i = 0; i < sCountry.length; i++) {
                out.println("<br>Value index[" + i + "] : " + sCountry[i]);
            }

        %>
    </body>
</html>