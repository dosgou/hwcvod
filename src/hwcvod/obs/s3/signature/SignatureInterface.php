<?php
namespace hwcvod\obs\s3\signature;

use hwcvod\obs\common\Model;

interface SignatureInterface{
	function doAuth(array &$requestConfig, array &$params, Model $model, array &$pathArg);
}