<?php
/**
 * Created by PhpStorm.
 * User: TuanMap
 * Date: 2/27/14
 * Time: 10:52 AM
 */
?>
<div class="slide">
    <div class="tz_audio">

        <?php if ($title == 1) : ?>
            <h3 class="tz_title">
                <a href="<?php echo $item->link; ?>"
                   title="<?php echo $item->title; ?>">
                    <?php echo $item->title; ?>
                </a>
            </h3>
        <?php endif; ?>

        <?php if ($image == 1) : ?>
            <a class="title" href="<?php echo $item->link; ?>">
                <img src="<?php echo $item->image; ?>"
                     title="<?php echo $media->imagetitle; ?>"
                     alt="<?php echo $media->imagetitle; ?>"/>
            </a>
        <?php endif; ?>

        <?php if ($des == 1) : ?>
            <span class="tz_description">
            <?php if ($limittext) :
                echo substr($item->intro, 3, $limittext);
            else :
                echo $item->intro;
            endif;?>
                </span>
        <?php endif; ?>

        <?php if ($hits == 1) : ?>
            <span class="tz_hits">
                                    <?php echo JText::sprintf('MOD_TZ_NEWS_HIST_LIST', $item->hit) ?>
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

        <?php if ($readmore == 1) : ?>
            <span class="tz_readmore">
                                    <a href="<?php echo $item->link; ?>"><?php echo JText::_('MOD_TZ_NEWS_READ_MORE') ?></a>
                                </span>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
</div>