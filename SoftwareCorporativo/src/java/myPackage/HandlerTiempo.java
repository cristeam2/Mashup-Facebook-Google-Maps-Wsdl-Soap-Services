
package myPackage;

/**
 *
 * @author cristeam2
 */
public class HandlerTiempo {

    private String ciudad;

    public HandlerTiempo() {
        ciudad = null;
    }

    /**
     * @return ciudad
     */
    public String getCiudad() {
        //return ciudad;
        return dameWeather(ciudad, "");
    }

    private static java.lang.String dameWeather(java.lang.String cityName, java.lang.String countryName) {
        net.webservicex.GlobalWeather service = new net.webservicex.GlobalWeather();
        net.webservicex.GlobalWeatherSoap port = service.getGlobalWeatherSoap();
        return port.getWeather(cityName, countryName);
    }

    /**
     * @param ciudad the name to set
     */
    public void setCiudad(String ciudad) {
        this.ciudad = ciudad;
    }

}
