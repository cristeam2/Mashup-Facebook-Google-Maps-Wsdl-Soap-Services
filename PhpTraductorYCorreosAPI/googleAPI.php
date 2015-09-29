<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP traductor</title>
    </head>
    <body>
        <h2>PHP Traductor con Google API</h2>
        <?php

        class GoogleTranslate {

            public function __construct($source, $target) {
                $this->source = $source;
                $this->target = $target;
            }

            public function translate($word) {
                $word = urlencode($word);
                $url = 'https://translate.google.com/translate_a/single?client=t&sl=' . $this->source . '&tl=' . $this->target . '&hl=' . $this->target . '-419&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&ie=UTF-8&oe=UTF-8&otf=1&ssel=0&tsel=0&tk=519235|682612&q=' . $word;
                $name_en = $this->curl($url);
                $name_en = explode('"', $name_en);
                return $name_en[1];
            }

            private function curl($url, $params = array(), $is_cookie_set = false) {
                if (!$is_cookie_set) {
                    $ckfile = tempnam("/tmp", "CURLCOOKIE");
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $output = curl_exec($ch);
                    curl_close($ch);
                }
                $str = '';
                $str_arr = array();
                foreach ($params as $key => $value) {
                    $str_arr[] = urlencode($key) . "=" . urlencode($value);
                }
                if (!empty($str_arr))
                    $str = '?' . implode('&', $str_arr);
                $finalUrl = $url . $str;
                $ch = curl_init($finalUrl);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $comment = test_input($_POST["comment"]);

            $idioma = ($_POST["idioma"]);

            if ($idioma == "es")
                $translator = new GoogleTranslate("", "es");
            else
                $translator = new GoogleTranslate("", "en");

            $translation = $translator->translate($comment);

            insertaENBBDD($comment, $translation, $idioma);
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> 
            Texto a traducir:<br> <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea>
            <input type="submit" name="submit" value="Traducir al "> 
            <input type="radio" name="idioma" <?php if (isset($idioma) && $idioma == "en") echo "checked"; ?>  value="en">Ingles
            <input type="radio" name="idioma" <?php if (isset($idioma) && $idioma == "es") echo "checked"; ?>  value="es">espanol

        </form>


<?php
if (!empty($translation))
    echo ($translation);

function insertaENBBDD($comment, $translation, $idioma) {


    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "SCtraductor";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "INSERT INTO palabrasTraducidas (Palabra, Traduccion,IdiomaFinal) VALUES ('$comment', '$translation','$idioma' )";
    if ($conn->query($sql) === TRUE) {
        echo "<script language='javascript'>
            alert('Traduccion:  $translation ,agregada exitosamente a la BBDD');
            
            </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }



    //Cierro la conexion
    $conn->close();
}
?>


    </body>
</html>
