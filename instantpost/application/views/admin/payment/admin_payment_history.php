<?php $this->load->view('layouts/message_layout'); ?>
<?php    
    $view_permission    = 1;
    $edit_permission    = 1;
    $delete_permission  = 1;
?>
<section class="content-header">
  <h1> <?php echo $this->lang->line("Payment History");?> </h1>
</section>
<section class="content">  
  <div class="row">
    <div class="col-xs-12">
        <div class="grid_container" style="width:100%; height:700px;">
            <table 
            id="tt"  
            class="easyui-datagrid" 
            url="<?php echo base_url()."payment/admin_payment_history_data"; ?>"            
            pagination="true" 
            rownumbers="true" 
            toolbar="#tb" 
            pageSize="10" 
            pageList="[5,10,20,50,100]"  
            fit= "true" 
            fitColumns= "true" 
            nowrap= "true" 
            view= "detailview"
            idField="id"
            >            
              <thead>
                <tr>
                  <th field="paypal_email" sortable="true"><?php echo $this->lang->line("Email");?></th>                        
                  <th field="first_name" sortable="true"><?php echo $this->lang->line("First Name");?></th>                        
                  <th field="last_name"  sortable="true" ><?php echo $this->lang->line("Last Name");?></th>
                  <th field="payment_date"  sortable="true"><?php echo $this->lang->line("Payment Date");?></th>
                  <th field="paid_amount" sortable="true" ><?php echo $this->lang->line("Paid Amount - ");?><?php echo $currency; ?></th>
                  <th field="cycle_start_date" sortable="true" ><?php echo $this->lang->line("Cycle Start Date");?></th>
                  <th field="cycle_expired_date" sortable="true" ><?php echo $this->lang->line("Cycle Expire Date");?></th>                  
                </tr>
              </thead>
            </table>                        
         </div>
  
       <div id="tb" style="padding:3px">
            <h4 style="color:olive"><?php echo $this->lang->line("Total Paid amount : ");?><?php echo $total_paid_amount." ".$currency; ?></h4> 
            <form class="form-inline" style="margin-top:20px">
                <div class="form-group">
                    <input id="first_name" name="first_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line("First Name");?>">
                </div> 
                <div class="form-group">
                    <input id="last_name" name="last_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line("Last Name");?>">
                </div> 
                <div class="form-group">
                    <input id="from_date" name="from_date" class="form-control datepicker" size="20" placeholder="<?php echo $this->lang->line("From Date");?>">
                </div>
                <div class="form-group">
                    <input id="to_date" name="to_date" class="form-control  datepicker" size="20" placeholder="<?php echo $this->lang->line("To Date");?>">
                </div>  
                <button class='btn btn-info'  onclick="doSearch(event)"><?php echo $this->lang->line("Search");?></button>
                      
            </form> 
        </div>        
    </div>
  </div>   
</section>
<script>       
  $(function() {
      $( ".datepicker" ).datepicker();
  });    
  
  function doSearch(event)
  {
      event.preventDefault(); 
      $j('#tt').datagrid('load',{
        first_name:       $j('#first_name').val(),
        last_name:        $j('#last_name').val(),
        from_date:        $j('#from_date').val(),
        to_date:          $j('#to_date').val(),
        is_searched:      1
      });
  }  
</script>
