<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="/codes">View Codes</a>
    <a href="javascript:generateCodes()">Generate Codes</a>

    <script>
    function generateCodes()
    {
        let price = prompt('Price per code');
        let minutes = prompt('Minutes per code');
        let quantity = prompt('How many?');

        if (confirm(`Are you sure? ${ price } pesos per ${ minutes } minutes and ${ quantity } pcs? `))
        {
            location.href=`/generate_codes?price=${ price }&minutes=${ minutes }&quantity=${ quantity }`;
        }
    }
    </script>
</body>
</html>