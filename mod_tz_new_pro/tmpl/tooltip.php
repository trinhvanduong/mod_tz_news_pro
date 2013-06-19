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

    $document   =   JFactory::getDocument();
    $document   ->  addStyleSheet('modules/mod_tz_news_pro/css/stickytooltip.css');
?>

<script type="text/javascript">

    var stickytooltip={
        tooltipoffsets: [20, -30], //additional x and y offset from mouse cursor for tooltips
        fadeinspeed: "<?php echo $fadeinspeed ; ?>" , //duration of fade effect in milliseconds
        rightclickstick: true, //sticky tooltip when user right clicks over the triggering element (apart from pressing "s" key) ?
        stickybordercolors: ["<?php echo $border_s; ?>", "<?php echo $border_out; ?>"], //border color of tooltip depending on sticky state
        stickynotice1: ["<?php echo JText::_('TZ_MOD_NEWS_PRESS_S_CLICK'); ?>"], //customize tooltip status message
        stickynotice2: "<?php echo JText::_('TZ_MOD_NEWS_PRESS_OUT_CLICK'); ?>", //customize tooltip status message

        //***** NO NEED TO EDIT BEYOND HERE

        isdocked: false,

        positiontooltip:function($, $tooltip, e){
            var x=e.pageX+this.tooltipoffsets[0], y=e.pageY+this.tooltipoffsets[1]
            var tipw=$tooltip.outerWidth(), tiph=$tooltip.outerHeight(),
                    x=(x+tipw>$(document).scrollLeft()+$(window).width())? x-tipw-(stickytooltip.tooltipoffsets[0]*2) : x
            y=(y+tiph>$(document).scrollTop()+$(window).height())? $(document).scrollTop()+$(window).height()-tiph-10 : y
            $tooltip.css({left:x, top:y})
        },

        showbox:function($, $tooltip, e){
            $tooltip.fadeIn(this.fadeinspeed)
            this.positiontooltip($, $tooltip, e)
        },

        hidebox:function($, $tooltip){
            if (!this.isdocked){
                $tooltip.stop(false, true).hide()
                $tooltip.css({borderColor:'<?php echo $background_s; ?>'}).find('.stickystatus:eq(0)').css({background:this.stickybordercolors[0]}).html(this.stickynotice1)
            }
        },

        docktooltip:function($, $tooltip, e){
            this.isdocked=true
            $tooltip.css({borderColor:'<?php echo $background_out; ?>'}).find('.stickystatus:eq(0)').css({background:this.stickybordercolors[1]}).html(this.stickynotice2)
        },


        init:function(targetselector, tipid){
            jQuery(document).ready(function($){
                var $targets=$(targetselector)
                var $tooltip=$('#'+tipid).appendTo(document.body)
                if ($targets.length==0)
                    return
                var $alltips=$tooltip.find('div.atip')
                if (!stickytooltip.rightclickstick)
                    stickytooltip.stickynotice1[1]=''
                stickytooltip.stickynotice1=stickytooltip.stickynotice1.join(' ')
                stickytooltip.hidebox($, $tooltip)
                $targets.bind('mouseenter', function(e){
                    $alltips.hide().filter('#'+$(this).attr('data-tooltip')).show()
                    stickytooltip.showbox($, $tooltip, e)
                })
                $targets.bind('mouseleave', function(e){
                    stickytooltip.hidebox($, $tooltip)
                })
                $targets.bind('mousemove', function(e){
                    if (!stickytooltip.isdocked){
                        stickytooltip.positiontooltip($, $tooltip, e)
                    }
                })
                $tooltip.bind("mouseenter", function(){
                    stickytooltip.hidebox($, $tooltip)
                })
                $tooltip.bind("click", function(e){
                    e.stopPropagation()
                })
                $(this).bind("click", function(e){
                    if (e.button==0){
                        stickytooltip.isdocked=false
                        stickytooltip.hidebox($, $tooltip)
                    }
                })
                $(this).bind("contextmenu", function(e){
                    if (stickytooltip.rightclickstick && $(e.target).parents().andSelf().filter(targetselector).length==1){ //if oncontextmenu over a target element
                        stickytooltip.docktooltip($, $tooltip, e)
                        return false
                    }
                })
                $(this).bind('keypress', function(e){
                    var keyunicode=e.charCode || e.keyCode
                    if (keyunicode==115){ //if "s" key was pressed
                        stickytooltip.docktooltip($, $tooltip, e)
                    }
                })
            }) //end dom ready
        }
    }

    //stickytooltip.init("targetElementSelector", "tooltipcontainer")
    stickytooltip.init("*[data-tooltip]", "mystickytooltip");
</script>

<div id="tz_tooltip">
    <?php if(isset($list) && !empty($list)){
        foreach($list as $i=>$item){ ?>
            <h6 class="tz_title" >
                <a class="tz_title_like" data-tooltip="sticky<?php echo $i ?>" href="<?php echo $item->link; ?>"  >
                    <?php
                        echo $item->title;
                    ?>
                </a>
            </h6>

    <?php }
    }
    ?>
    <div style="clear: both"></div>
</div>

    <!--HTML for the tooltips-->

<div id="mystickytooltip" class="stickytooltip">
<?php if(isset($list) && !empty($list)){
   foreach($list as $i=>$item){ ?>
        <div id="sticky<?php echo $i; ?>" class="atip tz_stichky" >
                <div class="info_slide">
                    <?php if($image == 1 && !empty($item->image)){ ?>
                        <span class="tz_image">
                            <a href="<?php echo $item->link; ?>">
                                <img src = "<?php echo JUri::root().$item->image; ?>" alt ="<?php echo $item->title; ?>" />
                                <br>
                            </a>
                        </span>
                    <?php } ?>

                    <?php if($date == 1){ ?>
                        <span class="tz_date">
                            <?php echo JText::sprintf("MOD_TZ_NEWS_DATE_ALL",date(JText::_('MOD_TZ_NEWS_DATE_FOMAT'),strtotime($item->created))) ; ?>
                        </span>
                    <?php } ?>

                    <?php if($des == 1){ ?>
                        <p>
                        <?php
                            if(isset($limittext) && !empty($limittext)){
                            $arr_title = explode(' ',$item->intro);
                            $arr_title = array_slice($arr_title,0,$limittext);
                            $arr_text  = implode(' ',$arr_title);
                            echo $arr_text;
                        }else{
                            echo $item->intro;
                        }
                        ?>
                        </p>
                    <?php } ?>

                    <?php if($readmore == 1){ ?>
                        <span class="tz_readmore">
                            <a href="<?php echo $item->link; ?>"><?php echo JText::_('MOD_TZ_NEWS_READ_MORE') ?></a>
                        </span>
                    <?php } ?>
            </div>
        </div>
    <?php } ?>

    <?php if(isset($tooltipStatus) && $tooltipStatus==1){ ?>
        <div class="stickystatus"></div>
    <?php } }?>
</div>