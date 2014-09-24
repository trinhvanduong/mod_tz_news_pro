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

require_once JPATH_SITE . '/components/com_content/helpers/route.php';
if ($content == 'tz_portfolio') {
    require_once JPATH_SITE . '/components/com_tz_portfolio/helpers/route.php';
}
require_once(JPATH_SITE . '/libraries/tzm/sources/modules/modules.php');
jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_tz_portfolio/models', 'TZ_PortfolioModel');
$url = JURI::base();

abstract class modTzNewsHelper
{

    /*
     * Method get data
    */
    public static function getList(&$params)
    {
        $db = JFactory::getDbo();
        $content = $params->get('manager');
        $order = $params->get('order');
        $orderby = $params->get('orderby');
        $limit = $params->get('limit');
        $catids = $params->get('catid');
        $img = $params->get('image');
        $typenews = $params->get('views');
        $featureol = $params->get('show_featured');
        $redirect = $params->get('redirect_page');
        $show_audio = $params->get('show_audio');
        $show_quote = $params->get('show_quote');
        $show_link = $params->get('show_link');
        $dispatcher = JDispatcher::getInstance();
        $li = array();


        if ($show_audio == 0) {
            $li[] = "'audio'";
        }
        if ($show_quote == 0) {
            $li[] = "'quote'";
        }
        if ($show_link == 0) {
            $li[] = "'link'";
        }
        $li_arr = implode(",", $li);
        $where_a = '(tz_xc.type not in(' . $li_arr . ') or tz_xc.type is null)';
        // portfolio and content
        if ($typenews == "featured") {
            if (isset($catids) && !empty($catids)) {
                $catid = implode(",", $catids);
                $where = " ct.catid IN($catid) and ct.state = 1  and ct.featured = 1 ORDER BY ct.$orderby $order LIMIT $limit";
            } else {
                $where = " ct.state = 1 and ct.featured = 1 ORDER BY ct.$orderby $order LIMIT $limit";
            }
        } else {
            if (isset($catids) && !empty($catids)) {
                $catid = implode(",", $catids);
                if ($featureol == 1) {
                    $where = " ct.catid IN($catid) and ct.state = 1 and ct.featured = 1 ORDER BY ct.$orderby $order LIMIT $limit";
                } else {
                    $where = " ct.catid IN($catid) and ct.state = 1  ORDER BY ct.$orderby $order LIMIT $limit";
                }
            } else {
                $where = " ct.state = 1 ORDER BY ct.$orderby $order LIMIT $limit";
            }
        }

        if ($content == 'tz_portfolio') {
            $jquery = $db->getQuery(true);
            $jquery->select('ct.*');
            $jquery->from('#__content as ct');
            $jquery->select('cat.title as category_title,cat.id, cat.alias as category_alias');
            $jquery->join('left', '#__categories as cat on cat.id = ct.catid');
            $jquery->select('us.id,us.name as author');
            $jquery->join('left', '#__users as us on us.id = ct.created_by');
            $jquery->select('tz_xc.type as type_media,tz_xc.*');
            $jquery->join('left', '#__tz_portfolio_xref_content as tz_xc on tz_xc.contentid = ct.id');
            if ($li) {
                $jquery->where($where_a);
            }
            $jquery->where($where);
            $db->setQuery($jquery);
            $items = $db->loadObjectList();
            $param_com = JComponentHelper::getComponent('com_content')->params;
            if ($items) {
                foreach ($items as $i => $item) {

                    $item->text = $item->introtext;
                    JPluginHelper::importPlugin('content');
                    $results = $dispatcher->trigger('onContentPrepare', array('com_tz_portfolio.article', &$item, &$param_com, 0));
                    $item->introtext = $item->text;
                    $item->event = new stdClass();
                    $results = $dispatcher->trigger('onContentAfterTitle', array('com_tz_portfolio.article', &$item, &$param_com, 0));
                    $item->event->afterDisplayTitle = trim(implode("\n", $results));
                    $results = $dispatcher->trigger('onContentBeforeDisplay', array('com_tz_portfolio.article', &$item, &$param_com, 0));
                    $item->event->beforeDisplayContent = trim(implode("\n", $results));
                    $results = $dispatcher->trigger('onContentAfterDisplay', array('com_tz_portfolio.article', &$item, &$param_com, 0));
                    $item->event->afterDisplayContent = trim(implode("\n", $results));
                    //Get Plugins Model
                    $pmodel = JModelLegacy::getInstance('Plugins', 'TZ_PortfolioModel', array('ignore_request' => true));
                    //Get plugin Params for this article
                    $pmodel->setState('filter.contentid', $item->id);
                    $pluginParams = $pmodel->getParams();
                    $item->pluginparams = clone($pluginParams);
                    JPluginHelper::importPlugin('tz_portfolio');
                    $results = $dispatcher->trigger('onTZPluginPrepare', array('com_tz_portfolio.article', &$item, &$param_com, &$pluginParams, 0));
                    $results = $dispatcher->trigger('onTZPluginAfterTitle', array('com_tz_portfolio.article', &$item, &$param_com, &$pluginParams, 0));
                    $item->event->TZafterDisplayTitle = trim(implode("\n", $results));
                    $results = $dispatcher->trigger('onTZPluginBeforeDisplay', array('com_tz_portfolio.article', &$item, &$param_com, &$pluginParams, 0));
                    $item->event->TZbeforeDisplayContent = trim(implode("\n", $results));
                    $results = $dispatcher->trigger('onTZPluginAfterDisplay', array('com_tz_portfolio.article', &$item, &$param_com, &$pluginParams, 0));
                    $item->event->TZafterDisplayContent = trim(implode("\n", $results));
                    $item->media = null;
                    $item->tztitle = $item->title;
                    $item->hit = $item->hits;
                    echo $item->event->beforeDisplayContent;
                    echo $item->event->TZbeforeDisplayContent;
                    $item->intro = $item->introtext;
                    echo $item->event->afterDisplayContent;
                    echo $item->event->TZafterDisplayContent;
                    $item->slug = $item->contentid . ':' . $item->alias;
                    $item->catslug = $item->catid . ':' . $item->category_alias;
                    $item->category = $item->category_title;
                    $item->author = $item->author;
                    if ($redirect == 0) {
                        $item->link = JRoute::_(TZ_PortfolioHelperRoute::getArticleRoute($item->slug, $item->catslug));
                    }
                    if ($redirect == 1) {
                        $item->link = JRoute::_(TZ_PortfolioHelperRoute::getPortfolioArticleRoute($item->slug, $item->catslug));
                    }
                    if (isset($item->type_media) and $item->type_media and $item->type_media != 'none') {

                        if ($item->type_media == 'image') {
                            $images = $item->images;
                            if ($images) {
                                $nameimg = JFile::getExt($images);
                                $count = strlen($nameimg);
                                $image_name = substr($images, 0, -($count + 1));
                                $item->image = $image_name . '_' . $img . '.' . $nameimg;
                            } else $item->image = '';
                        }
                        if ($item->type_media == 'imageGallery') {
                            $images = $item->gallery;
                            if ($images) {
                                $arrimages = explode("///", $images);
                                if ($arrimages[0]) {
                                    $nameimg = JFile::getExt($arrimages[0]);
                                    $count = strlen($nameimg);
                                    $image_name = substr($arrimages[0], 0, -($count + 1));
                                    $item->image = $image_name . '_' . $img . '.' . $nameimg;
                                }
                            } else $item->image = '';
                        }
                        if ($item->type_media == 'video') {
                            $images = $item->videothumb;
                            if ($images) {
                                $nameimg = JFile::getExt($images);
                                $count = strlen($nameimg);
                                $image_name = substr($images, 0, -($count + 1));
                                $item->image = $image_name . '_' . $img . '.' . $nameimg;
                            } else $item->image = '';
                        }
                        if ($item->type_media == 'audio') {
                            $images = $item->audiothumb;
                            if ($images) {
                                $nameimg = JFile::getExt($images);
                                $count = strlen($nameimg);
                                $image_name = substr($images, 0, -($count + 1));
                                $item->image = $image_name . '_' . $img . '.' . $nameimg;
                            } else $item->image = '';
                        }
                    } else {
                        $item->image = '';
                    }
                }
                return $items;
            }
        }
        if ($content == 'joomla_content') {
            $query = "SELECT ct.*,ct.id as arid, ct.hits as hit, ct.title as artitle, ct.alias as aralias, cat.title as category_title, us.name as created_by_user, cat.alias as category_alias
                                     FROM #__content ct LEFT JOIN #__categories cat ON(ct.catid = cat.id)
                                     LEFT JOIN #__users us On(ct.created_by = us.id) where
                                    $where ";
            $db->setQuery($query);
            $items = $db->loadObjectList();
            $param_com = JComponentHelper::getComponent('com_content')->params;
            if ($items) {
                if ($content == 'joomla_content') {
                    foreach ($items as $item) {
                        $item->text = $item->introtext;
                        JPluginHelper::importPlugin('content');
                        $results = $dispatcher->trigger('onContentPrepare', array('com_content.article', &$item, &$param_com, 0));
                        $item->introtext = $item->text;
                        $item->event = new stdClass();
                        $results = $dispatcher->trigger('onContentAfterTitle', array('com_content.article', &$item, &$param_com, 0));
                        $item->event->afterDisplayTitle = trim(implode("\n", $results));
                        $results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.article', &$item, &$param_com, 0));
                        $item->event->beforeDisplayContent = trim(implode("\n", $results));
                        $results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.article', &$item, &$param_com, 0));
                        $item->event->afterDisplayContent = trim(implode("\n", $results));
                        $item->title = $item->artitle;
                        $item->media = null;
                        $item->hit = $item->hits;
                        $item->intro = $item->introtext;
                        $item->category = $item->category_title;
                        $item->author = $item->created_by_user;
                        $item->slug = $item->arid . ':' . $item->aralias;
                        $item->catslug = $item->catid . ':' . $item->category_alias;
                        $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
                        if ($item->images) {
                            $images = new JRegistry;
                            $images->loadString($item->images);
                            $images = json_decode($item->images);
                            $item->image = $images->image_intro;
                            $item->image_full = $images->image_fulltext;
                        } else {
                            $item->image = null;
                        }
                    }
                    return $items;
                }
            }
        }
        return false;
    }
}

?>
