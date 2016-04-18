<?php

/**
 * Class ConsoleForm.
 */
class ConsoleForm extends Form
{
    /**
     * @var ContentController
     */
    protected $controller;

    /**
     * @param Controller $controller
     * @param string     $name
     */
    public function __construct(Controller $controller, $name)
    {
        $this->controller = $controller;

        $fields = $this->getCustomFields();
        $actions = $this->getCustomActions();
        $validator = $this->getCustomValidator($fields);

        parent::__construct($controller, $name, $fields, $actions, $validator);

        $this->setFormAction('/console/'.$name);
    }

    /**
     * @param array          $data
     * @param ConsoleForm    $form
     * @param SS_HTTPRequest $request
     */
    public function submitForm($data, ConsoleForm $form, SS_HTTPRequest $request)
    {
        //TODO : do something with data or form

        return $this->controller->redirect('/console/index/'.$data['Name']);
    }

    /**
     * @return FieldList
     */
    public function getCustomFields()
    {
        $fields = new FieldList(
            new TextField('Name', 'Name')
        );

        return $fields;
    }

    /**
     * @return FieldList
     */
    public function getCustomActions()
    {
        $fields = new FieldList(
            new FormAction('submitForm', _t('Site.Submit', 'Submit'))
        );

        return $fields;
    }

    public function getCustomValidator(FieldList $fields)
    {
        $required = [];

        $this->setRequiredFieldsLabels($required, $fields);

        return RequiredFields::create($required);
    }

    /**
     * Add required stars to field labels.
     *
     * @param array     $required
     * @param FieldList $fields
     */
    protected function setRequiredFieldsLabels($required, FieldList $fields)
    {
        foreach ((array) $required as $req) {
            $field = $fields->dataFieldByName($req);
            if ($field && $title = trim($field->Title())) {
                $this->setCustomValidationMessage($field, $title);
                $field->setTitle($title.' *');
            }
        }
    }

    /**
     * Add is $Field is required message.
     *
     * @param FormField $field
     * @param $title
     */
    protected function setCustomValidationMessage(FormField $field, $title)
    {
        if (!$field->getCustomValidationMessage()) {
            $message = strip_tags($title).' '._t('Site.IsRequired', ' is required');
            $field->setCustomValidationMessage($message);
        }
    }
}
