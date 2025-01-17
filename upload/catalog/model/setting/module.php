<?php
/**
 * Class Module
 *
 * @package Catalog\Model\Setting
 */
class ModelSettingModule extends Model {
	/**
	 * getModule
	 *
	 * @param int $module_id
	 *
	 * @return array
	 */
	public function getModule(int $module_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

		if ($query->row) {
			return json_decode($query->row['setting'], true);
		} else {
			return [];
		}
	}
}
