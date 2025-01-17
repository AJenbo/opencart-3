<?php
/**
 * Class Wishlist
 *
 * @package Catalog\Model\Account
 */
class ModelAccountWishlist extends Model {
	/**
	 * addWishlist
	 *
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function addWishlist(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `product_id` = '" . (int)$product_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_wishlist` SET `customer_id` = '" . (int)$this->customer->getId() . "', `product_id` = '" . (int)$product_id . "', `date_added` = NOW()");
	}

	/**
	 * deleteWishlist
	 *
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function deleteWishlist(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * getWishlist
	 *
	 * @return array
	 */
	public function getWishlist(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->rows;
	}

	/**
	 * getTotalWishlist
	 *
	 * @return int
	 */
	public function getTotalWishlist(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");

		return (int)$query->row['total'];
	}
}
