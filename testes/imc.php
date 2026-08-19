<html>
    <head>
        <title>Calculadora de IMC</title>
    </head>
    <body>
        <h1>Calculadora de IMC</h1>
        <form method="post" action="">
            <label for="peso">Peso (kg):</label>
            <input type="number" id="peso" name="peso" step="0.01" required><br><br>

            <label for="altura">Altura (m):</label>
            <input type="number" id="altura" name="altura" step="0.01" required><br><br>

            <input type="submit" value="Calcular IMC">
        </form>

        <?php
        if ($_POST) {
            $peso = $_POST['peso'];
            $altura = $_POST['altura'];
            $imc = $peso / ($altura * $altura);
            echo "<p>Seu IMC é: " . number_format($imc, 2) . "</p>";
        }
        ?>
    </body>
</html>