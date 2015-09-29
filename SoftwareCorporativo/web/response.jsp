<%-- 
    Document   : response
    Created on : Sep 9, 2015, 2:40:59 PM
    Author     : cristeam2
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>JSP Page</title>
    </head>
    <body>
        <jsp:useBean id="mybean" scope="session" class="myPackage.Handler" /> 
        <jsp:setProperty name="mybean" property="name"  /></body>
    <h1>Hello, <jsp:getProperty name="mybean" property="name" /> </h1>
</html>
