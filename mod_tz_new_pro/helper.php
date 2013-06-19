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

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');

$url = JURI::base();

abstract class modTzNewsHelper
{

    /*
     * Method get data
    */
    public static function getList(&$params){

        $db             = JFactory::getDbo();
        $content        = $params->get('manager');
        $order          = $params->get('order');
        $orderby        = $params->get('orderby');
        $limit          = $params->get('limit');
        $catids         = $params->get('catid');
        $catidsK2       = $params->get('id_k2');
        $img            = $params->get('image');
        $typenews       = $params->get('views');
        $featureol       = $params->get('show_featured');
        $redirect       = $params->get('redirect_page');


        // portfolio and content

        if($typenews == "featured"){
            if(isset($catids) && !empty($catids)){
                $catid      = implode(",",$catids);
                $where = "WHERE ct.catid IN($catid) and ct.state = 1  and featured = 1 ORDER BY ct.$orderby $order LIMIT $limit";
            }else{
                $where = "WHERE ct.state = 1 and featured = 1 ORDER BY ct.$orderby $order LIMIT $limit";
            }
        } else {
            if(isset($catids) && !empty($catids)){
                $catid      = implode(",",$catids);
                if($featureol==1){
                    $where = "WHERE ct.catid IN($catid) and ct.state = 1 and featured = 1 ORDER BY ct.$orderby $order LIMIT $limit";
                } else{
                    $where = "WHERE ct.catid IN($catid) and ct.state = 1  ORDER BY ct.$orderby $order LIMIT $limit";
                }

            }else{
                $where = "WHERE ct.state = 1 ORDER BY ct.$orderby $order LIMIT $limit";
            }
        }

        //k2
        if(isset($catidsK2) && !empty($catidsK2)){
            $catidsK2      = implode(",",$catidsK2);
            $where2 = "WHERE ct.catid IN($catidsK2) and ct.published = 1  ORDER BY ct.$orderby $order LIMIT $limit";
        }else{
            $where2 = "WHERE ct.published = 1 ORDER BY ct.$orderby $order LIMIT $limit";
        }



        if($content  == 'tz_portfolio'){
            $path       = JPATH_SITE.'/components/com_tz_portfolio/tz_portfolio.php';
            if(file_exists($path)){
                 require_once JPATH_SITE.'/components/com_tz_portfolio/helpers/route.php';

                    $query      =   "SELECT *, ct.id as arid, ct.hits as hit, ct.title as artitle, ct.alias as aralias, cat.alias as category_alias, tzx.images as tzimages,
                                      tzx.gallery as tzgallery, tzx.video as tzvideo
                                    FROM #__content ct LEFT JOIN #__tz_portfolio_xref_content tzx ON(ct.id = tzx.contentid)
                                    LEFT JOIN #__categories cat ON(ct.catid = cat.id)
                                    $where ";
            }else{
                return null;
            }
        }else if($content == 'joomla_content'){

                    $query      =   "SELECT *,ct.id as arid, ct.hits as hit, ct.title as artitle, ct.alias as aralias, cat.alias as category_alias
                                     FROM #__content ct LEFT JOIN #__categories cat ON(ct.catid = cat.id)
                                    $where ";

        }else if($content == 'k2'){
            $path       = JPATH_SITE.'/components/com_k2/k2.php';

            if(file_exists($path)){
                require_once JPATH_SITE.'/components/com_k2/helpers/route.php';

                    $query      =   "SELECT *,ct.id as arid, ct.hits as hit, ct.title as artitle, ct.alias as aralias,
                                    ct.gallery as tzgallery, ct.video as tzvideo,
                                    cat.alias as category_alias
                                  FROM #__k2_items ct LEFT JOIN #__k2_categories cat ON(ct.catid = cat.id)
                                  $where2 ";
            }else{
                return null;
            }
        }

        $db -> setQuery($query);
        $items = $db->loadObjectList();
        if($items){
            if($content == 'tz_portfolio'){
                foreach($items as $item){
                    $item->title    = $item->artitle;
                    $item->hit      = $item->hit;
                    $item->intro    = $item->introtext;
                    $item->slug     = $item->arid.':'.$item->aralias;
                    $item->catslug  = $item->catid.':'.$item->category_alias;
                    if($redirect == 0){
                    $item->link     = JRoute::_(TZ_PortfolioHelperRoute::getArticleRoute($item->slug, $item->catslug));
                    }
                    if($redirect == 1){
                    $item->link     = JRoute::_(TZ_PortfolioHelperRoute::getPortfolioArticleRoute($item->slug, $item->catslug));
                    }

                    if($item->tzimages){
                        $images     = $item->tzimages;
                        $nameimg    = JFile::getExt($images);
                        $count      = strlen($nameimg);
                        $image_name = substr($images, 0, -($count + 1));
                        $item->image = $image_name.'_'.$img.'.'.$nameimg;

                    }
                    if($item->tzgallery){
                        $images         = $item->tzgallery;
                        $arrimages      = explode("///",$images);
                        if($arrimages[0]){
                            $nameimg    = JFile::getExt($arrimages[0]);
                            $count      = strlen($nameimg);
                            $image_name = substr($arrimages[0], 0, -($count + 1));
                            $item->image= $image_name.'_'.$img.'.'.$nameimg;
                        }
                    }
                    if($item->tzvideo){
                        $images      = $item->videothumb;
                        $nameimg     = JFile::getExt($images);
                        $count       = strlen($nameimg);
                        $image_name  = substr($images, 0, -($count + 1));
                        $item->image = $image_name.'_'.$img.'.'.$nameimg;
                    }

                }
                return $items;
            }
            if($content == 'joomla_content'){
                foreach($items as $item){
                    $item->title    = $item->artitle;
                    $item->hit      = $item->hit;
                    $item->intro    = $item->introtext;
                    $item->slug     = $item->arid.':'.$item->aralias;
                    $item->catslug  = $item->catid.':'.$item->category_alias;
                    $item->link     = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
                    if($item->images){
                        $images     = new JRegistry;
                        $images->loadString($item->images);
                        $item->images;
                        $images     = json_decode($item->images);
                        $item->image = $images->image_intro;
                        $item->image_full = $images->image_fulltext;
                    }
                }
                return $items;
            }
            if($content == 'k2'){
                foreach($items as $item){

                    $item->title    = $item->artitle;
                    $item->hit      = $item->hit;
                    $item->intro    = $item->introtext;
                    $item->slug     = $item->arid.':'.$item->aralias;
                    $item->catslug  = $item->catid.':'.$item->category_alias;
                    $item->link     = JRoute::_(K2HelperRoute::getItemRoute($item->slug, $item->catslug));
                    if($item->id){
                        $images     = '/media/k2/items/cache/'.md5("Image".$item->arid);
                        $item->image = $images.'_'.$img.'.jpg';

                    }

                }
                return $items;
            }
        }
        return false;
    }


    public static function generateTabs($tabs, $list, $params, $module)
    {
        $title_type     = $params->get('tabs_title_type');
        $html           = array();
        if($title_type == 'custom'){
            $titles     = explode(",", $params->get('tabs_title_custom'));
        }
        if($tabs        == 0 OR $tabs>count($list)) $tabs = count($list);

        $html[]         = '<ul class="tabs">';

        for($i=0; $i<$tabs; $i++){
            if($list[$i]->introtext != NULL)
            {
                if($title_type == 'custom') $title = (isset($titles[$i])) ? $titles[$i] : '';
                else $title     = $list[$i]->title;
                $html[]         = '<li>';
                $html[]         = '<a href="#tab'.$i.'">';
                $html[]         = "<span>$title</span>";
                $html[]         = '</a>';
                $html[]         = '</li>';
            }

        }
        $html[] = '</ul>';

        return implode("\n", $html);

    }
}
?>
