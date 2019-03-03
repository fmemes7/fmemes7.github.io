<?php $this->load->view('layouts/message_layout'); ?>
<section class="content-header">
  <h2><?php echo $this->lang->line("Package Settings ");?></h2>  
</section>
<section class="content">  
  	<div class="row">
    	<div class="col-xs-12">
        	<div class="grid_container" style="width:100%; height:700px;">
            	<table 
            	id="tt"  
            	class="easyui-datagrid" 
            	url="<?php echo base_url()."admin/packageData"; ?>"            
            	pagination="true" 
            	rownumbers="true" 
            	toolbar="#tb" 
            	pageSize="50" 
            	pageList="[5,10,20,50,100]"  
            	fit= "true" 
            	fitColumns= "true" 
            	nowrap= "true" 
            	view= "detailview"
            	idField="id"
            	>            
                <thead>
                    <tr>
                        <th field="package_id" checkbox="true"></th>                        
                        <th field="id" sortable="true"><?php echo $this->lang->line("Package Id");?></th>                        
                        <th field="package_name" sortable="true"><?php echo $this->lang->line("Package Name");?></th>
                        <th field="price" sortable="true" formatter="priceFormatter"><?php echo $this->lang->line("Price - ");?><?php echo $paymentConfiguration[0]['currency']; ?></th>
                        <th field="validity" sortable="true" formatter="validityFormatter"><?php echo $this->lang->line("Validity - Days");?></th>
                        <th field="is_default" formatter="is_default" sortable="true"><?php echo $this->lang->line("Default Package");?></th>
                        <th field="view" width="100px"  formatter='actionColumn'><?php echo $this->lang->line("Actions");?></th>                    
                    </tr>
                </thead>
            	</table>                        
         	</div>  
       		<div id="tb" style="padding:3px">
            	<a class="btn btn-warning"  href="<?php echo site_url('admin/addPackage');?>">
            	    <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("Add Package");?>
            	</a>              
            	<br/>      
            	<br/>      
        	</div>
    	</div>
  	</div>   
</section>
<script>
	var baseUrl="<?php echo site_url(); ?>";
	function actionColumn(value,row,index)
    {               
        var url=baseUrl+'admin/detailsPackage/'+row.id;        
        var editUrl=baseUrl+'admin/updatePackage/'+row.id;
        var deleteUrl=baseUrl+'admin/deletePackage/'+row.id;
        var more="More info";
        var editStr="Edit";
        var deleteStr="Delete";
        var str="";   
        str="<a class='btn btn-info btn-outline btn-circle btn-lg' data-toggle='tooltip' title='"+more+"' href='"+url+"'>"+"<i class='fa fa-binoculars' style='padding-top: 5px;'></i></a>";
        str=str+"&nbsp;&nbsp;<a class='btn btn-info btn-outline btn-circle btn-lg' data-toggle='tooltip' title='"+editStr+"' href='"+editUrl+"'>"+"<i class='fa fa-edit' style='padding-top: 5px;'></i></a>";
        if(row.is_default=='0')
        str=str+"&nbsp;&nbsp;<a onclick=\"return confirm('"+'<?php echo "are you sure that you want to delete this record?"; ?>'+"')\" class='btn btn-info btn-outline btn-circle btn-lg' data-toggle='tooltip' title='"+deleteStr+"' href='"+deleteUrl+"'>"+"<i class='fa fa-trash' style='padding-top: 5px;'></i></a>";
   		
   		return str;
    }
    function is_default(value,row,index)
    {   
        if(value==1) return "<i class='fa fa-check' style='color:green;'></i>";            
        else return "<i class='fa fa-close' style='color:red;'></i>";     
    }
    function priceFormatter(value,row,index)
    {   
        if(row.is_default=="1" && row.price=="0")
        return "Free"; 
        else return value;  
    }
    function validityFormatter(value,row,index)
    {   
        if(row.is_default=="1" && row.price=="0")
        return "Unlimited"; 
        else return value;    
    }
</script>