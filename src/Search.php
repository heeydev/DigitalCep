<?php

namespace hiago\DigitalCep;

class Search
{

    public function getAddressFromZipcode(string $zipCode)
    {
        function isSiteAvailable($url)
        {
            // Check, if a valid url is provided
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            // Initialize cURL
            $curlInit = curl_init($url);

            // Set options
            curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curlInit, CURLOPT_HEADER, true);
            curl_setopt($curlInit, CURLOPT_NOBODY, true);
            curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

            // Get response
            $response = curl_exec($curlInit);

            // Close a cURL session
            curl_close($curlInit);

            return $response ? true : false;
        }
        

        $len = strlen((string) $zipCode);

        if (!empty($zipCode) && !($len < 8 || $len > 8) && isset($zipCode) && is_numeric($zipCode)) {
            $url = 'https://viacep.com.br';

            if (isSiteAvailable($url)) {
                $zipCode = preg_replace('/[^0-9]/im', '', $zipCode);
                $resultado = file_get_contents($url . '/ws/' . $zipCode . "/json");

                return (array) json_decode($resultado);
            } elseif (!isSiteAvailable($url)) {

                $secondUrl = 'http://cep.la/';

                if (isSiteAvailable($secondUrl)) {

                    $url = [
                        "http" => [
                            "method" => "GET",
                            "header" => "Accept: application/json\r\n"
                        ]
                    ];

                    $context = stream_context_create($url);
                    $resultado = file_get_contents($secondUrl . $zipCode, false, $context);

                    return (array) json_decode($resultado);
                }
                else {
                    echo 'Something went wrong! Please contact support. ';
                    echo PHP_EOL;
                    echo 'HTTP/1.1 500 Internal Server Error';
                    exit();
                }
            }
        } else {

            echo 'Method not supported';
            echo PHP_EOL;
            echo 'HTTP/1.1 422 Unprocessable Entity';
            exit();
        }
    }
}

?>