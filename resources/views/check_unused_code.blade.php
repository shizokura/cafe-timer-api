<body>
    @if ($get_code)
        @if($get_code->status == "unused")
            <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:25px; margin-top:60px;">
                Code:{{$get_code->code_id}}
                </br>
                Status: {{$get_code->status}}
                </br>
            </div>
        @elseif($get_code->status == "used")
            <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:25px;">
                Code:{{$get_code->code_id}}
                </br>
                Status: {{$get_code->status}}
                </br>
                </br>
                Used By: </br> {{ $get_code->member_un }}
                </br>
                Date Used: </br>{{ date('M d, Y', strtotime($get_code->used_date)) }} 

            </br>
        @endif
    @else 
        <div style="margin: auto; font-family: 'Arial'; page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:25px; margin-top:35px;">
        Invalid Code
        </br>
    @endif
</body>



