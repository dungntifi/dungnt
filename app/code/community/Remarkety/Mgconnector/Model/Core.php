<?php
/**
 * @copyright	Copyright (C) 2014 InteraMind Ltd (Remarkety). All rights reserved.
 * @license	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @package	This file is part of Magento Connector Plugin for Remarkety
 **/

define('REMARKETY_MGCONNECTOR_STATUS', 		'STATUS');
define('REMARKETY_MGCONNECTOR_CALLED_STATUS', 	'CALLED');
define('REMARKETY_MGCONNECTOR_SUCCESS_STATUS', 'SUCCESS');
define('REMARKETY_MGCONNECTOR_FAILED_STATUS', 	'FAILED');
define('REMARKETY_MGCONNECTOR_ERROR', 			'ERROR');
define('REMARKETY_MGCONNECTOR_DATA', 			'DATA');
define('REMARKETY_MGCONNECTOR_MAGE_VERSION', 	'VM_VERSION');
define('REMARKETY_MGCONNECTOR_EXT_VERSION', 	'PLUGIN_VERSION');

define('REMARKETY_LOG',					'remarkety_mgconnector.log');
define('REMARKETY_LOG_SEPARATOR', 		'|');

class Remarkety_Mgconnector_Model_Core extends Mage_Core_Model_Abstract {

	private $response_mask = array (
			'customer' => array (
					'entity_id',
					'firstname',
					'lastname',
					'email',
					'created_at',
					'updated_at',
					'is_active',
					'default_billing',
					'default_shipping',
					'registered_to_newsletter',
					'group_id',
					'group_name',
					'address' => array (
							'created_at',
							'updated_at',
							'is_active',
							'firstname',
							'lastname',
							'city',
							'country_id',
							'region',
							'postcode',
							'region_id',
							'street',
					)
			),
			'quote' => array (
					'entity_id',
					'created_at',
					'updated_at',
					'converted_at',
					'customer_id',
					'customer_is_guest',
					'customer_email',
					'customer_firstname',
					'customer_lastname',
					'is_active',
					'grand_total',
					'discount_amount',
					'base_subtotal',
					'base_subtotal_with_discount',
					'coupon_code',
					'quote_currency_code',
					'customer_registered_to_newsletter',
					'currency',
					'items' => array (
							'*' => array (
									'item_id',
									'parent_item_id',
									'categories',
									'qty',
									'base_price',
									'name',
									'sku',
									'created_at',
									'updated_at',
									'product_id',
									'type_id',
									'url_path'
							)
					)
			),
			'order' => array (
					'rem_main_increment_id',
					'increment_id',
					'entity_id',
					'grand_total',
					'shipping_amount',
					'coupon_code',
					'created_at',
					'updated_at',
					'order_currency_code',
					'customer_id',
					'customer_is_guest',
					'customer_email',
					'customer_firstname',
					'customer_lastname',
					'customer_group_id',
					'customer_group',
					'state',
					'status',
					'address',
					'currency',
					'discount_amount',
					'customer_registered_to_newsletter',
					'items' => array (
							'*' => array (
									'parent_item_id',
									'product_id',
									'qty_ordered',
									'base_price',
									'sku',
									'name',
									'created_at',
									'updated_at',
									'thumbnail',
									'categories',
									'type_id',
									'url_path'
							)
					)
			),
			'product' => array (
					'entity_id',
					'sku',
					'name',
					'created_at',
					'updated_at',
					'type_id',
					'thumbnail',
					'is_salable',
					'categories',
					'small_image',
					'price',
					'cost',
					'url_path'
			)
	);
	
	private function _log($function, $status, $message, $data) {
		$logMsg = implode(REMARKETY_LOG_SEPARATOR, array($function, $status, $message, serialize($data)));
		$force = ($status != REMARKETY_MGCONNECTOR_CALLED_STATUS);
		Mage::log($logMsg, null, REMARKETY_LOG, $force);
	}
	
	private function _wrapResponse($data, $status, $statusStr = null) {
		$ret = array();
		
		$ret[REMARKETY_MGCONNECTOR_DATA] = $data;
		$ret[REMARKETY_MGCONNECTOR_STATUS] = $status;
		$ret[REMARKETY_MGCONNECTOR_ERROR] = $statusStr;
		$ret[REMARKETY_MGCONNECTOR_EXT_VERSION] = (string) Mage::getConfig()->getNode()->modules->Remarkety_Mgconnector->version;
		$ret[REMARKETY_MGCONNECTOR_MAGE_VERSION] = Mage::getVersion();
		
		return $ret;
	}

