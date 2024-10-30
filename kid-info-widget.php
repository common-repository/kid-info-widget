<?php
/*
 * Plugin Name: Kid Info Widget
 * Version: 0.9
 * Plugin URI: http://mike.vanvendeloo.net/wordpress-kid-info-widget
 * Description: Developed to be able to display age, weight, length and other information about one or more kids in the widget area's.
 * Author: Mike van Vendeloo
 * Author URI: http://mike.vanvendeloo.net
 */

/*
 This widget was developed for my sister-in-law, so she could display some information and a picture of her kids on her Wordpress site.
*/

add_option("kid_info_widget_version", "0.9");
add_action('init', 'add_kid_info_style');
add_action('widgets_init', 'KidInfoInit');

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( "kid-info-widget", null, $plugin_dir );

if(!load_plugin_textdomain('kid-info-widget','/wp-content/languages/')) {
	load_plugin_textdomain('kid-info-widget','/wp-content/plugins/kid-info-widget/');
}


class KidInfoWidget extends WP_Widget
{
 /**
  * Declares the KidInfoWidget class.
  *
  */
    function KidInfoWidget(){
      $widget_ops = array('classname' => 'kid_info_widget', 'description' => __( "Widget to display age, weight, length and other information about a child.", 'kid-info-widget') );
      /*$control_ops = array('width' => 300, 'height' => 300);*/
      $control_ops = array();

      $current_version = get_option('kid_info_widget_version', '0.9');

      $this->WP_Widget('KidInfoWidget', __('Kid Info Widget', 'kid-info-widget'), $widget_ops, $control_ops);
    }

  /**
    * Displays the Widget
    *
    */
    function widget($args, $instance){
      extract($args);
      $name = apply_filters('widget_title', empty($instance['name']) ? '&nbsp;' : $instance['name']);
      $content = $instance['content'];
      $date_of_birth = $instance['date_of_birth'];
      $time_of_birth = $instance['time_of_birth'];
      $birth_weight = $instance['birth_weight'];
      $birth_length = $instance['birth_length'];
      $current_weight = $instance['current_weight'];
      $current_length = $instance['current_length'];
      $show_birth_weight = $instance['show_birth_weight'];
      $show_birth_length = $instance['show_birth_length'];
      $show_current_weight = $instance['show_current_weight'];
      $show_current_length = $instance['show_current_length'];
      $show_age_weeks = $instance['show_age_weeks'];
      $photo_url =$instance['photo_url'];
      $photo_width = $instance['photo_width'];
      $lastupdated = $instance['lastupdated'];
      if($photo_width=="") {
          $photo_width = 75;
      }

      # Before the widget
      echo $before_widget;

      # The name of the child is used as a title
      echo $before_title . $name . $after_title;

      # get current date
      $now = mktime();     // Today's date.
      if($time_of_birth=='') {
	 //probably the time is unknown or unimportant
	 $time_of_birth="00:00";
      }
      $birthDateString = $date_of_birth." ".$time_of_birth.":00";
      $birthDate = strtotime($birthDateString);
      $nrWeeksPassed = calculateWeeks($birthDate);
      $age = calculateTimeDiff(new DateTime("$birthDateString"));
      $customstyle="kidinfo";
      if($instance['customstyle'] != '') {
		$customstyle=$customstyle." ".$instance['customstyle'];
      }
      echo '<div class="'.$customstyle.'">';
      if($photo_url != '') {
          echo "<div class='kidinfo_photo'><img src='".$photo_url."' width='".$photo_width."' class='kidinfo_thumbnail' /></div>";
      }
      echo "<div class='kidinfo_info'><span class='kidinfo_label'>".__('Born on','kid-info-widget').":</span><br>".date("d-m-Y H:i", $birthDate)."<br>";
      if($show_birth_weight!='') {
          echo "<span class='kidinfo_label'>".__('Birth weight','kid-info-widget').":</span><br>".$birth_weight.__('gram','kid-info-widget')."<br>";
      }
      if($show_birth_length!='') {
         echo "".$birth_length.__('cm','kid-info-widget')."<br>";
      }
      echo "<span class='kidinfo_label'>".__('Age', 'kid-info-widget').":</span>";
      if($age['year'] > 0) {
         echo $age['year']." ".__('year', 'kid-info-widget').", ";
      }
      if($age['month'] > 0) {
         echo $age['month']." ".__('months', 'kid-info-widget').", ";
      }
      if($age['day'] > 0) {
        echo $age['day']." ".__('days', 'kid-info-widget');
      }
      if($show_age_weeks!='') {
		echo " (".$nrWeeksPassed." ".__('weeks', 'kid-info-widget').")";
      }
      echo "<br>";
      if($show_current_weight!='') {
          echo "<span class='kidinfo_label'>".__('Weight','kid-info-widget').":</span> ".$current_weight.__('gram','kid-info-widget')."<br>";
      }
      if($show_current_length!='') {
          echo "<span class='kidinfo_label'>".__('Length','kid-info-widget').":</span>".$current_length.__('cm','kid-info-widget')."<br>";
      }
     
      if($lastupdated!="") {
          echo $lastupdated->format('Y-m-d')." ".__('lastupdated', 'kid-info-widget');
      }
	  echo "</div>";
      echo "</div>";
      # Now show the content of the widget...
      echo "<div class='kidinfo_content ".$customstyle."'>".$content."</div>";
      echo "</div>";
      # After the widget
      echo $after_widget;
  }

