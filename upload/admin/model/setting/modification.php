<?php
/**
 * Class Modification
 *
 * @package Admin\Model\Setting
 */
class ModelSettingModification extends Model {
	/**
	 * addModification
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function addModification(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "modification` SET `extension_install_id` = '" . (int)$data['extension_install_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($data['code']) . "', `author` = '" . $this->db->escape($data['author']) . "', `version` = '" . $this->db->escape($data['version']) . "', `link` = '" . $this->db->escape($data['link']) . "', `xml` = '" . $this->db->escape($data['xml']) . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW()");
	}

	/**
	 * deleteModification
	 *
	 * @param int $modification_id
	 *
	 * @return void
	 */
	public function deleteModification(int $modification_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE `modification_id` = '" . (int)$modification_id . "'");
	}

	/**
	 * deleteModificationsByExtensionInstallId
	 *
	 * @param int $extension_install_id
	 *
	 * @return void
	 */
	public function deleteModificationsByExtensionInstallId(int $extension_install_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "modification` WHERE `extension_install_id` = '" . (int)$extension_install_id . "'");
	}

	/**
	 * enableModification
	 *
	 * @param int $modification_id
	 *
	 * @return void
	 */
	public function enableModification(int $modification_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET `status` = '1' WHERE `modification_id` = '" . (int)$modification_id . "'");
	}

	/**
	 * disableModification
	 *
	 * @param int $modification_id
	 *
	 * @return void
	 */
	public function disableModification(int $modification_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET `status` = '0' WHERE `modification_id` = '" . (int)$modification_id . "'");
	}

	/**
	 * getModification
	 *
	 * @param int $modification_id
	 *
	 * @return array
	 */
	public function getModification(int $modification_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "modification` WHERE `modification_id` = '" . (int)$modification_id . "'");

		return $query->row;
	}

	/**
	 * getModifications
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getModifications(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "modification`";

		$sort_data = [
			'name',
			'author',
			'version',
			'status',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * getTotalModifications
	 *
	 * @return int
	 */
	public function getTotalModifications(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "modification`");

		return (int)$query->row['total'];
	}

	/**
	 * getModificationByCode
	 *
	 * @param string $code
	 *
	 * @return array
	 */
	public function getModificationByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "modification` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}
}
