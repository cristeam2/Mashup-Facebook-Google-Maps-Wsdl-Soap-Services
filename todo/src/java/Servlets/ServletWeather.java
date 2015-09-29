package Servlets;


import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.xml.ws.WebServiceRef;
import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import java.io.StringReader;
import java.util.Enumeration;
import net.webservicex.GlobalWeather;

/**
 *
 * @author cristeam
 */
@WebServlet(urlPatterns = {"/ServletWeather"})
public class ServletWeather extends HttpServlet {

    @WebServiceRef(wsdlLocation = "WEB-INF/wsdl/www.webservicex.net/globalweather.asmx.wsdl")
    private GlobalWeather service;

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try (PrintWriter out = response.getWriter()) {

            String[] paramValues = null;
            response.setContentType("text/plain");
            Enumeration<String> parameterNames = request.getParameterNames();
            String city = "";
            String country = "";
            while (parameterNames.hasMoreElements()) {
                String paramName = parameterNames.nextElement();
                paramValues = request.getParameterValues(paramName);
                city = paramValues[0];
                paramName = parameterNames.nextElement();
                paramValues = request.getParameterValues(paramName);
                country = paramValues[0];
            }

            String xml = getWeather(city, country);
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            DocumentBuilder builder;
            InputSource is;
            try {
                builder = factory.newDocumentBuilder();
                is = new InputSource(new StringReader(xml));

                Document doc = builder.parse(is);
                String res = "Informacion climatologica en: " + city + "\n" + "Temperatura: " + doc.getElementsByTagName("Temperature").item(0).getTextContent() + "\n Punto de rocio: " + doc.getElementsByTagName("DewPoint").item(0).getTextContent() + "\n Humedad: " + doc.getElementsByTagName("RelativeHumidity").item(0).getTextContent() + "\n Presion: " + doc.getElementsByTagName("Pressure").item(0).getTextContent() + "\n Visibilidad " + doc.getElementsByTagName("Visibility").item(0).getTextContent();
                out.write(res);
            } catch (ParserConfigurationException e) {
            } catch (SAXException e) {
            } catch (IOException e) {
            }
            out.close();
        }

    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /**
     * Handles the HTTP <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
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
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>

    private String getWeather(java.lang.String cityName, java.lang.String countryName) {
        // Note that the injected javax.xml.ws.Service reference as well as port objects are not thread safe.
        // If the calling of port operations may lead to race condition some synchronization is required.
        net.webservicex.GlobalWeatherSoap port = service.getGlobalWeatherSoap();
        return port.getWeather(cityName, countryName);
    }

}
