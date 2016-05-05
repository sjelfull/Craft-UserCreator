<?php
/**
 * User Creator plugin for Craft CMS
 *
 * Allow you to generate users en masse, simply.
 *
 * @author    Fred Carlsen
 * @copyright Copyright (c) 2016 Fred Carlsen
 * @link      http://sjelfull.no
 * @package   UserCreator
 * @since     1.0.0
 */

namespace Craft;

class UserCreatorPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init ()
    {
    }

    /**
     * @return mixed
     */
    public function getName ()
    {
        return Craft::t('User Creator');
    }

    /**
     * @return mixed
     */
    public function getDescription ()
    {
        return Craft::t('Allow you to generate users en masse, simply.');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl ()
    {
        return 'https://github.com/sjelfull/Craft-UserCreator/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl ()
    {
        return 'https://raw.githubusercontent.com/sjelfull/Craft-UserCreator/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion ()
    {
        return '1.0.1';
    }

    /**
     * @return string
     */
    public function getSchemaVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper ()
    {
        return 'Fred Carlsen';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl ()
    {
        return 'http://sjelfull.no';
    }

    /**
     * @return bool
     */
    public function hasCpSection ()
    {
        return true;
    }

    /**
     * @param mixed $settings The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings ($settings)
    {
        // Modify $settings here...

        return $settings;
    }

    public function registerCpRoutes ()
    {
        return array(
            'usercreator'        => array( 'action' => 'UserCreator/index' ),
            'usercreator/result' => array( 'action' => 'UserCreator/result' ),
        );
    }

}
