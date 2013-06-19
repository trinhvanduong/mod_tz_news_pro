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
    $document->addScript('modules/mod_tz_news_pro/js/jquery.slides.min.js');


    if($list):

?>

    <div class="ser-bottom-middle">
        <div class="bottom-middle-slide ser-bottom-middle-slide">
            <div class="img-bottom-slide">
                <div id="slides">
                    <div class="slides_container">
                        <?php foreach($list as $item): ?>
                            <div class="slide">

                                <?php if($title == 1){ ?>
                                    <h3 class="tz_title_slide">
                                        <?php echo $item->title; ?>
                                    </h3>
                                    <?php if($hits == 1){ ?>
                                        <span class="tz_hits">
                                            <?php echo JText::sprintf('MOD_TZ_NEWS_HIST_LIST', $item->hit) ?>
                                        </span>
                                    <?php } ?>
                                <?php } ?>

                                <div class="info_slide">
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
                        <?php endforeach; ?>

                    </div>

                </div>

            </div>
        </div><!--End .bottom-middle-cont -->
    </div><!--End .rec-bottom-middle right-->

<?php endif;?>

<script type="text/javascript">
    var tz = jQuery.noConflict();
    tz('#slides').slides({
        autoHeight: true,
        generateNextPrev: <?php echo $tz_NextPrev; ?>,
        slideSpeed: <?php echo $tz_slideSpeed; ?>,
        generatePagination: <?php echo $tz_Pagination; ?>,
        effect: '<?php echo $tz_effect; ?>'
    });
</script>

