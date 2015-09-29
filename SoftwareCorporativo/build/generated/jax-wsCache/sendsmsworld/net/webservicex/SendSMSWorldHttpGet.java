
package net.webservicex;

import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.WebResult;
import javax.jws.WebService;
import javax.jws.soap.SOAPBinding;
import javax.xml.bind.annotation.XmlSeeAlso;


/**
 * This class was generated by the JAX-WS RI.
 * JAX-WS RI 2.2.10-b140803.1500
 * Generated source version: 2.2
 * 
 */
@WebService(name = "SendSMSWorldHttpGet", targetNamespace = "http://www.webserviceX.NET")
@SOAPBinding(parameterStyle = SOAPBinding.ParameterStyle.BARE)
@XmlSeeAlso({
    ObjectFactory.class
})
public interface SendSMSWorldHttpGet {


    /**
     * Send unlimited free SMS to following countries.<br><b>Note:</b>If your country code 091,Please enter as 91 and if your mobile number 098XXXXX,Please enter as 98XXXX <br><table cellSpacing=0 cellPadding=5 border=0 border-collapse: collapse bordercolor=#111111><tr> <td  width=50% ><font size=2 ><b>This SMS covers following countries and&nbsp; networks.</b></td>	</tr>	<tr><td  >	<b>Austria -</b><font face=Arial size=-1>	Mobilkom<b><br>	Bulgaria - </b>Mobiltel  <b><br>Croatia -</b> Cronet  <b><br>Germany -</b> E-Plus,Mannesman D2 <b><br>Israel - </b>Pelephone <b><br>Lithuania -</b> Omnitel	<b><br>Maldives - </b>Dhiraagu   <b><br>Norway - </b>NetCom,TeleNor	<b><br>	Switzerland - </b>Bluewin  <b><br>USA - </b>Ameritech Cellular services ,Cellular One,  Cingular <b><br>Brazil - </b>ATL express,Telemig Cellular <b><br>Canada -</b> Bell Mobility, Clearnet, Rogers AT&amp;T Wireless ,Telus <b><br>France - </b>Itineris <b><br>India - </b>Essar Cell Phone,RPG Cellular <b><br>Japan - </b>NTT Docomo <b>	<br>Malaysia - </b>Celcom <b><br>New Zealand - </b>Messagetrack <b><br>Spain -</b> MoviStar <b><br>Ukraine - </b>Golden Telecom, UMC <b><br>USA - </b>Comcast Cellular Communications,Voicestream Wireless Corp. </td></tr>	</table>
     * 
     * @param countryCode
     * @param mobileNumber
     * @param fromEmailAddress
     * @param message
     * @return
     *     returns java.lang.String
     */
    @WebMethod
    @WebResult(name = "string", targetNamespace = "http://www.webserviceX.NET", partName = "Body")
    public String sendSMS(
        @WebParam(name = "string", targetNamespace = "http://www.w3.org/2001/XMLSchema", partName = "FromEmailAddress")
        String fromEmailAddress,
        @WebParam(name = "string", targetNamespace = "http://www.w3.org/2001/XMLSchema", partName = "CountryCode")
        String countryCode,
        @WebParam(name = "string", targetNamespace = "http://www.w3.org/2001/XMLSchema", partName = "MobileNumber")
        String mobileNumber,
        @WebParam(name = "string", targetNamespace = "http://www.w3.org/2001/XMLSchema", partName = "Message")
        String message);

}
