<?php
/**
 * Class Country
 *
 * @package Catalog\Model\Localisation
 */
class ModelLocalisationCountry extends Model {
	/**
	 * getCountry
	 *
	 * @param int $country_id
	 *
	 * @return array
	 */
	public function getCountry(int $country_id): array {
		$query = $this->db->query("SELECT *, `c`.`name` FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "address_format` `af` ON (`c`.`address_format_id` = `af`.`address_format_id`) WHERE `c`.`country_id` = '" . (int)$country_id . "' AND `c`.`status` = '1'");

		return $query->row;
	}

	/**
	 * getCountryByIsoCode2
	 *
	 * @param $iso_code_2
	 *
	 * @return array
	 */
	public function getCountryByIsoCode2(string $iso_code_2): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($iso_code_2) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * getCountryByIsoCode3
	 *
	 * @param $iso_code_3
	 *
	 * @return array
	 */
	public function getCountryByIsoCode3(string $iso_code_3): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_3` = '" . $this->db->escape($iso_code_3) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * getCountries
	 *
	 * @return array
	 */
	public function getCountries(): array {
		$country_data = $this->cache->get('country.catalog');

		if (!$country_data) {
			$query = $this->db->query("SELECT *, `c`.`name` FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "address_format` `af` ON (`c`.`address_format_id` = `af`.`address_format_id`) WHERE `c`.`status` = '1' ORDER BY `c`.`name` ASC");

			$country_data = $query->rows;

			$this->cache->set('country.catalog', $country_data);
		}

		return $country_data;
	}
}
