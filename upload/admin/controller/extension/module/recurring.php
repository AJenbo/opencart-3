<?php
/**
 * Class Recurring
 *
 * @package Admin\Controller\Extension\Module
 */
class ControllerExtensionModuleRecurring extends Controller {
	private array $error = [];

	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/module/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$filter_order_recurring_id = $this->request->get['filter_order_recurring_id'];
		} else {
			$filter_order_recurring_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_reference'])) {
			$filter_reference = $this->request->get['filter_reference'];
		} else {
			$filter_reference = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 0;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_recurring_id';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . $this->request->get['filter_reference'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true)
		];

		$data['action'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['recurrings'] = [];

		$filter_data = [
			'filter_order_recurring_id' => $filter_order_recurring_id,
			'filter_order_id'           => $filter_order_id,
			'filter_reference'          => $filter_reference,
			'filter_customer'           => $filter_customer,
			'filter_status'             => $filter_status,
			'filter_date_added'         => $filter_date_added,
			'order'                     => $order,
			'sort'                      => $sort,
			'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                     => $this->config->get('config_limit_admin')
		];

		$recurrings_total = $this->model_extension_module_recurring->getTotalRecurrings($filter_data);

		$results = $this->model_extension_module_recurring->getRecurrings($filter_data);

		foreach ($results as $result) {
			if ($result['status']) {
				$status = $this->language->get('text_status_' . $result['status']);
			} else {
				$status = '';
			}

			$data['recurrings'][] = [
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id'           => $result['order_id'],
				'reference'          => $result['reference'],
				'customer'           => $result['customer'],
				'status'             => $status,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'               => $this->url->link('extension/module/recurring/info', 'user_token=' . $this->session->data['user_token'] . '&order_recurring_id=' . $result['order_recurring_id'] . $url, true),
				'order'              => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'], true)
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . urlencode(html_entity_decode($this->request->get['filter_reference'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order_recurring'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=or.order_recurring_id' . $url, true);
		$data['sort_order'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=or.order_id' . $url, true);
		$data['sort_reference'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=or.reference' . $url, true);
		$data['sort_customer'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=or.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&sort=or.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . urlencode(html_entity_decode($this->request->get['filter_reference'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new \Pagination();
		$pagination->total = $recurrings_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . '&page={page}' . $url, true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($recurrings_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recurrings_total - $this->config->get('config_limit_admin'))) ? $recurrings_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recurrings_total, ceil($recurrings_total / $this->config->get('config_limit_admin')));

		$data['filter_order_recurring_id'] = $filter_order_recurring_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_reference'] = $filter_reference;
		$data['filter_customer'] = $filter_customer;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['recurring_statuses'] = [];

		$data['recurring_statuses'][0] = [
			'text'  => '',
			'value' => 0
		];

		for ($i = 1; $i <= 6; $i++) {
			$data['recurring_statuses'][$i] = [
				'text'  => $this->language->get('text_status_' . $i),
				'value' => $i,
			];
		}

		$data['report'] = $this->url->link('extension/module/recurring/getReport', 'user_token=' . $this->session->data['user_token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/recurring', $data));
	}

	/**
	 * Info
	 *
	 * @return \Action|object|null
	 */
	public function info(): ?object {
		$this->load->model('extension/module/recurring');

		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = (int)$this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}

		$order_recurring_info = $this->model_extension_module_recurring->getRecurring($order_recurring_id);

		if ($order_recurring_info) {
			$this->load->language('extension/module/recurring');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['user_token'] = $this->session->data['user_token'];

			$url = '';

			if (isset($this->request->get['filter_order_recurring_id'])) {
				$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_reference'])) {
				$url .= '&filter_reference=' . $this->request->get['filter_reference'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true)
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_info'),
				'href' => $this->url->link('extension/module/recurring/info', 'user_token=' . $this->session->data['user_token'] . $url, true)
			];

			$data['cancel'] = $this->url->link('extension/module/recurring', 'user_token=' . $this->session->data['user_token'] . $url, true);

			// Recurring
			$data['order_recurring_id'] = $order_recurring_info['order_recurring_id'];
			$data['reference'] = $order_recurring_info['reference'];
			$data['recurring_name'] = $order_recurring_info['recurring_name'];
			$data['recurring_description'] = $order_recurring_info['recurring_description'];

			if ($order_recurring_info['status']) {
				$data['recurring_status'] = $this->language->get('text_status_' . $order_recurring_info['status']);
			} else {
				$data['recurring_status'] = '';
			}

			// Orders
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_recurring_info['order_id']);

			$data['payment_method'] = $order_info['payment_method'];

			// Order
			$data['order_id'] = $order_info['order_id'];
			$data['order'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_info['order_id'], true);
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}

			$data['email'] = $order_info['email'];
			$data['order_status'] = $order_info['order_status'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			// Product
			$data['product'] = $order_recurring_info['product_name'];
			$data['quantity'] = $order_recurring_info['product_quantity'];

			// Transactions
			$data['transactions'] = [];

			$transactions = $this->model_extension_module_recurring->getRecurringTransactions($order_recurring_info['order_recurring_id']);

			foreach ($transactions as $transaction) {
				$data['transactions'][] = [
					'date_added' => $transaction['date_added'],
					'type'       => $transaction['type'],
					'amount'     => $this->currency->format($transaction['amount'], $order_info['currency_code'], $order_info['currency_value'])
				];
			}

			$data['buttons'] = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/recurringButtons');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('extension/module/recurring_info', $data));
		} else {
			return new \Action('error/not_found');
		}

		return null;
	}

	/**
	 * getReport
	 *
	 * @return object\Action|null
	 */
	public function getReport(): ?object {
		$this->load->language('extension/module/recurring');

		$data['title'] = $this->language->get('text_report');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if ($this->user->hasPermission('modify', 'extension/module/recurring')) {
			// GDPR
			$this->load->model('customer/gdpr');

			// Products
			$this->load->model('catalog/product');

			// Recurring
			$this->load->model('extension/module/recurring');

			$frequencies = [
				'day'        => $this->language->get('text_day'),
				'week'       => $this->language->get('text_week'),
				'semi_month' => $this->language->get('text_semi_month'),
				'month'      => $this->language->get('text_month'),
				'year'       => $this->language->get('text_year')
			];

			$gdpr_data = [];

			// As per GDPR law, only one store per organization with the same party
			// or the same party from multiple stores of the same organization can export
			// customers' data
			if ($selected) {
				$expires = $this->model_customer_gdpr->getExpires();

				foreach ($expires as $expire) {
					$gdpr_data[] = "DATE(`or`.`date_added`) > DATE('" . $this->db->escape(date('Y-m-d', strtotime($expire['date_added']))) . "')";
				}
			}

			$data['recurrings'] = [];

			$order_recurring_data = [];

			foreach ($selected as $order_recurring_id) {
				$order_recurring_data[] = "`or`.`order_recurring_id` = '" . (int)$order_recurring_id . "'";
			}

			if ($order_recurring_data && $gdpr_data) {
				// Only pull unique order recurring and order
				// since it is not possible to create multiple identical orders
				// for the same subscription as it is not possible to create
				// multiple subscriptions for the same order.
				$transactions = $this->db->query("SELECT `oh`.`order_recurring_id` FROM `" . DB_PREFIX . "order_recurring_history` `oh` LEFT JOIN `" . DB_PREFIX . "order_recurring` `or` ON (`oh`.`order_recurring_id` = `or`.`order_recurring_id`) WHERE (" . implode(" OR ", $order_recurring_data) . ") AND (" . implode(" OR ", $gdpr_data) . ") GROUP BY `oh`.`order_recurring_id`, `or`.`order_id` ORDER BY `or`.`date_added` ASC");

				if ($transactions->num_rows) {
					foreach ($transactions->rows as $transaction) {
						$order_recurring_info = $this->model_extension_module_recurring->getRecurring($transaction['order_recurring_id']);

						if ($order_recurring_info && $order_recurring_info['status']) {
							$product_info = $this->model_catalog_product->getProduct($order_recurring_info['product_id']);

							if ($product_info) {
								$recurring = '';

								if ($order_recurring_info['recurring_duration']) {
									$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($order_recurring_info['recurring_price'] * $order_recurring_info['product_quantity'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency')), $order_recurring_info['recurring_cycle'], $frequencies[$order_recurring_info['recurring_frequency']], $order_recurring_info['recurring_duration']);
								} else {
									$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($order_recurring_info['recurring_price'] * $order_recurring_info['product_quantity'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency')), $order_recurring_info['recurring_cycle'], $frequencies[$order_recurring_info['recurring_frequency']], $order_recurring_info['recurring_duration']);
								}

								$data['recurrings'][] = [
									'store_name' => $this->config->get('config_name'),
									'recurring'  => $recurring
								];
							}
						}
					}
				}
			}

			$this->response->setOutput($this->load->view('extension/module/recurring_report', $data));
		} else {
			return new \Action('error/permission');
		}

		return null;
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('extension/module/recurring');

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring'");

		if (!$query->num_rows) {
			$this->session->data['error'] = $this->language->get('error_warning');
		} else {
			// Settings
			$this->load->model('setting/setting');

			$post_data = [
				'module_recurring_status' => 1
			];

			$this->model_setting_setting->editSetting('module_recurring', $post_data);
		}
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		// Settings
		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('module_recurring');
	}
}
