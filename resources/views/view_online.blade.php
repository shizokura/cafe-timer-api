
<div class="code_checker">
  <input type="number" class="codeHours" value="1" style="height:50px;font-size:14pt;"></input>
  </br>
  <div style="margin-left:80px; margin-top:5px;">
    <button id="myBtn2">Generate Code Receipt</button>
  </div>
</div>

<div class="code_checker">
  <input type="text" class="codeValue" style="height:50px;font-size:14pt;"></input>
  </br>
  <div style="margin-left:148px; margin-top:5px;">
    <button id="myBtn">Check Code</button>
  </div>
</div>


<div id="myModal" class="modal">

<!-- Modal content -->
<div class="modal-content">
  <span class="close" id="closeModalBtn">&times;</span>
   <div class="modal-container">
    
   </div>
</div>

</div>

<div id="print_div">
    <div id="hidden_div">
        
    </div>
</div>

<div class="container">
  <h1>Current Online : {{$count}}<h1>
  <table style="width:100%; font-size:30px;">
    <tr>
      <th>Username</th>
      <th>Time Remaining</th>
      <th>Last Code Used</th>
      <th>Last Date Code</th>
      <th>Points</th>
      <th>Multiple</th>
    </tr>
    @foreach ($get_member as $gm)
      <tr>
        <td class="{{ $gm->is_new == 1 ? 'do_green' : ''}}">{{$gm->member_un}}</td>
        <td>{{date('H', mktime(0,$gm->remaining_minutes)) != 0 ? date('H', mktime(0,$gm->remaining_minutes)) . ' hours' : ''}} {{date('i', mktime(0,$gm->remaining_minutes))}} minutes </td>
        <td class="boxhead"><a href="/view_members_code?member_id={{$gm->member_id}}" target="_blank">{{$gm->code_id}}</a></td>
        <td>{{ $gm->date_last_use != "None" ? date("M d h:i:s A",strtotime($gm->date_last_use)) : "None" }} </td>
        <td class="boxhead"><a href="/view_member_points?member_id={{$gm->member_id}}" target="_blank">{{number_format($gm->points,2)}}</a></td>
        <td class="boxhead">{{$gm->is_multiple_user == "true" ? "Yes" : "No"}}</a></td>
      </tr>
    @endforeach
  </table>

  </br>

  <h1>Latest Code<h1>
  <table style="width:100%; font-size:30px;">
    <tr>
      <th>Code</th>
      <th>Time</th>
      <th>Used By</th>
      <th>Date Generated</th>
    </tr>
    @foreach ($get_latest_code_receipt as $gl)
      <tr>
        <td>W{{$gl->id}}***</td>
        <td>{{$gl->minutes}} {{$gl->minutes > 1 ? 'minutes' : 'minute'}}</td>
        <td>{{ $gl->member_un ? $gl->member_un : '------'}}</td>
        <td>{{ $gl->date_generated != "None" ? date('F j, Y, g:i a', strtotime(Carbon\Carbon::parse($gl->date_generated)->addHours(8))) : "None" }} </td>
      </tr>
    @endforeach
  </table>
  
  </br>
  </br>
  </br>
  </br>
  </br>

  <h1>Duplicate </h1>
  <table style="width:100%; float:left">
    <tr>
      <th>Pin Code</th>
      <th>Activation Code</th>
      <th>Times Used</th>
      <th>View</th>
    </tr>
    @foreach ($get_duplicate_code_viewed as $gdc)
      <tr>
        <td>{{$gdc->pin_code}}</td>    
        <td>{{$gdc->activation_code}}</td>    
        <td>{{$gdc->total}}</td>    
        <td><h2><a href="/view_duplicate_code?code_id={{$gdc->code_id}}" target="_blank">View</a><h2></td>   
      </tr>
    @endforeach
  </table>

  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>

  <table style="width:49%; float:left">
    <tr>
      <th>Pin Code</th>
      <th>Activation Code</th>
      <th>Times Used</th>
      <th>Date Used</th>
      <th>View</th>
    </tr>
    @foreach ($get_duplicate_code as $gdc)
      <tr>
        <td>{{$gdc->pin_code}}</td>    
        <td>{{$gdc->activation_code}}</td>    
        <td>{{$gdc->total}}</td>    
        <td>{{$gdc->used_date}}</td>    
        <td><a href="/view_duplicate_code?code_id={{$gdc->code_id}}" target="_blank">View</a></td>   
      </tr>
    @endforeach
  </table>



  <table style="width:49%; float:right">
    <tr>
      <th>Username</th>
      <th>Amount</th>
      <th>Times Used</th>
      <th>View</th>
    </tr>
    @foreach ($get_duplicate_points as $gdp)
      <tr>
        <td>{{$gdp->member_un}}</td>      
        <td>{{$gdp->amount}}</td>    
        <td>{{$gdp->total}}</td>    
        <td><a href="/view_member_points?member_id={{$gdp->member_id}}" target="_blank">View</a></td>   
      </tr>
    @endforeach
  </table>
</div>
<style type="text/css">
table, th, td 
{
  border: 1px solid black;
  border-collapse: collapse;
  text-align: left;
}
.boxhead a {
    color: black;
    text-decoration: none;
}
.do_green{
    color: #00008B;
    font-weight: bold;
    font-style: italic;
    text-decoration: none;
}

.code_checker
{
  
}
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  height:200px;
  border: 1px solid #888;
  width: 30%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
  
