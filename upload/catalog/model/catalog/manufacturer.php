<?php
/**
 * Class Manufacturer
 *
 * @package Catalog\Model\Catalog
 */
class ModelCatalogManufacturer extends Model {
	/**
	 * getManufacturer
	 *
	 * @param int $manufacturer_id
	 *
	 * @return array
	 */
	public function getManufacturer(int $manufacturer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`) WHERE `m`.`manufacturer_id` = '" . (int)$manufacturer_id . "' AND `m2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}

	/**
	 * getManufacturers
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getManufacturers(array $data = []): array {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`) WHERE `m2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'";

			$sort_data = [
				'name',
				'sort_order'
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
		} else {
			$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));

			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`) WHERE `m2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `m`.`name`");

				$manufacturer_data = $query->rows;

				$this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
			}

			return $manufacturer_data;
		}
	}
}
