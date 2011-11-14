<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
* oData.gr Library Class
*
* This CI library is simply a wrapper class for oData API.
*
* The oData API let's you search for items available via the Greek Price API
* using the query syntax and formats in the Open Data Protocol. Most common 
* oData scenarios are based on structured databases where resources, collections
* are easily identified. In this particular scenario there are some restrictions 
* from the native API side like retrieving items as root collection without
* providing a keyword with at least two characters. To learn more about the oData
* protocol, you can browse the oData site.
*
*
* @package CodeIgniter
* @subpackage Libraries
* @author Vasilis Lolos <vlolos@me.com>
* @copyright Copyright (c) 2011, https://github.com/lolos/oData.gr-Codeigniter/
*
*/

// ------------------------------------------------------------------------

class Odata
{
	private static $base_url = 'http://epriceservice.cloudapp.net/PriceServices.svc/';

	public function api_call($name, $id=NULL, $resource=NULL)
	{
		if (! empty($id) )
		{
			$name = "$name($id)";
		}

		$requestUrl = sprintf('%s%s?$format=json', self::$base_url, $name);

		if ( extension_loaded('curl') )
		{
			$ch = curl_init() or die(curl_error());

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_URL, $requestUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

			$response = curl_exec($ch);

			curl_close( $ch );

			if ( $response )
			{
				$data = json_decode($response);

				return $data->d;
			}
		}
	}

	// Category - Defines a product category for each product.
	public function category($id=NULL, $resource=NULL)
	{
		return $this->api_call('Category', $id, $resource);
	}

	// Poi - Defines a point of interest that currently can either be a supermarket or a gas station.
	public function poi($id=NULL, $resource=NULL)
	{
		return $this->api_call('Poi', $id, $resource);
	}

	// PoiCompany - Declares the association of each company to specific Poi's, usually supermarket chains or gas stations of a particular brand. 
	public function poi_company($id=NULL, $resource=NULL)
	{
		return $this->api_call('PoiCompany', $id, $resource);
	}
	
	// PoiProductPrice - Cardinality defining the association of each product's price in a particular point of sale. For instance specific milk in a particular supermarket.
	public function poi_product_price($id=NULL, $resource=NULL)
	{
		return $this->api_call('PoiProductPrice', $id, $resource);
	}

	// Product - A product description. 
	public function product($id=NULL, $resource=NULL)
	{
		return $this->api_call('Product', $id, $resource);
	}

	// Product - Associates products with companies. 
	public function product_company($id=NULL, $resource=NULL)
	{
		return $this->api_call('ProductCompany', $id, $resource);
	}
	
	// Territory - Describes areas like municipalities, counties and prefectures. Includes self joint references.
	public function territory($id=NULL, $resource=NULL)
	{
		return $this->api_call('Territory', $id, $resource);
	}
}

?>