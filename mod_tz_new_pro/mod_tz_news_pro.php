    <?php

     /*------------------------------------------------------------------------

     # MOD_TZ_NEWS_PRO Extension

     # ------------------------------------------------------------------------

     # author    tuyennv

     # copyright Copyright (C) 2013 templaza.com. All Rights Reserved.

     # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

     # Websites: http://www.templaza.com

     # Technical Support:  Forum - http://templaza.com/Forum

     -------------------------------------------------------------------------*/
    // no direct access
    defined('_JEXEC') or die;



    include_once JPATH_LIBRARIES . '/tzm/tz_news.php';
    $version        = new JVersion();
    $document       =   JFactory::getDocument();


    $document       ->  addStyleSheet('modules/mod_tz_news_pro/css/mod_tz_new_marquee.css');
    $document       ->  addScript('modules/mod_tz_news_pro/js/mod_tz_news.slides.js');
    $document       ->  addScript('modules/mod_tz_news_pro/js/jquery.simplyscroll.min.js');
    $document       ->  addStyleSheet('modules/mod_tz_news_pro/css/mod_tz_news.css');
    $document       ->  addStyleSheet('modules/mod_tz_news_pro/css/slider.css');



    // Include the syndicate functions only once
    require_once dirname(__FILE__).'/helper.php';

    // Import source and get the class name
    $class_name = importSource("modules");

    // Create instance of the class
    $instance = new $class_name($module, $params);

    // Get the items
    $items = $instance->getItems();

    $tabs           = $params->get('tz_count',3);
    $tabs_position  = $params->get('tabs_position','top');
    $tabs_title     = modTzNewsHelper::generateTabs($tabs,$items,$params, $module);

    $list           =   modTzNewsHelper::getList($params);
    $view           =   $params->get('views');
    $title          =   $params->get('show_title');
    $hits           =   $params->get('show_hits');
    $image          =   $params->get('show_image');
    $date           =   $params->get('show_date');
    $des            =   $params->get('show_description');
    $readmore       =   $params->get('show_readmore');
    $readtext       =   $params->get('readmore_text');
    $limittext      =   $params->get('limit_text');
    $limittitle     =   $params->get('limit_title');
    $heightsl       = $params->get('slide_height');
    $widthsl       = $params->get('slide_width');

    //Config to marquee
    $CustomClass    =   $params->get('tz_customclass');
    $orientation    =   $params->get('tz_orientation');
    $direction      =   $params->get('tz_direction');
    $frameRate      =   $params->get('tz_frameRate');
    //end marquee


    //Config to tooltip
    $fadeinspeed        =   $params->get('tz_fadeinspeed');
    $background_s       =   $params->get('tz_border_s');
    $background_out     =   $params->get('tz_border_out');
    $border_s           =   $params->get('tz_background_s');
    $border_out         =   $params->get('tz_background_out');
    $tooltipStatus      =   $params->get('tz_tooltip_status');
    //end tooltip

    //congif to slider
    $tz_NextPrev        =   $params->get('tz_NextPrev');
    $tz_Pagination      =   $params->get('tz_Pagination');
    $tz_effect          =   $params->get('tz_effect');
    $tz_slideSpeed      =   $params->get('tz_slideSpeed');

    $author_new         =   $params->get('show_author');
    $cats_new           =   $params->get('show_category');
    // end slider

    if($view){

        if($view == "default"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','default');

        }elseif($view=="accordion"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','accordion');

        }elseif($view=="slider"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','slider');

        }elseif($view=="list"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','list');

        }elseif($view=="tabs"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','tabs');

        }elseif($view=="marquee"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','marquee');

        }elseif($view=="tooltip"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','tooltip');

        }elseif($view=="featured"){

            require JModuleHelper::getLayoutPath('mod_tz_news_pro','featured');

        }
    }else{
        echo JText::_('MOD_TZ_NEWS_NOT_ITEM');
    }

    ?>
