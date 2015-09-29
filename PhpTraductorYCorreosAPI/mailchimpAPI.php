<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        date_default_timezone_set('Europe/Madrid');

        //mia
        $list_id = $idListAPI = "5fcaa3f908";
        $apikey = $keyAPI = "0cc099290df20ed27da4417878068861-us10";

        llamarSuscriptos();

        class MailChimp {

            private $api_key;
            private $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0';

            //    private $verify_ssl   = false;
            /**
             * Create a new instance
             * @param string $api_key Your MailChimp API key
             */
            public function __construct($api_key) {
                $this->api_key = $api_key;
                list(, $emailcentre) = explode('-', $this->api_key);
                $this->api_endpoint = str_replace('<dc>', $emailcentre, $this->api_endpoint);
            }

            /**
             * Call an API method. Every request needs the API key, so that is added automatically -- you don't need to pass it in.
             * @param  string $method The API method to call, e.g. 'lists/list'
             * @param  array  $args   An array of arguments to pass to the method. Will be json-encoded for you.
             * @return array          Associative array of json decoded API response.
             */
            public function call($method, $args = array(), $timeout = 10) {
                return $this->makeRequest($method, $args, $timeout);
            }

            /**
             * Performs the underlying HTTP request. Not very exciting
             * @param  string $method The API method to be called
             * @param  array  $args   Assoc array of parameters to be passed
             * @return array          Assoc array of decoded result
             */
            private function makeRequest($method, $args = array(), $timeout = 10) {   //porner apikey en una variable no borrar
                $args['apikey'] = $this->api_key;
                $url = $this->api_endpoint . '/' . $method . '.json';
                $json_data = json_encode($args);
                if (function_exists('curl_init') && function_exists('curl_setopt')) {
                    //// create curl resource 
                    $ch = curl_init();

                    //// set url 
                    curl_setopt($ch, CURLOPT_URL, $url);
                    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    //curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
                    ////return the transfer/response as a string true=1  
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                    //and 0 for a get request
                    curl_setopt($ch, CURLOPT_POST, true);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

                    // $output contains the output string 
                    $result = curl_exec($ch);

                    // close curl resource to free up system resources 
                    curl_close($ch);
                } else {
                    $result = file_get_contents($url, null, stream_context_create(array(
                        'http' => array(
                            'protocol_version' => 1.1,
                            'user_agent' => 'PHP-MCAPI/2.0',
                            'method' => 'POST',
                            'header' => "Content-type: application/json\r\n" .
                            "Connection: close\r\n" .
                            "Content-length: " . strlen($json_data) . "\r\n",
                            'content' => $json_data,
                        ),
                    )));
                }
                return $result ? json_decode($result, true) : false;
            }

        }

        function calculaFechas() {

            global $list_id;
            global $apikey;

            echo "\nDescargando Emails...  iniciado a: " . date('d m Y H:i:s') . "\n<br>";

            $chunk_size = 4096; //in bytes
            $url = 'http://us1.api.mailchimp.com/export/1.0/list?apikey=' . $apikey . '&id=' . $list_id;

            $handle = @fopen($url, 'r');
            if (!$handle) {
                echo "failed to access url\n";
            } else {
                $i = 0;
                $header = array();
                while (!feof($handle)) {
                    $buffer = fgets($handle, $chunk_size);
                    if (trim($buffer) != '') {
                        $obj = json_decode($buffer);
                        if ($i == 0) {
                            //store the header row
                            $header = $obj;
                        } else {
                            var_dump($obj);
                        }
                        $i++;
                    }
                }
                fclose($handle);
            }



            echo "<br>Terminamos " . date('d m Y H:i:s') . "\n";
        }

        function dameNumeroDeSuscriptores($tipoDeSuscriptor, $MailChimp) {

            $consultaAPI = $MailChimp->call('lists/list');
            $encontrado = false;
            $contador = 0;
            while (!$encontrado) {
                if ((($consultaAPI['data'][$contador]['name']) == "test") ||
                        (($consultaAPI['data'][$contador]['name']) == "test" )) {
                    $encontrado = true;
                } else {
                    $contador++;
                }
            }

            $total = ($consultaAPI['data'][$contador]['stats'][$tipoDeSuscriptor]);
            return $total;
        }

        function llamarSuscriptos() {

            global $keyAPI;
            global $idListAPI;

            $MailChimp = new MailChimp($keyAPI);
            $totalDeSuscriptores = dameNumeroDeSuscriptores('member_count', $MailChimp);
            echo "\nCargando Subscribed.... ($totalDeSuscriptores) iniciado a " . date('d m Y H:i:s') . "\n";
            ; //borrar

            $limite = 100; //debido a que la api solo devuelve de 100 en 100 usuarios

            for ($contadorDeCadaCienPaginas = 0; $contadorDeCadaCienPaginas < (ceil($totalDeSuscriptores / 100)); $contadorDeCadaCienPaginas++) {
                echo " " . $contadorDeCadaCienPaginas . " de " . ceil($totalDeSuscriptores / 100);

                $json = ($MailChimp->call('lists/members', array(
                            'apikey' => $keyAPI,
                            'id' => $idListAPI,
                            'status' => 'subscribed',
                            'opts' => array('start' => $contadorDeCadaCienPaginas, "limit" => $limite, 'sort_field' => 'email', "sort_dir" => 'ASC') //DESC
                ))); // *** cambiar la apikey y la id por las verdaderas 
                //Para llegar al maximo de paginas segun la cantidad de usuarios
                if ($contadorDeCadaCienPaginas == ceil($totalDeSuscriptores / 100) - 1) {
                    $limite = $totalDeSuscriptores % 100;
                }

                //Iterar por todos los usuarios de la lista y guardar sus datos en las variables creadas
                for ($contadorCadaUsuario = 0; $contadorCadaUsuario < $limite; $contadorCadaUsuario++) {

                    var_dump($json['data'][$contadorCadaUsuario]['merges']);
                }
            }
            echo "<br>Terminamos " . date('d m Y H:i:s') . "\n";
        }
        ?>
    </body>
</html>
