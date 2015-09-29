
package myPackage;

/**
 *
 * @author cristeam2
 */
public class HandlerTraductor {

    private String modo;
    private String mensaje;

    public HandlerTraductor() {
        modo = null;
        mensaje = null;
    }

    public String getTraductor() {

        return traduce(modo, mensaje);
    }

    private static java.lang.String traduce(java.lang.String languageMode, java.lang.String text) {
        net.webservicex.TranslateService service = new net.webservicex.TranslateService();
        net.webservicex.TranslateServiceSoap port = service.getTranslateServiceSoap();
        return port.translate(languageMode, text);
    }

    public void setModo(String modo) {
        this.modo = modo;
    }

    public void setMensaje(String mensaje) {
        this.mensaje = mensaje;
    }

}
