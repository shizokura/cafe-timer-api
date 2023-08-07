<div class="code_checker">
  <input type="text" class="codeValue" style="height:50px;font-size:14pt;"></input>
  </br>
  <div style="margin-left:148px; margin-top:5px;">
    <button id="myBtn">Check Code</button>
  </div>
  <div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <span class="close" id="closeModalBtn">&times;</span>
       <div class="modal-container">
        
       </div>
    </div>

  </div>
</div>

<div class="container">
  <h1>Current Online : {{$count}}<h1>
  <table style="width:100%; font-size:30px;">
    <tr>
      <th>Username</th>
      <th>Time Remaining</th>
      <th>Last 3 Code Used</th>
      <th>Last Date Code</th>
      <th>Points</th>
      <th>Multiple</th>
    </tr>
    @foreach ($get_member as $gm)
      <tr>
        <td class="{{ $gm->is_new == 1 ? 'do_green' : ''}}">{{$gm->member_un}}</td>
        <td>{{date('H', mktime(0,$gm->remaining_minutes)) != 0 ? date('H', mktime(0,$gm->remaining_minutes)) . ' hours' : ''}} {{date('i', mktime(0,$gm->remaining_minutes))}} minutes </td>
        <td class="boxhead"><a href="/view_members_code?member_id={{$gm->member_id}}" target="_blank">{{$gm->code_id}}</a></td>
        <td>{{ $gm->date_last_use != "None" ? date("M m h:i:s A",strtotime($gm->date_last_use)) : "None" }} </td>
        <td class="boxhead"><a href="/view_members_code?member_id={{$gm->member_id}}" target="_blank">{{number_format($gm->points,2)}}</a></td>
        <td class="boxhead">{{$gm->is_multiple_user == "true" ? "Yes" : "No"}}</a></td>
      </tr>
    @endforeach
  </table>

  </br>
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
        $('.codeValue').focus();
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
             breakAutoRun = true;
             closeModal();
          }
          else
          {
            $('#myBtn').click();
          }
      }
    });
}


// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var breakAutoRun = false;
var onModal = false;
// When the user clicks on the button, open the modal
btn.onclick = function() 
{
  $(".codeValue").blur();
  onModal = true;
  modal.style.display = "block";
  let codeValue = $(".codeValue").val();
  $(".modal-container").load("/check_unused_code?code_id="+codeValue);
  runAutoCloseModal();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  breakAutoRun = true;
  onModal = false;
  $(".codeValue").focus();
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    breakAutoRun = true;
    onModal = false;
  }
}

function runAutoCloseModal()
{
  var timeleft = 30;
  var downloadTimer = setInterval(function(){
    if(timeleft <= 0)
    {
      closeModal();
      clearInterval(downloadTimer);
    }
    if(breakAutoRun == true)
    {
      clearInterval(downloadTimer);
      breakAutoRun = false;
      onModal = false;
    }
    timeleft -= 1;
  }, 1000);
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
    $('.codeValue').val("");
};


</script>
