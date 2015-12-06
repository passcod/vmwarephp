<?php
namespace Vmwarephp;

class SoapClient extends \SoapClient {

	function __construct($a,$b)
	{

		if(!isset($b['stream_context']))
		{

			$b['stream_context'] = stream_context_create(array(
			    'ssl' => array(
			        'verify_peer' => false,	
			        'allow_self_signed' => true
			    )
			));

		}


		parent::__construct($a, $b);

		return $this;

	}

	function __doRequest($request, $location, $action, $version, $one_way = 0) {
		$request = $this->appendXsiTypeForExtendedDatastructures($request);
		$result = parent::__doRequest($request, $location, $action, $version, $one_way);
		if (isset($this->__soap_fault) && $this->__soap_fault) {
			throw $this->__soap_fault;
		}
		return $result;
	}

	/* PHP does not provide inheritance information for wsdl types so we have to specify that its and xsi:type
	 * php bug #45404
	 * */
	private function appendXsiTypeForExtendedDatastructures($request) {
		return $request = str_replace(array("xsi:type=\"ns1:TraversalSpec\"", '<ns1:selectSet />'), array("xsi:type=\"ns1:TraversalSpec\"", ''), $request);
	}
}
