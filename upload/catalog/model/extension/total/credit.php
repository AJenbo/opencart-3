<?php
/**
 * Class Credit
 *
 * @package Catalog\Model\Extension\Total
 */
class ModelExtensionTotalCredit extends Model {
	/**
	 * getTotal
	 *
	 * @param array $total
	 */
	public function getTotal(array $total): void {
		$this->load->language('extension/total/credit');

		$balance = $this->customer->getBalance();

		if ((float)$balance) {
			$credit = min($balance, $total['total']);

			if ((float)$credit > 0) {
				$total['totals'][] = [
					'code'       => 'credit',
					'title'      => $this->language->get('text_credit'),
					'value'      => -$credit,
					'sort_order' => $this->config->get('total_credit_sort_order')
				];

				$total['total'] -= $credit;
			}
		}
	}

	/**
	 * Confirm
	 *
	 * @param array $order_info
	 * @param float $order_total
	 */
	public function confirm(array $order_info, float $order_total): void {
		$this->load->language('extension/total/credit');

		if ($order_info['customer_id']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$order_info['customer_id'] . "', `order_id` = '" . (int)$order_info['order_id'] . "', `description` = '" . $this->db->escape(sprintf($this->language->get('text_orders_id'), (int)$order_info['order_id'])) . "', `amount` = '" . (float)$order_total['value'] . "', `date_added` = NOW()");
		}
	}

	/**
	 * Unconfirm
	 *
	 * @param int $order_id
	 */
	public function unconfirm(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");
	}
}