  /**
    * Saves the widgets settings.
    *
    */
    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['name'] = strip_tags(stripslashes($new_instance['name']));
      // we want to allow people to put html in this field
      $instance['content'] = $new_instance['content'];
      $instance['date_of_birth'] = strip_tags(stripslashes($new_instance['date_of_birth']));
      $instance['time_of_birth'] = strip_tags(stripslashes($new_instance['time_of_birth']));
      $instance['birth_weight'] = strip_tags(stripslashes($new_instance['birth_weight']));
      $instance['birth_length'] = strip_tags(stripslashes($new_instance['birth_length']));
      $instance['current_weight'] = strip_tags(stripslashes($new_instance['current_weight']));
      $instance['current_length'] = strip_tags(stripslashes($new_instance['current_length']));
	  $show_age_weeks = strip_tags(stripslashes($new_instance['show_age_weeks']));
      $instance['show_birth_weight'] = strip_tags(stripslashes($new_instance['show_birth_weight']));
      $instance['show_birth_length'] = strip_tags(stripslashes($new_instance['show_birth_weight']));
      $instance['show_current_weight'] = strip_tags(stripslashes($new_instance['show_current_weight']));
      $instance['show_current_length'] = strip_tags(stripslashes($new_instance['show_current_length']));
      $instance['photo_url'] = $new_instance['photo_url'];
      $instance['photo_width'] = strip_tags(stripslashes($new_instance['photo_width']));
      $instance['customstyle'] = strip_tags(stripslashes($new_instance['customstyle']));
      $instance['lastupdated'] = new DateTime();
      return $instance;
    }

  /**
    * Creates the edit form for the widget.
    *
    */
    function form($instance){
      //Defaults
      $instance = wp_parse_args( (array) $instance, array('name'=>'', 'content'=>'', 'date_of_birth'=>'','time_of_birth'=>'', 'birth_weight'=>'','birth_length'=>'','show_age_weeks'=>'checked') );

      $name = htmlspecialchars($instance['name']);
      $content = htmlspecialchars($instance['content']);
      $date_of_birth = htmlspecialchars($instance['date_of_birth']);
      $time_of_birth = htmlspecialchars($instance['time_of_birth']);
      $birth_weight = htmlspecialchars($instance['birth_weight']);
      $birth_length = htmlspecialchars($instance['birth_length']);
      $current_weight = htmlspecialchars($instance['current_weight']);
      $current_length = htmlspecialchars($instance['current_length']);
      $show_birth_weight = $instance['show_birth_weight'];
      $show_birth_length = $instance['show_birth_length'];
      $show_current_weight = $instance['show_current_weight'];
      $show_current_length = $instance['show_current_length'];
	  $show_age_weeks = $instance['show_age_weeks'];
      $photo_url = $instance['photo_url'];
      $photo_width = $instance['photo_width'];
      $custom_style = $instance['customstyle'];

      # Output the options
      # title
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('name') . '">' . __('Name', 'kid-info-widget') . ': <input style="width: 200px;" id="' . $this->get_field_id('name') . '" name="' . $this->get_field_name('name') . '" type="text" value="' . $name . '" /></label></p>';
      # content
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('content') . '">' . __('Content', 'kid-info-widget') . ': <textarea rows="6" cols="40" style="width: 200px;" id="' . $this->get_field_id('content') . '" name="' . $this->get_field_name('content') . '" >' . $content . '</textarea></label></p>';
	#photo_url
 echo '<p style="text-align:right;"><label for="' . $this->get_field_name('photo_url') . '">' . __('Photo url', 'kid-info-widget') . ': <input style="width: 200px;" id="' . $this->get_field_id('photo_url') . '" name="' . $this->get_field_name('photo_url') . '" type="text" value="' . $photo_url . '" /></label></p>';
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('photo_width') . '">' . __('Width', 'kid-info-widget') . ': <input style="width: 100px;" id="' . $this->get_field_id('photo_width') . '" name="' . $this->get_field_name('photo_width') . '" type="text" value="' . $photo_width . '" /></label></p>';

      # date
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('date_of_birth') . '">' . __('Date of birth', 'kid-info-widget').' '.__('(YYYY-MM-DD)').':' . ' <input style="width: 200px;" id="' . $this->get_field_id('date_of_birth') . '" name="' . $this->get_field_name('date_of_birth') . '" type="text" value="' . $date_of_birth . '" /></label></p>';
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('time_of_birth') . '">' . __('Time of birth', 'kid-info-widget').' '.__('(HH:MM)').':' . ' <input style="width: 200px;" id="' . $this->get_field_id('time_of_birth') . '" name="' . $this->get_field_name('time_of_birth') . '" type="text" value="' . $time_of_birth . '" /></label></p>';
		
	  #show age in weeks
	  if($show_age_weeks!='') {
           $checked = "checked='checked'";
       } else {
           $checked = "";
       }
 	echo '<p><input type="checkbox" name="'.$this->get_field_name('show_age_weeks').'" value="true" '.$checked.' />';
	echo '<label for="' . $this->get_field_name('show_age_weeks').'">'. _e('Show age in weeks','kid-info-widget').'</label></p>';


     # weight
       if($show_birth_weight!='') {
           $checked = "checked='checked'";
       } else {
           $checked = "";
       }
 	echo '<p><input type="checkbox" name="'.$this->get_field_name('show_birth_weight').'" value="true" '.$checked.' />';
	echo '<label for="' . $this->get_field_name('show_birth_weight').'">'. _e('Show birth weight','kid-info-widget').'</label></p>';
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('birth_weight') . '">' . __('Birth weight', 'kid-info-widget').': ' . __('(gram)','kid-info-widget').' <input style="width: 200px;" id="' . $this->get_field_id('birth_weight') . '" name="' . $this->get_field_name('birth_weight') . '" type="text" value="' . $birth_weight . '" /></label></p>';

      # length
       if($show_birth_length!='') {
           $checked = "checked='checked'";
       } else {
           $checked = "";
       }
 	echo '<p><input type="checkbox" name="'.$this->get_field_name('show-birth-length').'" value="true" '.$checked.' />';
	echo '<label for="' . $this->get_field_name('show_birth_length').'">'. _e('Show birth length','kid-info-widget').'</label></p>';

      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('birth_length') . '">' . __('Birth length', 'kid-info-widget') . ': <input style="width: 200px;" id="' . $this->get_field_id('birth_length') . '" name="' . $this->get_field_name('birth_length') . '" type="text" value="' . $birth_length . '" /></label></p>';

      # currentweight
       if($show_current_weight!='') {
           $checked = "checked='checked'";
       } else {
           $checked = "";
       }
 	echo '<p><input type="checkbox" name="'.$this->get_field_name('show_current_weight').'" value="true" '.$checked.' />';
	echo '<label for="' . $this->get_field_name('show_current_weight').'">'. _e('Show current weight','kid-info-widget').'</label></p>';

      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('current_weight') . '">' . __('Weight', 'kid-info-widget').': ' . __('(gram)','kid-info-widget').' <input style="width: 200px;" id="' . $this->get_field_id('current_weight') . '" name="' . $this->get_field_name('current_weight') . '" type="text" value="' . $current_weight . '" /></label></p>';

      # length
       if($show_current_length!='') {
           $checked = "checked='checked'";
       } else {
           $checked = "";
       }

 	echo '<p><input type="checkbox" name="'.$this->get_field_name('show_current_length').'" value="true" '.$checked.' />';
	echo '<label for="' . $this->get_field_name('show_current_length').'">'. _e('Show current length','kid-info-widget').'</label></p>';
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('current_length') . '">' . __('Length', 'kid-info-widget') . ': <input style="width: 200px;" id="' . $this->get_field_id('current_length') . '" name="' . $this->get_field_name('current_length') . '" type="text" value="' . $current_length . '" /></label></p>';

      # customstyle
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('customstyle') . '">' . __('Custom CSS class', 'kid-info-widget') . ': <input style="width: 200px;" id="' . $this->get_field_id('customstyle') . '" name="' . $this->get_field_name('customstyle') . '" type="text" value="' . $custom_style . '" /></label></p>';

  }

}// END class


