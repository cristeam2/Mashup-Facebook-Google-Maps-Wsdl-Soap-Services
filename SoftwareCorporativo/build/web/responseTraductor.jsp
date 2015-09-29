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
        <title>Traductor jsp</title>
    </head>
    <body>
        <jsp:useBean id="mybean" scope="session" class="myPackage.HandlerTraductor" /> 
        <jsp:setProperty name="mybean" property="modo"  />
        <jsp:setProperty name="mybean" property="mensaje"  />
        <h1>Traduccion:</br> <jsp:getProperty name="mybean" property="traductor" /> </h1>
</html>