	private function _store_views_in_group($group_id) {
		if (empty($group_id)) {
			return array(-1);
		} else {
			$group = Mage::getModel('core/store_group')->load($group_id);
			if (empty($group)) return array(-1);
			
			$codes = array_keys($group->getStoreCodes());
			return $codes;
		}
	}
	
	private function _filter_output_data($data, $field_set = array()) {
		if (empty($field_set)) return $data;
		
		foreach (array_keys($data) as $key) {
			if (isset($field_set[$key]) && is_array($field_set[$key])) {
				$data[$key] = $this->_filter_output_data($data[$key], $field_set[$key]);
			} else if (isset($field_set['*']) && is_array($field_set['*'])) {
				$data[$key] = $this->_filter_output_data($data[$key], $field_set['*']);
			} else {
				if (!in_array($key, $field_set)) unset ($data[$key]);
			}
		}
		return $data;
	}
	
	private function _productCategories($product_id) {
		$categoryCollection = Mage::getModel("catalog/product")
			->load($product_id)
			->getCategoryCollection()
			->addAttributeToSelect('name');
	
		$categories = array();

		foreach ($categoryCollection as $category) {
			$categories[] = $category->getData('name');
		}
	
		return $categories;
	}
	
	private function _currencyInfo($currencyCode) {
		$currencyInfo = array(
			'code' => Mage::app()->getLocale()->currency($currencyCode)->getShortName(),
			'name' => Mage::app()->getLocale()->currency($currencyCode)->getName(),
			'symbol' => Mage::app()->getLocale()->currency($currencyCode)->getSymbol()
		);
		
		return $currencyInfo;
	}

