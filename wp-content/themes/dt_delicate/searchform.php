<!-- **Searchform** -->
<?php $search_text = empty($_GET['s']) ? __("Enter Keyword",'dt_delicate') : get_search_query(); ?> 
<form method="get" id="searchform" action="<?php echo home_url();?>">
    <input id="s" name="s" type="text" 
         	value="<?php echo $search_text;?>" class="text_input"
		    onblur="if(this.value==''){this.value='<?php echo $search_text;?>';}"
            onfocus="if(this.value =='<?php echo $search_text;?>') {this.value=''; }" />
	<input name="submit" type="submit"  value="<?php _e('Go','dt_delicate');?>" />
</form><!-- **Searchform - End** -->