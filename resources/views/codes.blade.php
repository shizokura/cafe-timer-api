<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Timer Backend</title>
</head>
<body>
    <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid;">
        @foreach($codes as $code)
        <div style="display: inline-block; width: max-content; border: 1px solid black; padding: 5px; font-size: 0.6rem; page-break-inside: avoid; break-inside: avoid; margin-bottom: 5px;">
            <div>Activation Code:  <strong>{{ $code->code_id }}</strong></div>
            <div>Pin Code: <strong>{{ $code->pin_code }}</strong></div>
            <div><strong>{{ $code->minutes }}</strong> minutes</div>
            <div>₱ <strong>{{ $code->price }}</strong></div>
        </div>
        @endforeach
    </div>
</body>
</html>