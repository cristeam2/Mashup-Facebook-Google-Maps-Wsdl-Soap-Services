
package myPackage;

import net.webservicex.GeoIP;

/**
 *
 * @author cristeam2
 */
public class HandlerIP {

    private String ip;

    public HandlerIP() {
        ip = null;

    }

    public String getIp() {
        GeoIP descifraEstaIp = conIpDada(ip);

        GeoIP dameMiIp = sinDarIP();
        //System.out.println("tu ip es:"+dameMiIp.getIP()+" y es de: "+traduce(dameMiIp.getCountryName()));

        return "La ip: " + ip + " es de " + descifraEstaIp.getCountryName() + ".</br>Tu ip es " + dameMiIp.getIP() + " y es de " + traduce(dameMiIp.getCountryName());
        //return this.email;
    }

    private static GeoIP conIpDada(java.lang.String ipAddress) {
        net.webservicex.GeoIPService service = new net.webservicex.GeoIPService();
        net.webservicex.GeoIPServiceSoap port = service.getGeoIPServiceSoap();
        return port.getGeoIP(ipAddress);
    }

    private static GeoIP sinDarIP() {
        net.webservicex.GeoIPService service = new net.webservicex.GeoIPService();
        net.webservicex.GeoIPServiceSoap port = service.getGeoIPServiceSoap();
        return port.getGeoIPContext();
    }

    private static String traduce(String pais) {
        if (pais.toLowerCase().equals(("spain"))) {
            pais = "España";
        }
        return pais;
    }

    public void setIp(String ip) {
        this.ip = ip;

    }

}
