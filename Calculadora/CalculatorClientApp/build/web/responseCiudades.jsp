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
        <title>Tiempo JSP Page</title>
    </head>
    <body>
        <jsp:useBean id="mybean" scope="session" class="myPackage.HandlerCiudades" /> 
        <jsp:setProperty name="mybean" property="ciudad"  /></body>
    <h1>Respuesta:</br> <jsp:getProperty name="mybean" property="ciudad" /> </h1>
</html>
