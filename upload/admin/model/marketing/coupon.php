<?php
/**
 * Class Coupon
 *
 * @package Admin\Model\Marketing
 */
class ModelMarketingCoupon extends Model {
	/**
	 * addCoupon
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function addCoupon(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `type` = '" . $this->db->escape($data['type']) . "', `total` = '" . (float)$data['total'] . "', `logged` = '" . (int)$data['logged'] . "', `shipping` = '" . (int)$data['shipping'] . "', `date_start` = '" . $this->db->escape($data['date_start']) . "', `date_end` = '" . $this->db->escape($data['date_end']) . "', `uses_total` = '" . (int)$data['uses_total'] . "', `uses_customer` = '" . (int)$data['uses_customer'] . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW()");

		$coupon_id = $this->db->getLastId();

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_product` SET `coupon_id` = '" . (int)$coupon_id . "', `product_id` = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_category` SET `coupon_id` = '" . (int)$coupon_id . "', `category_id` = '" . (int)$category_id . "'");
			}
		}

		return $coupon_id;
	}

	/**
	 * editCoupon
	 *
	 * @param int   $coupon_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editCoupon(int $coupon_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "coupon` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `type` = '" . $this->db->escape($data['type']) . "', `total` = '" . (float)$data['total'] . "', `logged` = '" . (int)$data['logged'] . "', `shipping` = '" . (int)$data['shipping'] . "', `date_start` = '" . $this->db->escape($data['date_start']) . "', `date_end` = '" . $this->db->escape($data['date_end']) . "', `uses_total` = '" . (int)$data['uses_total'] . "', `uses_customer` = '" . (int)$data['uses_customer'] . "', `status` = '" . (int)$data['status'] . "' WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_product` SET `coupon_id` = '" . (int)$coupon_id . "', `product_id` = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_category` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_category` SET `coupon_id` = '" . (int)$coupon_id . "', `category_id` = '" . (int)$category_id . "'");
			}
		}
	}

	/**
	 * deleteCoupon
	 *
	 * @param int $coupon_id
	 *
	 * @return void
	 */
	public function deleteCoupon(int $coupon_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_category` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_history` WHERE `coupon_id` = '" . (int)$coupon_id . "'");
	}

	/**
	 * getCoupon
	 *
	 * @param int $coupon_id
	 *
	 * @return array
	 */
	public function getCoupon(int $coupon_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "coupon` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		return $query->row;
	}

	/**
	 * getCouponByCode
	 *
	 * @param string $code
	 *
	 * @return array
	 */
	public function getCouponByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "coupon` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * getCoupons
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getCoupons(array $data = []): array {
		$sql = "SELECT `coupon_id`, `name`, `code`, `discount`, `date_start`, `date_end`, `status` FROM `" . DB_PREFIX . "coupon`";

		$sort_data = [
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
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
	 * getProducts
	 *
	 * @param int $coupon_id
	 *
	 * @return array
	 */
	public function getProducts(int $coupon_id): array {
		$coupon_product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_product` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_product_data[] = $result['product_id'];
		}

		return $coupon_product_data;
	}

	/**
	 * getCategories
	 *
	 * @param int $coupon_id
	 *
	 * @return array
	 */
	public function getCategories(int $coupon_id): array {
		$coupon_category_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon_category` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		foreach ($query->rows as $result) {
			$coupon_category_data[] = $result['category_id'];
		}

		return $coupon_category_data;
	}

	/**
	 * getTotalCoupons
	 *
	 * @return int
	 */
	public function getTotalCoupons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon`");

		return (int)$query->row['total'];
	}

	/**
	 * getHistories
	 *
	 * @param int $coupon_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getHistories(int $coupon_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ch`.`order_id`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `ch`.`amount`, `ch`.`date_added` FROM `" . DB_PREFIX . "coupon_history` `ch` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ch`.`customer_id` = `c`.`customer_id`) WHERE `ch`.`coupon_id` = '" . (int)$coupon_id . "' ORDER BY `ch`.`date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * getTotalHistories
	 *
	 * @param int $coupon_id
	 *
	 * @return int
	 */
	public function getTotalHistories(int $coupon_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "coupon_history` WHERE `coupon_id` = '" . (int)$coupon_id . "'");

		return (int)$query->row['total'];
	}
}
