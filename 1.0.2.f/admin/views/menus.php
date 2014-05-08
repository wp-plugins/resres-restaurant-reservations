<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   ResRes
 * @author    Dean Robinson <team@deftdev.com>
 * @license   GPL-2.0+
 * @link      http://deftdev.com
 * @copyright 2014 deftDEV
 */
?>

<?php
//var_dump($_POST);


		if(isset($_POST['resres_save_section'])) {
			$res_save_menu = $this->resres_save_update_sections($_POST);
		}
?>


<div class="wrap">

<noscript><div class="error"><p class="resresnojs">WARNING: JavaScript is DISABLED. ResRes requires JavaScript to be enabled in the admin to function correctly.</p></div></noscript>


	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>


	<form id="resres_menus" action="" method="POST">

		<div>

            <fieldset class="form-actions resres_sections_button_containers" id="resres_new_section_button_container">
				<input class ="button-primary" type="submit" id="resres_new_section" name="resres_new_section" value="<?php echo __( 'New Section') ;?>" />
            </fieldset>

            <fieldset class="form-actions resres_sections_button_containers" id="resres_load_section_button_container">
				<input id="resres_load_section_submit" class="button-primary" type="submit" name="resres_load_section_submit" value="<?php echo __( 'Load Section') ;?>" />
				<select id="resres_load_section_submit" name="resres_load_section_submit">
					<option value=""><?php echo __( 'Select a Section') ;?></option>
					<?php //function for menu list ?>
				</select>
            </fieldset>

            <fieldset class="form-actions resres_sections_button_containers" id="resres_save_section_button_container">
				<input class ="button-primary" type="submit" id="resres_save_section" name="resres_save_section" value="<?php echo __( 'Save Section') ;?>" />
				<input id="resres_section_name" type="text" name="resres_section_name" /><span id="section_name_error"><?php echo __('Please enter a name for this section.'); ?></span>
                <input id="resres_section_id" type="hidden" name="resres_section_id" value="" />
            </fieldset>



		</div>

<hr class="resreshr">



<div id="section1">

            <div id="entry1" class="clonedInput">
<p class="delete_entry">X</p>

                <fieldset class="resres_price">
                    <label class="label_iprice" for="item_price">Price</label><br>
                    <input class="input_iprice" type="text" name="item_iprice" id="item_price" value="">
                </fieldset>

				<fieldset class="resres_name">
                    <label class="label_iname" for="item_name">Item Name</label><br>
                    <input class="input_iname" type="text" name="item_iname" id="item_name" value="">
                </fieldset>

                <fieldset class="resres_desc">
                    <label class="label_idesc" for="item_desc">Description</label><br>
                    <textarea class="input_idesc" name="item_idesc" id="item_desc"></textarea>
                </fieldset>

                <fieldset class="resres_checkbox entrylist">
                    <ul>
                        <li><label><input type="checkbox" id="allergenGluten" value="allergenGluten" name="checkboxitem[]" class="input_checkboxitem allergenGluten"> Gluten free</label></li>
                        <li><label><input type="checkbox" id="allergenLactose" value="allergenLactose" name="checkboxitem[]" class="input_checkboxitem allergenLactose"> Lactose free</label></li>
                        <li><label><input type="checkbox" id="allergenWheat" value="allergenWheat" name="checkboxitem[]" class="input_checkboxitem allergenWheat"> Wheat free</label></li>
                        <li><label><input type="checkbox" id="allergenDairy" value="allergenDairy" name="checkboxitem[]" class="input_checkboxitem allergenDairy"> Dairy free</label></li>
                    </ul>
                    <ul>
                        <li><label><input type="checkbox" id="allergenSugar" value="allergenSugar" name="checkboxitem[]" class="input_checkboxitem allergenSugar"> Sugar free</label></li>
                        <li><label><input type="checkbox" id="allergenPeanuts" value="allergenPeanuts" name="checkboxitem[]" class="input_checkboxitem allergenPeanuts"> Contains peanuts</label></li>
                        <li><label><input type="checkbox" id="allergenTreenuts" value="allergenTreenuts" name="checkboxitem[]" class="input_checkboxitem allergenTreenuts"> Contains tree nuts</label></li>
                        <li><label><input type="checkbox" id="allergenFish" value="allergenFish" name="checkboxitem[]" class="input_checkboxitem allergenFish"> Contains fish</label></li>
                    </ul>
                    <ul>
                        <li><label><input type="checkbox" id="allergenShellfish" value="allergenShellfish" name="checkboxitem[]" class="input_checkboxitem allergenShellfish"> Contains shellfish</label></li>
                        <li><label><input type="checkbox" id="allergenEgg" value="allergenEgg" name="checkboxitem[]" class="input_checkboxitem allergenEgg"> Contains egg</label></li>
                        <li><label><input type="checkbox" id="allergenVegetarian" value="allergenVegetarian" name="checkboxitem[]" class="input_checkboxitem allergenVegetarian"> Vegetarian</label></li>
                        <li><label><input type="checkbox" id="allergenVegan" value="allergenVegan" name="checkboxitem[]" class="input_checkboxitem allergenVegan"> Vegan</label></li>
                    </ul>
                </fieldset>


            </div><!-- end #entry1 -->

            <div id="addDelButtons"  class="addInput">
                <input type="button" id="btnAdd" value="Add New Item">
                <input type="button" id="btnDel" value="Remove Last Item">
            </div>


</div><!-- end section -->

			<hr class="resreshr" />




	</form>

</div>


