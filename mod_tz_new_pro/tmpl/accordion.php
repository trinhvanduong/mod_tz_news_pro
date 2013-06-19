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
<div class = "mod_tz_news ">
    <div class="tz_news">
        <?php if(isset($list) && !empty($list)){
        foreach($list as $item){ ?>
            <div class="tz_accordion">

                <?php if($title == 1){ ?>
                    <h3 class="tz_title">
                        <?php echo $item->title; ?>
                    </h3>
                <?php } ?>

                <div class="info_accordion">
                    <?php if($image == 1){ ?>
                        <span class="tz_image">
                            <a href="<?php echo $item->link; ?>">
                                <img src = "<?php echo JUri::root().$item->image; ?>" alt ="<?php echo $item->title; ?>" />
                            </a>
                        </span>
                    <?php } ?>

                    <?php if($date == 1){ ?>
                        <span class="tz_date">
                            <?php echo JText::sprintf("MOD_TZ_NEWS_DATE_ALL",date(JText::_('MOD_TZ_NEWS_DATE_FOMAT'),strtotime($item->created))) ; ?>
                        </span>
                    <?php } ?>

                    <?php if($hits == 1){ ?>
                        <span class="tz_hits">
                            <?php echo JText::sprintf('MOD_TZ_NEWS_HIST_LIST', $item->hit) ?>
                        </span>
                    <?php } ?>

                    <?php if($des == 1){ ?>
                        <p>
                            <?php if($limittext){
                                    echo substr($item->intro, 3, $limittext);
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
        <?php }
        }?>
    </div>
</div>

<script type="text/javascript">
    var tz = jQuery.noConflict();
    tz('.tz_news .tz_accordion:first-child h3').addClass('open');
    tz('.tz_accordion h3').click(function(){
        tz(this).parent().find('.info_accordion').slideToggle();
        tz(this).toggleClass('open');
    });
</script>