function calculateAge($birthDate) {
    $beginTimestamp = $birthDate;
    $endTimestamp = time();

    $difference_of_times = $endTimestamp - $beginTimestamp;

    for($timeUnit = 6; $timeUnit > 0; $timeUnit--)
    {
        switch($timeUnit)
        {
            case '1'; //minutes
                $unitSize = 60;
                $diffUnitIdentifier = 'minute';
                break;

            case '2';
                $unitSize = 3600;
                $diffUnitIdentifier = 'hour';
                break;

            case '3';
                $unitSize = 86400;
                $diffUnitIdentifier = 'day';
                break;

            case '4';
                $unitSize = 604800;
                $diffUnitIdentifier = 'week';
                break;

            case '5';
                $unitSize = 2678400;
                $diffUnitIdentifier = 'month';
                break;

            case '6';
                $unitSize = 31536000;
                $diffUnitIdentifier = 'year';
                break;
		}

        if($difference_of_times > ($unitSize - 1))
        {
            $modulus_for_time_difference = $difference_of_times % $unitSize;
            $seconds_for_current_unit = $difference_of_times - $modulus_for_time_difference;
            $units_calculated = $seconds_for_current_unit / $unitSize;
            $difference_of_times = $modulus_for_time_difference;

            $age[$diffUnitIdentifier] = $units_calculated;
        }
    }
    $age['second'] = $difference_of_times;

    return $age;
}


function calculateWeeks($birthDate) {
      return floor(($now-$birthDate) /  604800);
}

function calculateTimeDiff( $birthDate )
{
    $interval = date_create('now')->diff( $birthDate );
    $suffix = ( $interval->invert ? ' geleden' : '' );
    if ( $v = $interval->y >= 1 ) $age['year'] = $interval->y;
    if ( $v = $interval->m >= 1 ) $age['month'] = $interval->m;
    if ( $v = $interval->d >= 1 ) $age['day'] = $interval->d;
    if ( $v = $interval->h >= 1 ) $age['hour'] = $interval->h;
    if ( $v = $interval->i >= 1 ) $age['minute'] = $interval->i;
    $age['second'] = $interval->s;
    return $age;
}

/**
  * Register Kid Info Widget
  *
  * Calls 'widgets_init' action after the Kid Info widget has been registered.
  */
  function KidInfoInit() {
  register_widget('KidInfoWidget');
  }


  /**
   * Include stylesheet for displaying profiles.
   */
  function add_kid_info_style() {
	wp_enqueue_style('kid-info-widget', plugins_url('kid-info-widget/kid-info-widget.css'));
  }

?>