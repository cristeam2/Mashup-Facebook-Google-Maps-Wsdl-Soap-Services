
package myPackage;

/**
 *
 * @author cristeam2
 */
public class HandlerCiudades {

    private String ciudad;

    public HandlerCiudades() {
        ciudad = null;
    }

    public String getCiudad() {

        return dameCiudades(ciudad);
    }

    private static java.lang.String dameCiudades(java.lang.String countryName) {
        net.webservicex.GlobalWeather service = new net.webservicex.GlobalWeather();
        net.webservicex.GlobalWeatherSoap port = service.getGlobalWeatherSoap();
        return port.getCitiesByCountry(countryName);
    }

    public void setCiudad(String ciudad) {
        this.ciudad = ciudad;
    }

}
