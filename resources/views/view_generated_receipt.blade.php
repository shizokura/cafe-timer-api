<body id="print_container">
        <input type="hidden" id="code_id_container" value="{{$code->id}}"/>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:10px;">
            ID# {{$code->id}}
        </div>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:10px;">
            Date Generated:</br>{{date('F j, Y, g:i a', strtotime(Carbon\Carbon::parse($code->date_generated)->addHours(8))) }}
        </div>
        </br>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:10px;">
            Price: PHP {{number_format($code->price)}}
        </div>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:10px;">
            Time: {{$code->minutes / 60}} Hour/s
        </div>
        </br>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:12px;">
            Activation Code: <span style=" text-decoration: underline; font-weight: bold;">{{$code->first_code}}</span>
        </div>
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:12px;">
            Pin Code:<span style=" text-decoration: underline; font-weight: bold;">{{$code->second_code}}</span>
        </div>
        </br>
        </br>
        </br>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
myPrint();
function myPrint() 
{

}
</script>
