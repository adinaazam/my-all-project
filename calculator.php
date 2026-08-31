<?php
$result = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $expression = trim($_POST["expression"]);

   
    if (!preg_match('/^[0-9+\-*\/().%\s]+$/', $expression)) {
        $error = "Invalid expression! Only numbers and + - * / % ( ) are allowed.";
    } else {

       

        try {

            ob_start();

            $result = @eval("return ($expression);");

            ob_end_clean();

            if ($result === false && $result !== 0) {
                $error = "Invalid mathematical expression.";
            }

        } catch (Throwable $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculation Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <div class="calculator">

        <h1>Calculation Result</h1>

        <?php if ($error != "") { ?>

            <div class="error">
                <?php echo $error; ?>
            </div>

        <?php } else { ?>

            <div class="result">
                <h2>Expression</h2>
                <p><?php echo htmlspecialchars($expression); ?></p>

                <br>

                <h2>Answer</h2>
                <p><?php echo $result; ?></p>
            </div>

        <?php } ?>

        <br>

        <a href="index.html" class="back">
            Back to Calculator
        </a>

    </div>

</div>

</body>
</html>