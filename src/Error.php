<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/7/6
 * Time: 下午12:56
 */

namespace BeeFood\Validate;

/**
 * 错误消息
 * Class Error
 * @revise : 2018-11-15 by eValor
 * @package EasySwoole\Validate
 */
class Error {
	private $field;
	private $fieldData;
	private $fieldAlias;
	private $errorRule;
	private $errorRuleMsg;
	private $errorRuleArg;
	private $lang;
	private $defaultErrorMsg;

	/**
	 * Error constructor.
	 * @param string $field 字段名称
	 * @param mixed  $fieldData 字段数据
	 * @param string $fieldAlias 字段别名
	 * @param string $errorRule 触发规则名
	 * @param string $errorRuleMsg 触发规则消息
	 * @param mixed  $errorRuleArg 触发规则参数
	 */
	function __construct($field, $fieldData, $fieldAlias, $errorRule, $errorRuleMsg, $errorRuleArg, $lang) {
		$this->field = $field;
		$this->fieldData = $fieldData;
		$this->fieldAlias = $fieldAlias;
		$this->errorRule = $errorRule;
		$this->errorRuleMsg = $errorRuleMsg;
		$this->errorRuleArg = $errorRuleArg;
		$this->lang = $lang;
		$this->loadErrorMsg();
	}

	/**
	 * 获取错误消息
	 * @return [type] [description]
	 */
	private function loadErrorMsg(): void{
		$file = LANG_PATH . $this->lang . '/validation.php';
		if (file_exists($file)) {
			$this->defaultErrorMsg = require $file;
		} else {
			throw new \Exception("ErrorMsg file : {$file} is miss");
		}
	}

	/**
	 * 获取字段名称
	 * @return string
	 */
	public function getField(): string {
		return $this->field;
	}

	/**
	 * 设置字段名称
	 * @param string $field
	 */
	public function setField(string $field): void{
		$this->field = $field;
	}

	/**
	 * 获取字段数据
	 * @return mixed
	 */
	public function getFieldData() {
		return $this->fieldData;
	}

	/**
	 * 设置字段数据
	 * @param mixed $fieldData
	 */
	public function setFieldData($fieldData): void{
		$this->fieldData = $fieldData;
	}

	/**
	 * 获取字段别名
	 * @return string
	 */
	public function getFieldAlias(): string {
		return $this->fieldAlias;
	}

	/**
	 * 设置字段别名
	 * @param string $fieldAlias
	 */
	public function setFieldAlias(string $fieldAlias): void{
		$this->fieldAlias = $fieldAlias;
	}

	/**
	 * 获取触发规则名
	 * @return string
	 */
	public function getErrorRule(): string {
		return $this->errorRule;
	}

	/**
	 * 设置触发规则名
	 * @param string $errorRule
	 */
	public function setErrorRule(string $errorRule): void{
		$this->errorRule = $errorRule;
	}

	/**
	 * 获取触发规则消息
	 * @return string
	 */
	public function getErrorRuleMsg(): string {
		if (!empty($this->errorRuleMsg)) {
			return $this->errorRuleMsg;
		} else {
			return $this->parserDefaultErrorMsg();
		}
	}

	/**
	 * 设置触发规则消息
	 * @param string $errorRuleMsg
	 */
	public function setErrorRuleMsg(string $errorRuleMsg): void{
		$this->errorRuleMsg = $errorRuleMsg;
	}

	/**
	 * 获取触发规则参数
	 * @return mixed
	 */
	public function getErrorRuleArg() {
		return $this->errorRuleArg;
	}

	/**
	 * 设置触发规则参数
	 * @param mixed $errorRuleArg
	 */
	public function setErrorRuleArg($errorRuleArg): void{
		$this->errorRuleArg = $errorRuleArg;
	}

	/**
	 * 组装默认错误消息
	 * @return mixed|string
	 */
	private function parserDefaultErrorMsg() {
		$fieldName = empty($this->fieldAlias) ? $this->field : $this->fieldAlias;
		if (!isset($this->defaultErrorMsg[$this->errorRule])) {
			var_dump($this->defaultErrorMsg);
			return "{$fieldName}参数错误";
		}
		$defaultErrorTpl = $this->defaultErrorMsg[$this->errorRule];
		$errorMsg = str_replace(':fieldName', $fieldName, $defaultErrorTpl);
		if (is_array($this->errorRuleArg)) {
			$arrayCheckFunc = ['inArray', 'notInArray'];
			if (in_array($this->errorRule, $arrayCheckFunc)) {
				$arrayValue = array_shift($this->errorRuleArg);
				$errorMsg = str_replace(":arg0", '[' . implode(',', $arrayValue) . ']', $errorMsg);
			} else {
				foreach ($this->errorRuleArg as $index => $arg) {
					$argValue = is_string($arg) ? $arg : json_encode($arg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
					$errorMsg = str_replace(":arg{$index}", $argValue, $errorMsg);
				}
			}
		} else {
			if (is_object($this->errorRuleArg)) {
				if (method_exists($this->errorRuleArg, '__toString')) {
					return str_replace(":arg0", $this->errorRuleArg->__toString(), $errorMsg);
				} else {
					return str_replace(":arg0", 'OBJECT', $errorMsg);
				}
			} else {
				$errorMsg = str_replace(":arg0", var_export($this->errorRuleArg, true), $errorMsg);
			}
		}
		return $errorMsg;
	}

	/**
	 * 返回错误消息
	 * @return string
	 */
	public function __toString(): string {
		return $this->getErrorRuleMsg();
	}
}