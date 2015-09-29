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
        <title>SMS JSP Page</title>
    </head>
    <body>
        <jsp:useBean id="mybean" scope="session" class="myPackage.HandlerSMS" /> 
        <jsp:setProperty name="mybean" property="email"  />
        <jsp:setProperty name="mybean" property="telefono"  />
        <jsp:setProperty name="mybean" property="mensaje"  />
    </body>
    <h1>Respuesta: <jsp:getProperty name="mybean" property="sms" /> </h1>
</html>
