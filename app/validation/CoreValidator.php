<?php

class CoreValidator extends Illuminate\Validation\Validator
{

    protected $implicitRules = array('Required', 'RequiredWith', 'RequiredWithout', 'RequiredIf', 'Accepted', 'RequiredWithoutField');

    public function __construct(\Symfony\Component\Translation\TranslatorInterface $translator, $data, $rules, $messages = array())
    {
        parent::__construct($translator, $data, $rules, $messages);
        $this->isImplicit('fail');
    }

    public function validateCaptcha($attribute, $value, $parameters = null)
    {
        return !strcasecmp($value, Session::get('captcha'));
    }
}