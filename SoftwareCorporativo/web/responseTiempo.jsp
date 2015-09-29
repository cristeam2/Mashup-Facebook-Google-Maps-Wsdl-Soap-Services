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
        <title>Tiempo en ciudad</title>
    </head>
    <body>
        <jsp:useBean id="mybean" scope="session" class="myPackage.HandlerTiempo" /> 
        <jsp:setProperty name="mybean" property="ciudad"  /></body>
    <h1>Tiempo: <jsp:getProperty name="mybean" property="ciudad" /> </h1>
</html>
