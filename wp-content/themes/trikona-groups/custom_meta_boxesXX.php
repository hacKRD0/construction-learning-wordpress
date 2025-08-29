<?php
	add_action("add_meta_boxes", "add_custom_meta_box");
	add_action("save_post","save_custom_meta_box", 10, 3);
	add_action("add_meta_boxes", "add_custom_meta_box1");


	 function add_custom_meta_box(){
	    add_meta_box("demo-meta-box", "Field Type Box","custom_meta_box_markup", "group_meta_data", "normal", "high", null);
	}

	 function custom_meta_box_markup($object){
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");   
	        $optionName =  get_post_meta($object->ID, "group_fileds_optionName", true);
	        $group_fieldType =  get_post_meta($object->ID, "group_fieldType", true);
	       // print_r($optionName);
	     ?>
	        <div>
	            <select  class="form-control input" name="fieldType" >
				<option value="">Please select</option>
				<option value="text" <?php if( $group_fieldType == "text" ): ?> selected="selected"<?php endif; ?>>Text Field</option>
				<option value="select" <?php if( $group_fieldType == "select" ): ?> selected="selected"<?php endif; ?>>Drop Down Select Box</option>
				<option value="checkbox" <?php if( $group_fieldType == "checkbox" ): ?> selected="selected"<?php endif; ?>>Checkboxes</option>
				<option value="radio" <?php if( $group_fieldType == "radio" ): ?> selected="selected"<?php endif; ?>>Radio</option>
	           </select>
	           
	        </div>

	     <div style="width:100%; display:none"  id="dropdownsoptions">
	 Please enter options for this Field:
	            <div class="">
	                <div class="col-lg-12">
	                	<?php foreach ($optionName as $key => $value) {
	                	?>
	                    <div id="row">
	                        <div class="input-group m-3">
	                            <div class="input-group-prepend">
	                                <button  class="btn btn-danger button-1" id="DeleteRow" type="button"> Delete</button>
	                            </div>
	                            <input type="text" class="form-control m-input input" name="optionname[]" value="<?php echo $value;?>">
	                        </div>
	                    </div>
	                <?php } ?>
	 
	                    <div id="newinput"></div>
	                    <button id="rowAdder" type="button"
	                        class="btn btn-dark button button-primary button-large">
	                        <span class="bi bi-plus-square-dotted">
	                        </span> Add Another Option
	                    </button>
	                </div>
	            </div>
	        
	    </div> 
	    <style>  
	.input{width:50%;border:1px solid #aaa; border-radius:4px;margin:8px 0;outline:none;padding:8px;box-sizing:border-box;transition:.3s;}
	   #rowAdder {margin-top: 26px;}
	/* CSS */
	.button-1 {float:right;background-color: #EA4C89;border-radius: 8px; border-style: none;box-sizing: border-box;color: #FFFFFF; font-size: 14px;font-weight: 500;height: 40px;line-height: 20px;}
	</style>
	<script type="text/javascript">
	jQuery(document).ready(function() {
	         <?php if($group_fieldType!="text"){?>
	            jQuery("#dropdownsoptions").show();

	        <?php } ?>
	          });
	     
	 jQuery('select').on('change', function() {
	  if(this.value=="text"){
	  	jQuery("#dropdownsoptions").hide();
	  }else{
	  	jQuery("#dropdownsoptions").show();
	  }
	   if(this.value==""){ jQuery("#dropdownsoptions").hide(); }

	});
	        jQuery("#rowAdder").click(function () {
	            newRowAdd =
	            '<div id="row"> <div class="input-group m-3">' +
	            '<div class="input-group-prepend">' +
	            '<button class="btn btn-danger button-1" id="DeleteRow" type="button">' +
	            '<i class="bi bi-trash"></i> Delete</button> </div>' +
	            '<input type="text" class="form-control m-input input" name="optionname[]"> </div> </div>';
	 
	            jQuery('#newinput').append(newRowAdd);
	        });
	 
	        jQuery("body").on("click", "#DeleteRow", function () {
	            jQuery(this).parents("#row").remove();
	        })
	    </script>    
	    <?php  
	}


	 function save_custom_meta_box($post_id, $post, $update)
	{
	    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
	        return $post_id;

	    if(!current_user_can("edit_post", $post_id))
	        return $post_id;

	    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	        return $post_id;

	    $slug = "group_meta_data";
	    if($slug != $post->post_type)
	        return $post_id;
	       //print_r($_POST["optionname"]);die;

	    if(isset($_POST["optionname"]))
	    {
	        $meta_box_text_value = $_POST["optionname"];
	    }   
	    update_post_meta($post_id, "group_fileds_optionName", $meta_box_text_value);

	    if(isset($_POST["fieldType"]))
	    {
	        $meta_box_dropdown_value = $_POST["fieldType"];
	    }   
	    update_post_meta($post_id, "group_fieldType", $meta_box_dropdown_value);   

	    if(isset($_POST["showin_filters"]))
	    {
	        $meta_box_dropdown_value = $_POST["showin_filters"];
	    }   
	    update_post_meta($post_id, "showin_filters", $meta_box_dropdown_value);   
	}

	 function add_custom_meta_box1()
	{
	    add_meta_box("demo-meta-box1", "filter Type", "custom_meta_box_markup2", "group_meta_data", "side", "high", null);
	}

	 function custom_meta_box_markup2($object)
	{
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

	    ?>
	        <div>
	           

	            <label for="meta-box-checkbox">Show In filter</label>
	            <?php
	                $checkbox_value = get_post_meta($object->ID, "showin_filters", true);

	                if($checkbox_value == "")
	                {
	                    ?>
	                        <input name="showin_filters" type="checkbox" value="true">
	                    <?php
	                }
	                else if($checkbox_value == "true")
	                {
	                    ?>  
	                        <input name="showin_filters" type="checkbox" value="true" checked>
	                    <?php
	                }
	            ?>
	        </div>
	    <?php  
	}
?>