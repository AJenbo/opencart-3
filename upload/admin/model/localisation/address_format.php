<?php
/**
 * Class Address Format
 *
 * @package Admin\Model\Localisation
 */
class ModelLocalisationAddressFormat extends Model {
	/**
	 * addAddressFormat
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function addAddressFormat(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "address_format` SET `name` = '" . $this->db->escape($data['name']) . "', `address_format` = '" . $this->db->escape($data['address_format']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * editAddressFormat
	 *
	 * @param int   $address_format_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editAddressFormat(int $address_format_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "address_format` SET `name` = '" . $this->db->escape($data['name']) . "', `address_format` = '" . $this->db->escape($data['address_format']) . "' WHERE `address_format_id` = '" . (int)$address_format_id . "'");
	}

	/**
	 * deleteAddressFormat
	 *
	 * @param int $address_format_id
	 *
	 * @return void
	 */
	public function deleteAddressFormat(int $address_format_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "address_format` WHERE `address_format_id` = '" . (int)$address_format_id . "'");
	}

	/**
	 * getAddressFormat
	 *
	 * @param int $address_format_id
	 *
	 * @return array
	 */
	public function getAddressFormat(int $address_format_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "address_format` WHERE `address_format_id` = '" . (int)$address_format_id . "'");

		return $query->row;
	}

	/**
	 * getAddressFormats
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getAddressFormats(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "address_format`";

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
	 * getTotalAddressFormats
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalAddressFormats(array $data = []): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address_format`");

		return (int)$query->row['total'];
	}
}
