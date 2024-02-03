<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="height: auto; color: #ffffff;">
    <div class="container" 
        style="width: 600px;
            height: auto;
            margin: auto;
            background-color: #1e1e1e;
            padding: 15px;"> 
        <div class="mail-card" 
            style="border: 3px solid #54a9c4;
            border-radius: 8px;
            padding: 26px;">
            <div class="reminder-header" style="text-align: center; margin-bottom: 26px;">
            <div class="title" style="font-size: 35px; font-weight: 600;">It's {{ $name }}'s Birthday</div>
            <div class="subtitle" style="font-size: 16px; color: #54a9c4;">Wish them a happy birthday &#127881</div>
            </div>
            @if ($phone_number || $body)
            <div class="reminder-details" style="border: 2px solid #54a9c4; border-radius: 8px; text-align: justify; padding: 20px 40px;">
                @if ($phone_number)
                <p>Call them on: <span style="color: #54a9c4;">{{ $phone_number }}</span></p>
                @endif
                @if ($body)
                <p>Birthday note: <span style="color: #54a9c4;">{{ $body }}</span></p>
                @endif
            </div>
            @endif
        </div>
    </div>
</body>
</html>