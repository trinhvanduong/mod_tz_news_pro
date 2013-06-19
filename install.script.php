<?php



defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');


class PlgSystemInstallerInstallerScript
{

    private $sourcedir = '';
    private $manifest = '';
    private $extensions = array();

    /**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
        $this->installExtensions($parent);
	}

    /*function install($parent)
    {

    }*/

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
        $parent->getParent()->abort();
	}

    private function installExtensions($parent)
    {
        $src = $parent->getParent()->getPath('source');
        $xml = $parent->getParent()->getManifest();
        $db = JFactory::getDbo();
        $status = array();
        $buffer = '';

        // Opening HTML
        ob_start();
    ?>
    <div id="xei-logo">
        <ul id="xei-status">
    <?php
        $buffer .= ob_get_clean();
        if ( count($xml->extlist->children()) )
        {
            foreach ($xml->extlist->children() as $ext)
            {
                $path = $src . '/' . $ext->folder;
                $type = $ext->attributes()->type;

                if (is_dir($path))
                {
                    // Was the extension already installed?
                    // we'll set the query based on ext type
                    switch ($type)
                    {
                        case 'module':
                        case 'library':
                            $query = $db->qn('element').' = '.$db->q($ext->folder);
                            break;
                        case 'plugin':
                            $query = array();
                            $query[] = $db->qn('element').' = '.$db->q(str_replace('plg_','',$ext->folder));
                            $query[] = $db->qn('folder').' = '.$db->q($ext->attributes()->group);
                            break;
                        case 'template':
                            $folder = str_replace('tpl_','', $ext->folder);
                            $query = $db->qn('element').' = '.$db->q($folder);
                            break;
                    }

                    $sql = $db->getQuery(true)
                        ->select('COUNT(*)')
                        ->from('#__extensions')
                        ->where($query);

                    $db->setQuery($sql);
                    $count = $db->loadResult();

                    // if extension is found on database then its upgrade state
                    if( $count ) $state = 'update';
                    else $state = 'install';

                    //take new installer instance for installing sub extensions
                    $installer = new JInstaller;
                    $result = $installer->install($path);;

//                    if($result)
//                    {
//                        $version = $installer->getManifest()->version;
//                        if($state == 'install')
//                        {
//                            $buffer .= $this->printInstall($ext->name, $version);
//                        }elseif($state == 'update')
//                        {
//                            $buffer .= $this->printUpdate($ext->name, $version);
//                        }
//                    }

                    // We'll publish the plugin if it set to enable
                    if( $type == 'plugin' AND $ext->attributes()->enabled == 'true')
                    {
                        $query = array();
                        $query[] = $db->qn('element').' = '.$db->q(str_replace('plg_','',$ext->folder));
                        $query[] = $db->qn('folder').' = '.$db->q($ext->attributes()->group);

                        $sql = $db->getQuery(true)
                            ->update('#__extensions')
                            ->where($query)
                            ->set('enabled = 1');

                        $db->setQuery($sql)
                            ->execute();
                    }

                }
            }
        }

    // Closing HTML
        ob_start();
    ?>
        </ul>
    </div>
    <?php
        $buffer .= ob_get_clean();
        // Return stuff
        echo $this->getCSS();
        echo $buffer;
    }

    public function printInstall($name, $version)
    {
        ob_start();
        ?>
    <li class="xei-success">
        <span class="icon"></span><?php echo $name;?> installed successfully <span class="version">v <?php echo $version?></span>
    </li>
    <?php
            $out = ob_get_clean();
        return $out;
    }

    public function printUpdate($name, $version)
    {
        ob_start();
        ?>
    <li class="xei-update">
        <span class="icon"></span><?php echo $name;?> updated successfully <span class="version">v <?php echo $version?></span>
    </li>
    <?php
            $out = ob_get_clean();
        return $out;
    }

    function getCSS()
    {
        $css = "


            #xei-status {list-style:none; padding:20px 20px 5px; width: 600px; margin: 10px auto; font-size: 16px; color: #fff; box-shadow: 0 0 10px #ddd inset; text-shadow: 1px 1px 1px #888;}
            #xei-status li{padding: 6px 20px 6px 10px; margin-bottom: 15px; line-height: 28px;}
            #xei-status li.xei-success {background-color: #24a324;}
            #xei-status li.xei-update {background-color: #97da15;}
            #xei-status span.version{float: right; background: #c0da15; padding: 0 5px; color: #333; text-shadow: 1px 1px 0 #fff; border-radius: 3px; font-size: 12px; line-height: 20px;margin-top: 4px;box-shadow: 0 0 2px #000 inset; font-weight: bold;}
        ";

        return '<style>' . $css . '</style>';
    }
}