	public function getCustomers (
			$mage_store_group_id,
			$updated_at_min = null,
			$updated_at_max = null,
			$limit = null,
			$page = null,
			$since_id = null) {
		register_shutdown_function('handleShutdown');
		$ret = array();
		$pageNumber = null;
		$pageSize = null;
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$customersCollection = Mage::getModel("customer/customer")
				->getCollection()
				->addOrder('updated_at', 'ASC')
				->addAttributeToSelect('default_shipping')
				->addAttributeToSelect('default_billing')
				->addAttributeToSelect('firstname')
				->addAttributeToSelect('lastname')
				->addAttributeToSelect ('group_id');
	
			$store_views = $this->_store_views_in_group($mage_store_group_id);
			$customersCollection->addFieldToFilter('store_id', array('in' => $store_views));
			
			if ($updated_at_min != null) {
				$customersCollection->addAttributeToFilter('updated_at', array('gt' => $updated_at_min));
			}
		
			if ($updated_at_max != null) {
				$customersCollection->addAttributeToFilter('updated_at', array('lt' => $updated_at_max));
			}
		
			if ($since_id != null) {
				$customersCollection->addAttributeToFilter('entity_id', array('gt' => $since_id));
			}
		
			if ($limit != null) {
				$pageNumber = 1;		// Note that page numbers begin at 1 in Magento
				$pageSize = $limit;
			}
		
			if ($page != null) {
				if (!is_null($pageSize)) {
					$pageNumber = $page + 1;	// Note that page numbers begin at 1 in Magento
				}
			}
		
			if (!is_null($pageSize)) {
				$customersCollection->setPage($pageNumber, $pageSize);
			}
	
			$subscriberModel = Mage::getModel("newsletter/subscriber");
			$groupModel = Mage::getModel ( "customer/group" );

			/**
			 * 
			 * Special code to exclude Amazon's group shoppers
			 * You can un-comment this code and change the group name ("amazon") to fit your needs
			 */
			/*
			$ignoredGroupIds = $this->getIgnoredGroupIds("amazon");
			if (!empty($ignoredGroupIds))
				$customersCollection->addAttributeToFilter("group_id", array("nin"=>$ignoredGroupIds));
			 */	
			
			foreach ($customersCollection as $customer) {
				$customerData = $customer->toArray();
					
				$subscriberModel->loadByCustomer($customer);
				$customerData['registered_to_newsletter'] = $subscriberModel->isSubscribed();
				
				$groupModel->load ( $customer->getGroupId () );
				$groupName = $groupModel->getCustomerGroupCode ();
				$customerData ['group_name'] = $groupName;
				$customerData ['group_id'] = $customer->getGroupId ();
				
				$addresses_coll = $customer->getAddressesCollection();
				$addresses_array = $addresses_coll->toArray();

				if (!empty($addresses_array)) {
					if (array_key_exists($customerData['default_billing'], $addresses_array)) {
						$customerData['address_origin'] = 'billing';
						$customerData['address'] = $addresses_array[$customerData['default_billing']];
					} else if (in_array('default_shipping', $customerData) && array_key_exists($customerData['default_shipping'], $addresses_array)) {
						$customerData['address_origin'] = 'shipping';
						$customerData['address'] = $addresses_array[$customerData['default_shipping']];
					} else {
						$customerData['address_origin'] = 'first';
						$customerData['address'] = array_shift($addresses_array);
					}
				}

				$customerData = $this->_filter_output_data($customerData, $this->response_mask['customer']);
				$ret[] = $customerData;
			}

			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
		
		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	public function getOrders(
			$mage_store_group_id,
			$updated_at_min = null,
			$updated_at_max = null,
			$limit = null,
			$page = null,
			$since_id = null,
			$created_at_min = null,
			$created_at_max = null,
			$order_status = null,	// not implemented
			$order_id = null) {

		register_shutdown_function('handleShutdown');			
		$ret = array();
		$pageNumber = null;
		$pageSize = null;
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$ordersCollection = Mage::getModel("sales/order")
				->getCollection()
				->addOrder('updated_at', 'ASC')
				->addAttributeToSelect('*');
		
			$store_views = $this->_store_views_in_group($mage_store_group_id);
			$ordersCollection->addFieldToFilter('store_id', array('in' => $store_views));
					
			if ($updated_at_min != null) {
				$ordersCollection->addAttributeToFilter('updated_at', array('gt' => $updated_at_min));
			}
		
			if ($updated_at_max != null) {
				$ordersCollection->addAttributeToFilter('updated_at', array('lt' => $updated_at_max));
			}
		
			if ($since_id != null) {
				$ordersCollection->addAttributeToFilter('entity_id', array('gt' => $since_id));
			}
		
			if ($created_at_min != null) {
				$ordersCollection->addAttributeToFilter('created_at', array('gt' => $created_at_min));
			}
		
			if ($created_at_max != null) {
				$ordersCollection->addAttributeToFilter('created_at', array('lt' => $created_at_max));
			}
		
			if ($order_id != null) {
				$ordersCollection->addAttributeToFilter('entity_id', $order_id);
			}
		
			if ($limit != null) {
				$pageNumber = 1;		// Note that page numbers begin at 1
				$pageSize = $limit;
			}
		
			if ($page != null) {
				if (!is_null($pageSize)) {
					$pageNumber = $page + 1;	// Note that page numbers begin at 1
				}
			}
		
			if (!is_null($pageSize)) {
				$ordersCollection->setPage($pageNumber, $pageSize);
			}
	
			$subscriberModel = Mage::getModel("newsletter/subscriber");
			$groupModel = Mage::getModel ( "customer/group" );
			
			/**
			 * Special code to exclude Amazon's group shoppers
			 * You can un-comment this code and change the group name ("amazon") to fit your needs
			 */
			/*
			$ignoredGroupIds = $this->getIgnoredGroupIds("amazon");
			if (!empty($ignoredGroupIds))
				$ordersCollection->addAttributeToFilter("customer_group_id", array("nin"=>$ignoredGroupIds));
			*/
			
			foreach ($ordersCollection as $order) {
				$orderData = $order->toArray();
				if (!empty($orderData['relation_child_id'])) continue;
				$orderData['rem_main_increment_id'] = (empty($orderData['original_increment_id'])) ? $orderData['increment_id'] : $orderData['original_increment_id'];  
				$orderData['currency'] = $this->_currencyInfo($orderData['order_currency_code']);
				
				$subscriberModel->loadByEmail($orderData['customer_email']);
				$orderData['customer_registered_to_newsletter'] = $subscriberModel->isSubscribed();
				
				$addressID = null;
				
				if (isset($orderData['billing_address_id']) && $orderData['billing_address_id']) {
					$addressID = $orderData['billing_address_id'];
				} elseif (isset($orderData['shipping_address_id']) && $orderData['shipping_address_id']) {
					$addressID = $orderData['shipping_address_id'];
				}
	
				if (!empty($addressID)) {
					$address = Mage::getModel('sales/order_address')->load($addressID)->toArray();
					$orderData['address'] = $address;
				}
				
				foreach ($order->getItemsCollection() as $item) {
					$product = null;
					try{
						$itemData = $item->toArray();
						$product = Mage::getModel('catalog/product')->load($itemData['product_id']);
						if(empty($product)){
							continue;
						}
						
						$itemData['thumbnail'] = $product->getImageUrl();
						$itemData['type_id'] = $product->getData('type_id');
						$itemData['categories'] = $this->_productCategories($itemData['product_id']);
						$prodUrl = $this->getProdUrl($product);
						$itemData['url_path'] = $prodUrl;
				
						$orderData['items'][$itemData['item_id']] = $itemData;
					
					}catch (Exception $e){
						$this->_log(__FUNCTION__, 'Error in handling order items', $e->getMessage(), $myArgs); 
					}
				}

				$groupModel->load( $orderData['customer_group_id'] );
				$groupName = $groupModel->getCustomerGroupCode();
				$orderData['customer_group'] = $groupName;
				
				$orderData = $this->_filter_output_data($orderData, $this->response_mask['order']);
				$ret[] = $orderData;
			}

			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
		
		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
			
	public function getQuotes(
			$mage_store_group_id,
			$updated_at_min = null,
			$updated_at_max = null,
			$limit = null,
			$page = null,
			$since_id = null) {
		register_shutdown_function('handleShutdown');
		$pageNumber = null;
		$pageSize = null;
		$ret = array();
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$quotesCollection = Mage::getModel("sales/quote")
				->getCollection()
				->addOrder('updated_at', 'ASC')
				->addFieldToFilter('is_active', 1);
			
			$store_views = $this->_store_views_in_group($mage_store_group_id);
			$quotesCollection->addFieldToFilter('store_id', array('in' => $store_views));
			
			if ($updated_at_min != null) {
				$quotesCollection->addFieldToFilter('updated_at', array('gt' => $updated_at_min));
			}
		
			if ($updated_at_max != null) {
				$quotesCollection->addFieldToFilter('updated_at', array('lt' => $updated_at_max));
			}
		
			if ($since_id != null) {
				$quotesCollection->addFieldToFilter('entity_id', array('gt' => $since_id));
			}
		
			if ($limit != null) {
				$pageNumber = 1;		// Note that page numbers begin at 1
				$pageSize = $limit;
			}
		
			if ($page != null) {
				if (!is_null($pageSize)) {
					$pageNumber = $page + 1;	// Note that page numbers begin at 1
				}
			}
		
			if (!is_null($pageSize)) $quotesCollection->setPageSize($pageSize)->setCurPage($pageNumber);
	
			foreach ($quotesCollection as $quote) {
				$quoteData = $quote->toArray();

				//if (empty($quoteData['customer_email'])) continue;

				$currency = $quoteData['quote_currency_code'];
				$quoteData['currency'] = $this->_currencyInfo($currency);
				
				foreach ($quote->getItemsCollection() as $item) {
					$quoteItem = array();
					
					foreach ($this->response_mask['quote']['items']['*'] as $field) {
						$quoteItem[$field] = $item->getData($field);
					}
					$_product = Mage::getModel('catalog/product')->load($quoteItem['product_id']);
					if(!empty($_product)){
						$quoteItem['type_id'] = $_product->getData('type_id');
					}
					
					$quoteItem['categories'] = $this->_productCategories($quoteItem['product_id']);
					$prodUrl = $this->getProdUrl($_product);
					$quoteItem['url_path'] = $prodUrl;
						
					$quoteData['items'][$quoteItem['item_id']] = $quoteItem;
				}

				$quoteData = $this->_filter_output_data($quoteData, $this->response_mask['quote']);
				if (!empty($quoteData['items'])) $ret[] = $quoteData;
			}
			
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
		
		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	public function getProducts(
			$mage_store_view_id,
			$updated_at_min = null,
			$updated_at_max = null,
			$limit = null,
			$page = null,
			$handle = null,				// Not implemented
			$vendor = null,				// Not implemented
			$product_type = null,		// Not implemented
			$collection_id = null,		// Not implemented
			$since_id = null,
			$created_at_min = null,
			$created_at_max = null,
			$published_status = null,	// Not implemented
			$product_id = null) {
	
		register_shutdown_function('handleShutdown');
		$pageNumber = null;
		$pageSize = null;
		$ret = array();
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$productsCollection = Mage::getModel("catalog/product")
				->getCollection()
				->addOrder('updated_at', 'ASC')
				->addAttributeToSelect('*');

			$productsCollection->addStoreFilter($mage_store_view_id);
			
			if ($updated_at_min != null) {
				$productsCollection->addAttributeToFilter('updated_at', array('gt' => $updated_at_min));
			}
		
			if ($updated_at_max != null) {
				$productsCollection->addAttributeToFilter('updated_at', array('lt' => $updated_at_max));
			}
		
			if ($since_id != null) {
				$productsCollection->addAttributeToFilter('entity_id', array('gt' => $since_id));
			}
		
			if ($created_at_min != null) {
				$productsCollection->addAttributeToFilter('created_at', array('gt' => $created_at_min));
			}
		
			if ($created_at_max != null) {
				$productsCollection->addAttributeToFilter('created_at', array('lt' => $created_at_max));
			}
		
			if ($product_id != null) {
				$productsCollection->addAttributeToFilter('entity_id', $product_id);
			}
		
			if ($limit != null) {
				$pageNumber = 1;		// Note that page numbers begin at 1
				$pageSize = $limit;
			}
		
			if ($page != null) {
				if (!is_null($pageSize)) {
					$pageNumber = $page + 1;	// Note that page numbers begin at 1
				}
			}
		
			if (!is_null($pageSize)) $productsCollection->setPage($pageNumber, $pageSize);
	
			foreach ($productsCollection as $product) {
				$productData = $product->toArray();
				$productData['thumbnail'] = $product->getImageUrl();
				$productData['categories'] = array();
				$categoryCollection = $product->getCategoryCollection()->addAttributeToSelect('name');
				foreach ($categoryCollection as $category) {
					$productData['categories'][] = $category->getData('name');
				}
				$prodUrl = $this->getProdUrl($product);
				$productData['url_path'] = $prodUrl;
				
				$productData = $this->_filter_output_data($productData, $this->response_mask['product']);
				$ret[] = $productData;
			}

			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
		
		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	public function getStoreSettings($mage_store_view_id) {
		register_shutdown_function('handleShutdown');
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$currency = Mage::getStoreConfig('currency/options/default', $mage_store_view_id);
			$currencySymbol = Mage::app()->getLocale()->currency($currency)->getSymbol();
			$currencyFormat = Mage::getModel("directory/currency")->load($currency)->getOutputFormat();
	
			$ret = array(
					'domain' => Mage::getStoreConfig('web/unsecure/base_url', $mage_store_view_id),
					'name' => Mage::getStoreConfig('general/store_information/name', $mage_store_view_id),
					'phone' => Mage::getStoreConfig('general/store_information/phone', $mage_store_view_id),
					'contactEmail' => Mage::getStoreConfig('contacts/email/recipient_email', $mage_store_view_id),
					'timezone' => Mage::getStoreConfig('general/locale/timezone', $mage_store_view_id),
					'address' => Mage::getStoreConfig('general/store_information/address', $mage_store_view_id),
					'country' => Mage::getStoreConfig('general/store_information/merchant_country', $mage_store_view_id),
					'currency' => $currency,
					'money_format' => $currencySymbol,
					'money_with_currency_format' => $currencyFormat,
			);
			
			$wsCollection = Mage::getModel("core/website")->getCollection();
			foreach ($wsCollection as $ws) {
				$websiteArr = $ws->toArray();
				$groups = $ws->getGroupCollection();
				foreach ($groups as $group) {
					$groupArr = $group->toArray();
					$stores = $group->getStoreCollection();
					foreach ($stores as $store) {
						$groupArr['views'][] = $store->toArray();
					}
					$websiteArr['groups'][] = $groupArr;
				}
				$ret['websites'][] = $websiteArr;
			}
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		}
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
		
		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
		
	public function getStoreOrderStatuses($mage_store_view_id) {
		register_shutdown_function('handleShutdown');
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$statusesCollection = Mage::getModel('sales/order_status')->getCollection();
			$ret = array();
			foreach ($statusesCollection as $status) {
				$newStat = array();
				$newStat['status'] = $status->getData('status');
				$newStat['label'] = $status->getStoreLabel($mage_store_view_id); 
				$ret[] = $newStat;
			}
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}

		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	
	public function createCoupon($rule_id, $coupon_code, $expiration_date = null) {
		register_shutdown_function('handleShutdown');
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$rule = Mage::getModel('salesrule/rule')->load($rule_id);
			
			if (empty($rule)) {
				$msg = 'Given promotion ID does not exist';
				$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $msg, $myArgs); 
				return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $msg);			
			} 
			
			if (!($rule->getUseAutoGeneration())) {
				$msg = 'Promotion not configured for multiple coupons generation';
				$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $msg, $myArgs);
				return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $msg);
			}
		
			$coupon = Mage::getModel('salesrule/coupon')
				->setRule($rule)
				->setIsPrimary(false)
				->setCode($coupon_code)
				->setUsageLimit($rule->getUsesPerCoupon())
	            ->setUsagePerCustomer($rule->getUsesPerCustomer())
	            ->setType(Mage_SalesRule_Helper_Coupon::COUPON_TYPE_SPECIFIC_AUTOGENERATED);
	            
	        if($expiration_date != null){
	        	$coupon->setExpirationDate($expiration_date);
	        }else{
	        	$coupon->setExpirationDate($rule->getToDate());
	        }
			
			$coupon->save();
			
			$msg = "Successfuly created coupon code: ".$coupon_code." for rule id: ".$rule_id;
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, $msg, $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
		
		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	public function getCustomersCount($mage_store_group_id) {
		register_shutdown_function('handleShutdown');
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$store_views = $this->_store_views_in_group($mage_store_group_id);
			$customers = Mage::getModel("customer/customer")->getCollection();
			$customers->addFieldToFilter('store_id', array('in' => $store_views));
			$count = $customers->getSize();
			$ret = array('count' => $count);

			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}

		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	public function getOrdersCount($mage_store_group_id) {
		register_shutdown_function('handleShutdown');
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$store_views = $this->_store_views_in_group($mage_store_group_id);
			$orders = Mage::getModel("sales/order")->getCollection();
			$orders->addFieldToFilter('store_id', array('in' => $store_views));
			$count = $orders->getSize();
			$ret = array('count' => $count);

			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}

		catch (Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs);
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}
	
