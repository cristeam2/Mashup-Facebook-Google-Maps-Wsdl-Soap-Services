<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php

        class AccessTokenAuthentication {

            function getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl) {
                try {
                    $ch = curl_init();
                    $paramArr = array(
                        'grant_type' => $grantType,
                        'scope' => $scopeUrl,
                        'client_id' => $clientID,
                        'client_secret' => $clientSecret
                    );
                    $paramArr = http_build_query($paramArr);
                    curl_setopt($ch, CURLOPT_URL, $authUrl);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $strResponse = curl_exec($ch);
                    $curlErrno = curl_errno($ch);
                    if ($curlErrno) {
                        $curlError = curl_error($ch);
                        throw new Exception($curlError);
                    }
                    curl_close($ch);
                    $objResponse = json_decode($strResponse);
                    // if($objResponse->error){
                    //     throw new Exception($objResponse->error_description);
                    // }
                    return $objResponse->access_token;
                } catch (Exception $e) {
                    echo "Exception-" . $e->getMessage();
                }
            }

        }

        class HTTPTranslator {

            function curlRequest($url, $authHeader, $postData = '') {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader, "Content-Type: text/xml"));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
                if ($postData) {
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                }
                $curlResponse = curl_exec($ch);
                $curlErrno = curl_errno($ch);
                if ($curlErrno) {
                    $curlError = curl_error($ch);
                    throw new Exception($curlError);
                }
                curl_close($ch);
                return $curlResponse;
            }

            function xmlLanguageCodes($languageCodes) {
                $requestXml = '<ArrayOfstring xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:i="http://www.w3.org/2001/XMLSchema-instance">';
                if (sizeof($languageCodes) > 0) {
                    foreach ($languageCodes as $codes) {
                        $requestXml .= "<string>$codes</string>";
                    }
                } else {
                    throw new Exception('$languageCodes array is empty.');
                }
                $requestXml .= '</ArrayOfstring>';
                return $requestXml;
            }

            function xmlTranslateArray($fromLanguage, $toLanguage, $contentType, $inputStrArr) {
                $requestXml = "<TranslateArrayRequest>" .
                        "<AppId/>" .
                        "<From>$fromLanguage</From>" .
                        "<Options>" .
                        "<Category xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                        "<ContentType xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\">$contentType</ContentType>" .
                        "<ReservedFlags xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                        "<State xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                        "<Uri xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                        "<User xmlns=\"http://schemas.datacontract.org/2004/07/Microsoft.MT.Web.Service.V2\" />" .
                        "</Options>" .
                        "<Texts>";
                foreach ($inputStrArr as $inputStr) {
                    $requestXml .= "<string xmlns=\"http://schemas.microsoft.com/2003/10/Serialization/Arrays\">$inputStr</string>";
                }
                $requestXml .= "</Texts>" .
                        "<To>$toLanguage</To>" .
                        "</TranslateArrayRequest>";
                return $requestXml;
            }

        }

        function get_language_names($locale = "en") {
            global $list, $apis;
            try {
                $clientID = $apis['azure']['id'];
                $clientSecret = $apis['azure']['key'];
                $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
                $scopeUrl = "http://api.microsofttranslator.com";
                $grantType = "client_credentials";
                $authObj = new AccessTokenAuthentication();
                $accessToken = $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
                $authHeader = "Authorization: Bearer " . $accessToken;
                foreach ($list['translate'] as $k => $iso) {
                    $languageCodes[] = $k;
                }
                $url = "http://api.microsofttranslator.com/V2/Http.svc/GetLanguageNames?locale=$locale";
                $translatorObj = new HTTPTranslator();
                $requestXml = $translatorObj->xmlLanguageCodes($languageCodes);
                $curlResponse = $translatorObj->curlRequest($url, $authHeader, $requestXml);
                $xmlObj = simplexml_load_string($curlResponse);
                $i = 0;
                foreach ($xmlObj->string as $language) {
                    $result[$languageCodes[$i]] = (string) $language;
                    $i++;
                }
                return $result;
            } catch (Exception $e) {
                echo "Exception: " . $e->getMessage() . PHP_EOL;
            }
        }

        function get_translate($array, $from, $to, $html = false) {
            global $apis;

            try {
                $clientID = $apis['azure']['id'];
                $clientSecret = $apis['azure']['key'];
                $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
                $scopeUrl = "http://api.microsofttranslator.com";
                $grantType = "client_credentials";
                $authObj = new AccessTokenAuthentication();
                $accessToken = $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
                $authHeader = "Authorization: Bearer " . $accessToken;

                $fromLanguage = $from;
                $toLanguage = $to;
                $inputStrArr = array_values($array);
                if ($html) {
                    $contentType = 'text/html';
                    foreach ($inputStrArr as $k => $item) {
                        $inputStrArr[$k] = htmlentities($inputStrArr[$k]);
                    }
                } else {
                    $contentType = 'text/plain';
                }

                $translatorObj = new HTTPTranslator();
                $requestXml = $translatorObj->xmlTranslateArray($fromLanguage, $toLanguage, $contentType, $inputStrArr);
                $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/TranslateArray";
                $curlResponse = $translatorObj->curlRequest($translateUrl, $authHeader, $requestXml);

                $xmlObj = simplexml_load_string($curlResponse);
                foreach ($xmlObj->TranslateArrayResponse as $translatedArrObj) {
                    $result[] = (string) $translatedArrObj->TranslatedText;
                }
                return $result;
            } catch (Exception $e) {
                echo "Exception: " . $e->getMessage() . PHP_EOL;
            }
        }

        function get_translate_single($input, $from, $to, $html = false) {
            $apis = array();
            $apis['azure']['id'] = "97f4379a-eaf1-4566-b80e-17cdda5afdd5";
            $apis['azure']['key'] = 'aH9Zj5/ok8x3xjw6SznVW79ZWG5F8V5U9afeDrgF8ow=';

            try {
                //$clientID = $apis['azure']['id'];
                //$clientSecret = $apis['azure']['key'];
                $clientID = '97f4379a-eaf1-4566-b80e-17cdda5afdd5';
                $clientSecret = 'zsWEVbo9/18WK7VH6rK3qSh6sjd0m1CRU6tFTQ0iOqw=';

                $authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
                $scopeUrl = "http://api.microsofttranslator.com";
                $grantType = "client_credentials";
                $authObj = new AccessTokenAuthentication();
                $accessToken = $authObj->getTokens($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);
                $authHeader = "Authorization: Bearer " . $accessToken;

                $fromLanguage = $from;
                $toLanguage = $to;
                if ($html) {
                    $contentType = 'text/html';
                    $input = htmlentities($input);
                } else {
                    $contentType = 'text/plain';
                }

                # params
                $params = "text=" . urlencode($input) . "&to=" . $toLanguage . "&from=" . $fromLanguage;
                $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?" . $params;

                # object
                $translatorObj = new HTTPTranslator();
                $curlResponse = $translatorObj->curlRequest($translateUrl, $authHeader);

                # interprets a string of XML into an object.
                $xmlObj = simplexml_load_string($curlResponse);
                foreach ((array) $xmlObj[0] as $val) {
                    $result = $val;
                }
                return $result;
            } catch (Exception $e) {
                echo "Exception: " . $e->getMessage() . PHP_EOL;
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $idioma = "es";
        $comment = "";
        $traduccion = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $comment = test_input($_POST["comment"]);

            $idioma = ($_POST["idioma"]);

            if ($idioma == "es")
                $traduccion = get_translate_single($comment, 'en', 'es');
            else
                $traduccion = get_translate_single($comment, 'en', 'es');
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
            Palabra a traducir :<br> <textarea name="comment" cols="16"><?php echo $comment; ?></textarea>
            <input type="submit" name="submit" value="Traducir al "> 
            <input type="radio" name="idioma" <?php if (isset($idioma) && $idioma == "en") echo "checked"; ?>  value="en">Ingles
            <input type="radio" name="idioma" <?php if (isset($idioma) && $idioma == "es") echo "checked"; ?>  value="es">espanol

        </form>

        <?php
        if (!empty($traduccion))
            echo "<br>La traduccion es: " . $traduccion;
        ?>
    </body>
</html>
