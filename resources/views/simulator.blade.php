<!-- Vista simple para simular pagos -->
<!DOCTYPE html>
<html>
<head>
    <title>Simulador de Pagos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Simulador de Pagos</h1>
    <button onclick="simulatePayment(true)">Simular Pago Exitoso</button>
    <button onclick="simulatePayment(false)">Simular Pago Fallido</button>
    <pre id="result"></pre>
    <script>
        function simulatePayment(success) {
            fetch('/api/simulator/pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: 'success=' + (success ? '1' : '0')
            })
            .then(response => response.json().then(data => ({status: response.status, body: data})))
            .then(res => {
                document.getElementById('result').textContent = JSON.stringify(res.body, null, 2);
            })
            .catch(err => {
                document.getElementById('result').textContent = 'Error: ' + err;
            });
        }
    </script>
</body>
</html>
