package org.me.calculator.client;

import java.io.*;
import javax.annotation.Resource;

import javax.servlet.*;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import javax.xml.ws.WebServiceContext;
import javax.xml.ws.WebServiceRef;

/**
 *
 * @author cristeam
 */
@WebServlet(name = "ClientServlet", urlPatterns = {"/ClientServlet"})
public class ClientServlet extends HttpServlet {

    @WebServiceRef(wsdlLocation = "http://localhost:8080/CalculatorApp/CalculatorWSService?wsdl")
    public CalculatorWSService service;

    @Resource
    protected WebServiceContext context;

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = response.getWriter();
        try {
            out.println("<h2>Servlet Cliente: " + request.getContextPath() + "</h2>");

            org.me.calculator.client.CalculatorWS port = service.getCalculatorWSPort();

            int i = Integer.parseInt(request.getParameter("value1"));
            int j = Integer.parseInt(request.getParameter("value2"));

            int result = port.add(i, j);

            out.println("<br/>");
            out.println("Resultado:");
            out.println("" + i + " + " + j + " = " + result);
            ((Closeable) port).close();

        } finally {
            out.close();
        }
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /**
     * Handles the HTTP <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Handles the HTTP <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Returns a short description of the servlet.
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }
    // </editor-fold>
}
