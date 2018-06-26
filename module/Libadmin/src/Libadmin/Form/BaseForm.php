<?php
namespace Libadmin\Form;

use Zend\Form\Form;

/**
 * Base form with convenience helpers
 *
 */
class BaseForm extends Form
{

	/**
	 * Add hidden field
	 *
	 * @param    String        $name
	 */
	protected function addHidden($name)
	{
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type' => 'hidden'
			)
		));
	}



	/**
	 * Add text field
	 *
	 * @param    String        $name
	 * @param    String        $label
	 * @param    Boolean        $required
	 */
	protected function addText($name, $label, $required = false)
	{
		$this->add(array(
			'name' => $name,
			'attributes' => array(
				'type' => 'text',
				'required' => !!$required
			),
			'options' => array(
				'label' => $label
			)
		));
	}

    /**
     * Add text field
     *
     * @param    String        $name
     * @param    String        $label
     * @param    array         $value_options
     */
    protected function addSelect($name, $label, $value_options, $required = false)
    {
        $this->add(array(
            'name' => $name,
            'type' => 'select',
            'attributes' => array(
                'required' => !!$required,
            ),
            'options' => array(
                'label' => $label,
                'value_options' => $value_options,
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ),
        ));
    }

    /**
     * Add text field
     *
     * @param    String        $name
     * @param    String        $label
     * @param    Boolean        $required
     */
    protected function addCheckbox($name, $label)
    {
        $this->add([
            'name' => $name,
            'type' => 'checkbox',
            'options' => [
                'label' => $label,
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ]
        ]);
    }
}
