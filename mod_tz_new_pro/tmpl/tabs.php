<?php
/*------------------------------------------------------------------------

# MOD_TZ_NEW_PRO Extension

# ------------------------------------------------------------------------

# author    tuyennv

# copyright Copyright (C) 2013 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/


// no direct access
defined('_JEXEC') or die;
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.tabs').each(function(){
            // For each set of tabs, we want to keep track of
            // which tab is active and it's associated content
            var $active, $content, $links = jQuery(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = jQuery($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);

            $active.addClass('active');
            $content = jQuery($active.attr('href'));

            // Hide the remaining content
            $links.not($active).each(function () {

                jQuery(jQuery(this).attr('href')).hide();
            });

            // Bind the click event handler
            jQuery(this).on('click', 'a', function(e){

                // Make the old tab inactive.
                $active.removeClass('active');
                $content.slideUp(450,function(){
                    $content.hide();
                });


                // Update the variables with the new link and content
                $active = jQuery(this);
                $content = jQuery(jQuery(this).attr('href'));

                // Make the tab active.
                $active.addClass('active');
                $content.slideDown(450,function(){
                    $content.show();
                });


                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });

    })
</script>

<div  class="tz-wrap">
    <?php if($tabs_position == 'top') echo $tabs_title; ?>
    <?php
        if($tabs < count($items)){
            $count = $tabs;
        }else{
            $count = count($items);
        }
        for($i=0; $i < $count; $i++):
    ?>
            <div class="tz_tabs_introtext" id="tab<?php echo $i; ?>">
                <?php
                if(isset($items[$i]->introtext)){
                    echo $items[$i]->introtext;
                }
                ?>
            </div>
    <?php endfor; ?>
    <?php if($tabs_position == 'bottom') echo $tabs_title; ?>
</div>




