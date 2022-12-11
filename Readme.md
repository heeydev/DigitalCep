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

//FUNÇÃO DE BUSCA
    public function getAddressFromZipcode(string $zipCode): array {
        $zipCode = preg_replace('/[^0-9]/im', '', $zipCode);

        $resultado = file_get_contents($this->url .$zipCode ."/json");

//RETORNO COM AS INFORMAÇÕES EM ARRAY
        return (array) json_decode($resultado);
    }
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