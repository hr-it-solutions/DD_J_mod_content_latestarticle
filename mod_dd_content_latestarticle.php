<?php
/**
 * @package    DD_Mod_Content_LatestArticle
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

JLoader::register('Mod_Content_LatestArticle_Helper', __DIR__ . '/helper.php');

// Check for a custom CSS file
JHtml::_('stylesheet', 'mod_dd_content_latestarticle/user.css', array('version' => 'auto', 'relative' => true));

require_once JModuleHelper::getLayoutPath('mod_dd_content_latestarticle', $params->get('layout', 'default'));
