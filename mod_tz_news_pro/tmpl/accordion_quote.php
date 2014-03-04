<?php
/**
 * Created by PhpStorm.
 * User: TuanMap
 * Date: 2/27/14
 * Time: 10:41 AM
 */
?>
<div class="tz_accordion" id="section<?php echo $i; ?>">
    <h3 class="tz_title">
        <?php echo $item->title; ?>
    </h3>
</div>
<div class="tz_quote info_accordion">

    <i class="icon-quote"></i>
    <?php echo $media->quote_text ?>

    <div class="muted author"><?php echo $media->quote_author; ?></div>

    <?php if ($hits == 1) : ?>
        <span class="tz_hits">
                                <?php echo JText::sprintf('MOD_TZ_NEWS_HIST_LIST', $list[$i]->hit) ?>
                            </span>
    <?php endif; ?>

    <?php if ($author_new == 1): ?>
        <span class="tz_author">
                                <?php echo JText::sprintf('MOD_TZ_NEWS_AUTHOR', $item->author); ?>
                            </span>
    <?php endif; ?>

    <?php if ($cats_new == 1): ?>
        <span class="tz_category">
                                <?php echo JText::sprintf('MOD_TZ_NEWS_CATEGORY', $item->category); ?>
                            </span>
    <?php endif; ?>

    <?php if ($date == 1) : ?>
        <span class="tz_date">
                                <?php echo JText::sprintf('MOD_TZ_NEWS_DATE_ALL', JHtml::_('date', $item->created, JText::_('MOD_TZ_NEWS_DATE_FOMAT'))); ?>
                            </span>
    <?php endif; ?>
</div>
<div class="clearfix"></div>