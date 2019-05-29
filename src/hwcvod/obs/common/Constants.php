<?php
namespace hwcvod\obs\common;

class Constants
{
	
	const METADATA_PREFIX = 'x-amz-meta-';
	const AMAZON_HEADER_PREFIX = 'x-amz-';
	const ALTERNATIVE_DATE_HEADER = 'x-amz-date';
	
	const ALLOWED_RESOURCE_PARAMTER_NAMES = [
			'acl',
			'policy',
			'torrent',
			'logging',
			'location',
			'storageinfo',
			'quota',
			'storagepolicy',
			'requestpayment',
			'versions',
			'versioning',
			'versionid',
			'uploads',
			'uploadid',
			'partnumber',
			'website',
			'notification',
			'lifecycle',
			'deletebucket',
			'delete',
			'cors',
			'restore',
			'tagging',
			'response-content-type',
			'response-content-language',
			'response-expires',
			'response-cache-control',
			'response-content-disposition',
			'response-content-encoding',
			'x-image-process'
	];
	
	const  ALLOWED_REQUEST_HTTP_HEADER_METADATA_NAMES = [
		'content-type',
		'content-md5',
		'content-length',
		'content-language',
		'expires',
		'origin',
		'cache-control',
		'content-disposition',
		'content-encoding',
		'access-control-request-method',
		'access-control-request-headers',
		'x-default-storage-class',
		'location',
		'date',
		'etag',
		'range',
		'host',
		'if-modified-since',
		'if-unmodified-since',
		'if-match',
		'if-none-match',
		'last-modified',
		'content-range'
	];
			
	const ALLOWED_RESPONSE_HTTP_HEADER_METADATA_NAMES = [
		'content-type',
		'content-md5',
		'content-length',
		'content-language',
		'expires',
		'origin',
		'cache-control',
		'content-disposition',
		'content-encoding',
		'x-default-storage-class',
		'location',
		'date',
		'etag',
		'host',
		'last-modified',
		'content-range',
		'x-reserved',
		'access-control-allow-origin',
		'access-control-allow-headers',
		'access-control-max-age',
		'access-control-allow-methods',
		'access-control-expose-headers',
		'connection'
	];
	
	const COMMON_HEADERS = [
		'content-length' => 'ContentLength',
		'date' => 'Date',
		'x-amz-request-id' => 'RequestId',
		'x-amz-id-2' => 'Id2',
		'x-reserved' => 'Reserved'
	];
	
}