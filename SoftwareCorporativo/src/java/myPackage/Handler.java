
package myPackage;

/**
 *
 * @author cristeam2
 */
public class Handler {

    private String name;

    public Handler() {
        name = null;
    }

    /**
     * @return the name
     */
    public String getName() {

        //System.out.println("start");
        //System.out.println(dameWeather("Madrid",""));
        //return dameWeather("Madrid","");    
        return name;
    }
    //private static java.lang.String  dameWeather(java.lang.String cityName, java.lang.String countryName) {
    //  net.webservicex.GlobalWeather service = new net.webservicex.GlobalWeather();
    // net.webservicex.GlobalWeatherSoap port = service.getGlobalWeatherSoap();
    //return port.getWeather(cityName,countryName);
    //}

    /**
     * @param name the name to set
     */
    public void setName(String name) {
        this.name = name;
    }

}
