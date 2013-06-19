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
<div class = "mod_tz_news">
    <ul class="tz_news default1">
<?php if(isset($list) && !empty($list)){
 foreach($list as $item){ ?>
        <li class="tz_item_default">
            <h6 class="tz_title">
                <a class="iframe" href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>">
                    <?php
                            echo $item->title;
                    ?>
                </a>
            </h6>
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

            <?php if($readmore == 1){ ?>
                <span class="tz_readmore">
                    <a  class="iframe" href="<?php echo $item->link; ?>"><?php echo JText::_('MOD_TZ_NEWS_READ_MORE') ?></a>
                </span>
            <?php } ?>

        </li>
    <?php } } ?>
    </ul>
</div>