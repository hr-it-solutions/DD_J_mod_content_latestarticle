<?php
/**
 * @package    DD_Mod_Content_LatestArticle
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

/**
 * Helper for mod_content_latestarticle
 *
 * @since  Version 1.0.0.0
 */
class Mod_Content_LatestArticle_Helper
{
	protected $app;

	protected $db;

	protected $params;

	/**
	 * getLatestArticles
	 *
	 * @since Version 1.0.0.0
	 *
	 * @return object
	 */
	public function getLatestArticles()
	{
		$this->app = JFactory::getApplication();
		$this->db = JFactory::getDbo();

		$module = JModuleHelper::getModule('mod_dd_content_latestarticle');
		$this->params = new JRegistry($module->params);

		$query = $this->db->getQuery(true);

		$select = $this->db->qn(
			array(
				'a.id',
				'a.catid',
				'a.images',
				'a.title'
			)
		);

		$query->select($select)
			->from($this->db->qn('#__content', 'a'));

		$query->where($this->db->qn('a.published') . ' = 1');

		if ($this->params->get('associated_article_mode') === '1')
		{
			if ($this->getAssociatedItem() !== false)
			{
				// Exclude active article id
				$query->where(
					$this->db->qn('a.id') . ' <> ' .
					(int) $this->getAssociatedItem()['inputID']
				);

				// Only associated category articles
				$query->where(
					$this->db->qn('a.catid') . ' = ' .
					(int) $this->getAssociatedItem()['catID']
				);
			};
		}

		$query->order('a.id DESC LIMIT 0, 3');

		// Set this query using query object
		$this->db->setQuery($query);

		// Return Object List
		return $this->db->loadObjectList();
	}

	/**
	 * getAssociatedItem
	 *
	 * @since  Version 1.0.0.0
	 *
	 * @return mixed
	 */
	private function getAssociatedItem()
	{
		if ($this->app->input->getCmd('option') === 'com_content')
		{
			if ($this->app->input->getCmd('view') === 'article')
			{
				$inputId = (int) $this->app->input->getCmd('id');

				// Create a new query object
				$query = $this->db->getQuery(true);

				$query->select($this->db->qn('catid'))
					->from($this->db->qn('#__content'))
					->where($this->db->qn('id') . '=' . $inputId);

				$this->db->setQuery($query);

				return array(
					'catID' => $this->db->loadResult(),
					'inputID' => $inputId
				);
			}
		}

		return false;
	}
}