	public function getProductsCount($mage_view_id) {
		register_shutdown_function('handleShutdown');
		$myArgs = func_get_args();
		
		try {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_CALLED_STATUS, null, $myArgs);
			$products = Mage::getModel("catalog/product")->getCollection();
			$products->addStoreFilter($mage_view_id);
			$count = $products->getSize();
			$ret = array('count' => $count);

			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_SUCCESS_STATUS, null, '');
			return $this->_wrapResponse($ret, REMARKETY_MGCONNECTOR_SUCCESS_STATUS);
		} 
		
		catch (Mage_Core_Exception $e) {
			$this->_log(__FUNCTION__, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage(), $myArgs); 
			return $this->_wrapResponse(null, REMARKETY_MGCONNECTOR_FAILED_STATUS, $e->getMessage());
		}
	}	
	
	public function getProdUrl($product) {
		$url = $product->getProductUrl();
		if($product->type_id == 'configurable') {
			return $product->getProductUrl();
		}else{
			$configurable_product_model = Mage::getModel('catalog/product_type_configurable');
			$arrayOfParentIds = $configurable_product_model->getParentIdsByChild($product->getId());
			$parentId = (count($arrayOfParentIds) > 0 ? $arrayOfParentIds[0] : null);
			$url = $product->getProductUrl();
			if(!is_null($parentId)){
				$url = Mage::getModel("catalog/product")->load($parentId)->getProductUrl();
			}
		}
		return $url;
	}
}


function handleShutdown() {
	$error = error_get_last();
	$error_info = '';
	if($error !== NULL){
		$error_info = "[SHUTDOWN] file:".$error['file']." | ln:".$error['line']." | msg:".$error['message'] .PHP_EOL;
		Mage::log($error_info, null, REMARKETY_LOG, true);
	}
	else{
		Mage::log("SHUTDOWN", null, REMARKETY_LOG, true);
	}
}


