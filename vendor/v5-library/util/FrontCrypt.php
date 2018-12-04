<?php
/**
 * create by 2017-01-15 pm 14:48
 * @author clevercloud@qq.com
 * 简单加解密
 */
namespace V5\Library\Util;

class FrontCrypt {
	/**
	 * 秘钥
	 * @var {String}
	 */
	private $key;
	public function __construct($key) {
		$this -> key = base64_encode($key ? $key : "");
	}

	/**
	 * 加密
	 * @param  {String} [$pData] 明文
	 * @return {String} 密文
	 */
	public function encrypt($pData) {
		$data = self::stringToBinaryArray(base64_encode($pData));
		$key = self::stringToBinaryArray($this -> key);
		for ($i = 0, $dLen = count($data), $kLen = count($key); $i < $dLen; $i++) {
			$data[$i] += $key[$i % $kLen];
		}
		$data = self::binaryArrayToString($data);
		return base64_encode($data);
	}

	/**
	 * 解密
	 * @param  {String} [$strData] 密文
	 * @return {String} 明文
	 */
	public function decrypt($strData) {
		$data = self::stringToBinaryArray(base64_decode($strData));
		$key = self::stringToBinaryArray($this -> key);
		for ($i = 0, $dLen = count($data), $kLen = count($key); $i < $dLen; $i++) {
			$data[$i] -= $key[$i % $kLen];
		}
		$data = self::binaryArrayToString($data);
		return base64_decode($data);
	}

	/**
	 * 字符串转二进制数组
	 * @param  {String} [$str]
	 * @return {Array}
	 */
	public static function stringToBinaryArray($str) {
		$len = strlen($str);
		$count = 0;
		$arr = array();
		while ($count < $len) {
			$ud = 0;
			if (ord($str{$count}) >= 0 && ord($str{$count}) <= 127) {
				$ud = ord($str{$count});
				$count += 1;
			} else if (ord($str{$count}) >= 192 && ord($str{$count}) <= 223) {
				$ud = (ord($str{$count}) - 192) * 64 + (ord($str{$count + 1}) - 128);
				$count += 2;
			} else if (ord($str{$count}) >= 224 && ord($str{$count}) <= 239) {
				$ud = (ord($str{$count}) - 224) * 4096 + (ord($str{$count + 1}) - 128) * 64 + (ord($str{$count + 2}) - 128);
				$count += 3;
			} else if (ord($str{$count}) >= 240 && ord($str{$count}) <= 247) {
				$ud = (ord($str{$count}) - 240) * 262144 + (ord($str{$count + 1}) - 128) * 4096 + (ord($str{$count + 2}) - 128) * 64 + (ord($str{$count + 3}) - 128);
				$count += 4;
			} else if (ord($str{$count}) >= 248 && ord($str{$count}) <= 251) {
				$ud = (ord($str{$count}) - 248) * 16777216 + (ord($str{$count + 1}) - 128) * 262144 + (ord($str{$count + 2}) - 128) * 4096 + (ord($str{$count + 3}) - 128) * 64 + (ord($str{$count + 4}) - 128);
				$count += 5;
			} else if (ord($str{$count}) >= 252 && ord($str{$count}) <= 253) {
				$ud = (ord($str{$count}) - 252) * 1073741824 + (ord($str{$count + 1}) - 128) * 16777216 + (ord($str{$count + 2}) - 128) * 262144 + (ord($str{$count + 3}) - 128) * 4096 + (ord($str{$count + 4}) - 128) * 64 + (ord($str{$count + 5}) - 128);
				$count += 6;
			} else if (ord($str{$count}) >= 254 && ord($str{$count}) <= 255) {//error
				$ud = false;
			}
			$arr[] = $ud;
		}
		return $arr;
	}
	/**
	 * 二进制数组转字符串
	 * @param  {Array}  [$arr]
	 * @return {String}
	 */
	public static function binaryArrayToString($arr) {
		$str = "";
		foreach ($arr as $val) {
			if ($val < 128) {
				$str .= chr($val);
			} else if ($val < 2048) {
				$str .= chr(192 + (($val - ($val % 64)) / 64));
				$str .= chr(128 + ($val % 64));
			} else {
				$str .= chr(224 + (($val - ($val % 4096)) / 4096));
				$str .= chr(128 + ((($val % 4096) - ($val % 64)) / 64));
				$str .= chr(128 + ($val % 64));
			}
		}
		return $str;
	}


}
?>