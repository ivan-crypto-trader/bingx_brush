function getBalance() {
  global $apiKey;

  // interface info
  $path = "/api/v1/user/getBalance";
  $method = "POST";

  // interface params
  $params = array();
  $params['currency'] = 'USDT';
  $params['apiKey'] = $apiKey;
  $date = new DateTime();
  $params['timestamp'] = $date->getTimestamp()*1000;

  // sort params
  ksort($params);

  // generate signature
  $originString = getOriginString($method, $path, $params);
  $signature = getSignature($originString);
  $params["sign"] = $signature;

  // send http request
  $requestUrl = getRequestUrl($path, $params);
  $result = httpPost($requestUrl);

  // parse response
  $response = json_decode($result, true);

  // generate HTML table
  echo "<table>";
  echo "<tr><th>Currency</th><th>Balance</th></tr>";
  foreach ($response['data'] as $balance) {
    echo "<tr><td>{$balance['currency']}</td><td>{$balance['balance']}</td></tr>";
  }
  echo "</table>";
}

<html>
<head>
  <title>Brush</title>
</head>
<body>
  <?php getBalance(); ?>
  <div id="price"></div>
    <script>
      const socket = new WebSocket('wss://stream.binance.com:9443/ws/btcusdt@ticker');

      socket.onmessage = event => {
        const data = JSON.parse(event.data);
        document.getElementById('price').innerHTML = data.c;
      };
    </script>
    <style>
      #price {
        font-size: 36px;
        font-weight: bold;
        color: #000;
        text-align: center;
        margin-top: 50px;
      }
    </style>
</body>
</html>