setInterval(myMethod, 3000);
whenKeyboardPressed();
var is_preview  = false;
var is_ongoing  = false;
var second_type = false;
function myMethod( )
{
  $(".container").load(location.href + " .container");
   // alert(1);
}

function whenKeyboardPressed()
{
    document.addEventListener('keypress', (event)=>
    {
      const isNumber = /^[0-9]$/i.test(event.key)
      if(isNumber)
      {
        if(second_type == true)
        {

        }
        else
        {
          $('.codeHours').val("");
          second_type = true;
        }

        if(is_preview == false && is_ongoing == false)
        {
          $('.codeHours').focus();
        }
      }
    });
    
    document.addEventListener('keyup', (event)=>
    {

      // event.keyCode or event.which  property will have the code of the pressed key
      let keyCode = event.keyCode ? event.keyCode : event.which;
      
      // 13 points the enter key
      if(keyCode === 13) 
      {
          if(onModal == true)
          {
            if(is_preview == true && is_ongoing == false)
            {
              let codeValue = $(".codeHours").val();
                  codeValue = parseInt(codeValue);
              let data = codeValue;
              let setData = {};
                  setData.quantity = codeValue;
              $(".modal-container").load("/topup_preview?quantity="+codeValue+"&wait=1");
              is_ongoing = true;
              $.ajax({
                url:'/generate_codes_receipt',
                method: 'GET',
                dataType: 'json',
                data: setData,
                success: function (response) 
                {
                  id = response;
                  is_ongoing = false;
                  // $("#hidden_div").load("/view_generate_code_receipt?id="+id);
                  setHtmlPrint(response);
                  closeModal();
                  // myPrint(id);
                }});
            }
            else if(is_ongoing)
            {
              alert("Please wait...");
            }
            else
            {
              breakAutoRun = true;
              closeModal();
            }
          }
          else
          {
            $('#myBtn2').click();
          }
      }

        //ESCAPE KEY = 27
        if (keyCode === 27) 
        { 
          closeModal();
        }
    });
}

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var breakAutoRun = false;
var onModal = false;

btn2.onclick = function() 
{

  let codeValue = $(".codeHours").val();
      codeValue = parseInt(codeValue);
  $(".codeHours").blur();

  if(Number.isInteger(codeValue) && codeValue >= 1)
  {
    onModal = true;
    modal.style.display = "block";
    is_preview = true;
    is_ongoing = false;
    $(".modal-container").load("/topup_preview?quantity="+codeValue);
  }
  else
  {
    alert("Invalid Quantity");
  }
}


// When the user clicks on the button, open the modal
btn.onclick = function() 
{
  $(".codeValue").blur();
  onModal = true;
  modal.style.display = "block";
  let codeValue = $(".codeValue").val();
  is_preview = true;
  $(".modal-container").load("/check_unused_code?code_id="+codeValue);
  runAutoCloseModal();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  breakAutoRun = true;
  onModal = false;
  is_preview = false;
  second_type = false;
  $(".codeHours").focus();
  $('.codeValue').val("");
  $('.codeHours').val(1);
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    breakAutoRun = true;
    onModal = false;
    is_preview = false;
    second_type = false;
    $(".codeHours").focus();
    $('.codeValue').val("");
    $('.codeHours').val(1);
  }
}

function runAutoCloseModal()
{
  // var timeleft = 30;
  // var downloadTimer = setInterval(function(){
  //   if(timeleft <= 0)
  //   {
  //     closeModal();
  //     clearInterval(downloadTimer);
  //   }
  //   if(breakAutoRun == true)
  //   {
  //     clearInterval(downloadTimer);
  //     breakAutoRun = false;
  //     onModal = false;
  //   }
  //   timeleft -= 1;
  // }, 1000);
}

// $('.codeValue').on('keyup', function(e) {
//     if (e.keyCode === 13) 
//     {
//         $('#myBtn').click();
//     }
// });

function closeModal()
{ 
    modal.style.display = "none";
    breakAutoRun = true;
    onModal = false;
    is_preview = false;
    second_type = false;
    $('.codeValue').val("");
    $('.codeHours').val(1);
};


function setHtmlPrint(codes)
{
  let code = codes.code;

  var string_html = "";
      string_html = string_html +'<body id="print_container">'
      string_html = string_html + '<div style="margin: auto;  page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:13px;">'
      string_html = string_html + '    Time:'+codes.time+" Hour/s"
      string_html = string_html + '</div>'
      string_html = string_html + '<div style="margin: auto;  page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:15px;">'
      string_html = string_html + '    Activation Code: <span style=" text-decoration: underline; font-weight: bold;">'+code.first_code+'</span>'
      string_html = string_html + '</div>'
      string_html = string_html + '<div style="margin: auto;  page-break-inside: avoid; break-inside: avoid; text-align: center; font-size:15px;">'
      string_html = string_html + '    Pin Code:<span style=" text-decoration: underline; font-weight: bold;">'+code.second_code+'</span>'
      string_html = string_html + '</div>'
      string_html = string_html + '</br>'
      string_html = string_html + '</br>'
      string_html = string_html + '</br>'
      string_html = string_html + '</body>'


      var openWindow = window.open("", "title", "attributes");
          openWindow.document.write(string_html);
          openWindow.document.close();
          openWindow.focus();
          openWindow.print();
          setInterval(function()
          {
            openWindow.close();
          }, 1000);
}

</script>
