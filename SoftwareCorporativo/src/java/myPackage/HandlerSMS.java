
package myPackage;

/**
 *
 * @author cristeam2
 */
public class HandlerSMS {

    private String email;
    private String telefono;
    private String mensaje;

    public HandlerSMS() {
        email = null;
        telefono = null;
        mensaje = null;
    }

    public String getSms() {

        return enviaSMS(this.email, "34", this.telefono, this.mensaje);
        //return this.email;
    }

    private static java.lang.String enviaSMS(java.lang.String fromEmailAddress, java.lang.String countryCode, java.lang.String mobileNumber, java.lang.String message) {
        net.webservicex.SendSMSWorld service = new net.webservicex.SendSMSWorld();
        net.webservicex.SendSMSWorldSoap port = service.getSendSMSWorldSoap();
        return port.sendSMS(fromEmailAddress, countryCode, mobileNumber, message);
    }

    public void setEmail(String email) {
        this.email = email;

    }

    public void setTelefono(String telefono) {

        this.telefono = telefono;

    }

    public void setMensaje(String mensaje) {
        this.mensaje = mensaje;
    }
}
