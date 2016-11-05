<?php
namespace Alustau\App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


/**
 * Class ModelValidator
 * @package Alustau\API\Traits
 */
trait ModelValidator
{
    /**
     * @return mixed
     */

    public static function getRulesMsgs()
    {
        return (new static)->getRulesMessages();
    }
    /**
     * @return array
     */
    public function getRulesMessages()
    {
        return $this->rulesMessages;
    }

    /**
     * @param array $rulesMessages
     */
    public function setRulesMessages(array $rulesMessages)
    {
        $this->rulesMessages = $rulesMessages;
        return $this;
    }

    /**
     * @param $rule
     * @param $message
     * @return $this
     */
    public function addRuleMessage($rule, $message)
    {
        $this->rulesMessages[$rule] = $message;
        return $this;
    }

    /**
     * @param $rule
     * @return $this
     */
    public function removeRuleMessage($rule)
    {
        unset($this->rulesMessages[$rule]);
        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @param $field
     * @param $rule
     * @return $this
     */
    public function addRule($field, $rule)
    {
        $this->rules[$field] = $rule;
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function removeRule($field)
    {
        unset($this->rules[$field]);
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     */

    public function isValid($data)
    {
        $rules = $this->getRulesMessages();

        return Validator::make(
            $data,
            $this->getRules(),
            $rules ? $rules : []
        );
    }

    /**
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function validOrRedirect($data)
    {
        if ($data instanceof Request) {
            $data = $data->all();
        }

        $validation = $this->isValid($data);

        if ($validation->fails()) {
            return Redirect::back()
                ->withInput($data)
                ->withErrors($validation->errors()->toArray());
        }

        return true;
    }
}
