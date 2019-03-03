<br>
<style>
#recovery_form{text-align:center;}
.row-centered 
{
    text-align:center;
}
.my_back{
  min-height: 140px;
  background: #EEEEEE;
  padding:20px;
  font-size:14px !important;
  font-weight: bold;
}
</style>
<style>#wait{padding-top:20px;padding-left:10px;}</style>
<div class="row" >
  <div class="col-md-3"></div>
  <div class="col-md-6 col-lg-6 col-centered border_gray padded my_back">
    <h6 class="column-title"><i class="fa fa-key fa-2x blue">  Password Recovery</i></h6>
    <hr>
    <div class="account-wall" id='recovery_form'> 
        <div class="form-group">
           <div id='msg'></div>
           <label class="col-xs-12" style="margin-left:0;padding-left:0;">Email</label>
           <input required type="email" class="form-control col-xs-12" id="email" placeholder="Email">
        </div>       
        <div class="form-group">
          <button type="button" id="submit" style="margin-top:20px" class="btn btn-warning btn-lg">Send Recovery Data</button>
          <span id='wait' ></span>  
        </div>
        <a class="btn btn-info btn-lg" href="<?php echo base_url();?>">Go Back</a>     
    </div>
  </div>
</div>

<script type="text/javascript">
$('document').ready(function(){
  $("#submit").click(function(){
    $("#msg").removeAttr('class');
    $("#msg").html("");
    var email=$("#email").val();
    var mobile= $('#mobile').val();
    if(email=='' || mobile == '')
    {
      $("#msg").attr('class','alert alert-warning');
      $("#msg").html("Please enter an email address");
    }
    else
    {
      $("#wait").html("<img src='<?php echo site_url();?>assets/pre-loader/Ovals in circle.gif' height='20' width='20'>");
      $.ajax({
        type:'POST',
        url: "<?php echo site_url();?>main/codeGenaration",
        data:{email:email},
        success:function(response){
          $("#wait").html("");
          if(response=='0')
          {
            $("#msg").attr('class','alert alert-danger');
            $("#msg").html("This email is not associated with any user");
          }
          else
          {
            var string="<div class='well'>"+ 
              "<p>"+
                "<?php echo "An email containing a url and a password recovery code is sent to your email"; ?><br>"+
                "<?php echo "Check your inbox and perform the following steps"; ?>:"+
              "</p>"+
              "<ul style='list-style-type: none;'>"+
                "<li>Go to the provided url</li>"+
                "<li>Enter the provided code</li>"+
                "<li>Reset your password</li>"+
              "</ul>"+
              "<h4>Link and code will be expired after 24 hours</h4>"+
            "</div>";
            $("#recovery_form").slideUp();
            $("#recovery_form").html(string);
            $("#recovery_form").slideDown();
          }
      }
      });
    }
  });
});
</script>
