<body>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:25px;">
            Time Add: {{$add_time/60 }} Hour/s
        </div>
        </br>
        @if($wait)
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:25px;">
            Processing Please Wait...
        </div>
        @else
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:25px;">
            Press enter to generate
        </div>
        @endif
        </br>
</body>



