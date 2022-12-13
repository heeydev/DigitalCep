# PHP DIGITAL CEP

Simples buscador de cep

## Utilização

Para utilizar esse buscador basta seguir o exemplo abaixo:

```php

<?php

namespace hiago\DigitalCep;

//CLASSE DESEJADA UTILIZANDO URL BASE VIACEP
class Search {
    private $url = "https://viacep.com.br/ws/";

//FUNÇÃO PARA TESTAR SE A URL DE BUSCA PRINCIPAL ESTÁ ONLINE
function isSiteAvailable($url)
        {
            // CHECAR, SE A URL DISPONIBILIZADA É VÁLIDA
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            // INICIALIZAR CURL
            $curlInit = curl_init($url);

            // SETAR OPÇÕES
            curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curlInit, CURLOPT_HEADER, true);
            curl_setopt($curlInit, CURLOPT_NOBODY, true);
            curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

            // RECEBER A RESPOSTA
            $response = curl_exec($curlInit);

            // FECHAR A SESSÃO COM A URL
            curl_close($curlInit);

            return $response ? true : false;
        }

//TESTAR SE O CEP INSERIDO É VÁLIDO, ATRAVÉS DE ALGUMAS VALIDAÇÕES SIMPLES DE ENTRADA
$len = strlen((string) $zipCode);

        if (!empty($zipCode) && !($len < 8 || $len > 8) && isset($zipCode) && is_numeric($zipCode)) {
            $url = 'https://viacep.com.br';


            //CASO A PRIMEIRA URL FOR VALIDADA, É FEITA A BUSCA DO CEP E É RETORNADO O RESULTADO
            if (isSiteAvailable($url)) {
                $zipCode = preg_replace('/[^0-9]/im', '', $zipCode);
                $resultado = file_get_contents($url . '/ws/' . $zipCode . "/json");

                return (array) json_decode($resultado);

            //CASO A PRIMEIRA URL NÃO SEJA VALIDADA, UMA SEGUNDA TENTATIVA COM A URL SECUNDÁRIA É REALIZADA
            } elseif (!isSiteAvailable($url)) {

                $secondUrl = 'http://cep.la/';

            //SE A SEGUNDA URL FOR VALIDADA, É FEITA A BUSCA DO CEP E É RETORNADO O RESULTADO
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

                //CASO AMBAS AS URLS RETORNEM INVÁLIDAS, RETORNA-SE UM ERRO INTERNO DO SERVIDOR
                else {
                    echo 'Something went wrong! Please contact support. ';
                    echo PHP_EOL;
                    echo 'HTTP/1.1 500 Internal Server Error';
                    exit();
                }
            }
        //CASO O CEP INSERIDO NÃO SEJA VÁLIDO, RETORNA-SE QUE O MÉTODO NÃO É SUPORTADO
        } else {

            echo 'Method not supported';
            echo PHP_EOL;
            echo 'HTTP/1.1 422 Unprocessable Entity';
            exit();
        }

?>
```

```php

<?php

require_once "vendor/autoload.php";

//DEPENDÊNCIAS
use Hiago\DigitalCep\Search;

//INSTÂNCIA
$busca = new Search;

//BUSCA O CEP SOLICITADO
$resultado = $busca->getAddressFromZipcode('01001000');

//RESULTADO DA BUSCA
print_r($resultado);

?>

```

## REQUISITOS
-Necessário PHP 7.2.0 ou superior