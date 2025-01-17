<?php
/**
 * Class Api
 *
 * @package Admin\Model\User
 */
class ModelUserApi extends Model {
	/**
	 * addApi
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function addApi(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api` SET `username` = '" . $this->db->escape($data['username']) . "', `key` = '" . $this->db->escape($data['key']) . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW(), `date_modified` = NOW()");

		$api_id = $this->db->getLastId();

		if (isset($data['api_ip'])) {
			foreach ($data['api_ip'] as $ip) {
				if ($ip) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "api_ip` SET `api_id` = '" . (int)$api_id . "', `ip` = '" . $this->db->escape($ip) . "'");
				}
			}
		}

		return $api_id;
	}

	/**
	 * editApi
	 *
	 * @param int   $api_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editApi(int $api_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `username` = '" . $this->db->escape($data['username']) . "', `key` = '" . $this->db->escape($data['key']) . "', `status` = '" . (int)$data['status'] . "', `date_modified` = NOW() WHERE `api_id` = '" . (int)$api_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "api_ip` WHERE `api_id` = '" . (int)$api_id . "'");

		if (isset($data['api_ip'])) {
			foreach ($data['api_ip'] as $ip) {
				if ($ip) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "api_ip` SET `api_id` = '" . (int)$api_id . "', `ip` = '" . $this->db->escape($ip) . "'");
				}
			}
		}
	}

	/**
	 * deleteApi
	 *
	 * @param int $api_id
	 *
	 * @return void
	 */
	public function deleteApi(int $api_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "api` WHERE `api_id` = '" . (int)$api_id . "'");
	}

	/**
	 * getApi
	 *
	 * @param int $api_id
	 *
	 * @return array
	 */
	public function getApi(int $api_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `api_id` = '" . (int)$api_id . "'");

		return $query->row;
	}

	/**
	 * getApis
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getApis(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "api`";

		$sort_data = [
			'username',
			'status',
			'date_added',
			'date_modified'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `username`";
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
	 * getTotalApis
	 *
	 * @return int
	 */
	public function getTotalApis(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "api`");

		return (int)$query->row['total'];
	}

	/**
	 * addIp
	 *
	 * @param int    $api_id
	 * @param string $ip
	 *
	 * @return void
	 */
	public function addIp(int $api_id, string $ip): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_ip` SET `api_id` = '" . (int)$api_id . "', `ip` = '" . $this->db->escape($ip) . "'");
	}

	/**
	 * getIps
	 *
	 * @param int $api_id
	 *
	 * @return array
	 */
	public function getIps(int $api_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE `api_id` = '" . (int)$api_id . "'");

		return $query->rows;
	}

	/**
	 * addSession
	 *
	 * @param int    $api_id
	 * @param string $session_id
	 * @param string $ip
	 *
	 * @return int
	 */
	public function addSession(int $api_id, string $session_id, string $ip): int {
		$api_ip_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		if (!$api_ip_query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "api_ip` SET `api_id` = '" . (int)$api_id . "', `ip` = '" . $this->db->escape($ip) . "'");
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_session` SET `api_id` = '" . (int)$api_id . "', `session_id` = '" . $this->db->escape($session_id) . "', `ip` = '" . $this->db->escape($ip) . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * getSessions
	 *
	 * @param int $api_id
	 *
	 * @return array
	 */
	public function getSessions(int $api_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_session` WHERE `api_id` = '" . (int)$api_id . "'");

		return $query->rows;
	}

	/**
	 * deleteSession
	 *
	 * @param int $api_session_id
	 *
	 * @return void
	 */
	public function deleteSession(int $api_session_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE `api_session_id` = '" . (int)$api_session_id . "'");
	}

	/**
	 * deleteSessionBySessionId
	 *
	 * @param string $session_id
	 *
	 * @return void
	 */
	public function deleteSessionBySessionId(string $session_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE `session_id` = '" . $this->db->escape($session_id) . "'");
	}
}
