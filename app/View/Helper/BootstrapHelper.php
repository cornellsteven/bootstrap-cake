<?php

App::uses('AppHelper', 'View/Helper');

class BootstrapHelper extends AppHelper {
    
    public $helpers = array('Html', 'Form');
    
    public function btnGroup($field, $params = array()) {
        $defaults = array(
            'label' => ucwords(str_replace('_', ' ', $field)),
            'options' => array(0 => 'No', 1 => 'Yes'),
            'default' => 0,
        );
        
        // Merge defaults and passed options
        $params = array_merge($defaults, $params);
        
        // Get the model (if passed) and field
        $_field = $this->_getField($field);
        $model = Inflector::classify($this->request->params['controller']);
        $_value = ( isset($this->request->data[$model][$_field]) ? $this->request->data[$model][$_field] : $params['default'] );
        
        $html  = '<div class="input btn-group-input">' . "\n";
        $html .= $this->Form->hidden($field, array('value' => $_value, 'id' => false));
        
        if ($params['label']) {
            $html .= $this->Form->label($field, $params['label']);
        }
        
        $html .= "\n" . '<div class="btn-group" data-toggle="buttons">' . "\n";
        
        foreach ($params['options'] as $key => $value) {
            $class = '';
            
            // check for active option
            if ($model) {
                if (isset($this->request->data[$model][$_field]) && $this->request->data[$model][$_field] !== NULL) {
                    if ($this->request->data[$model][$_field] == $key) {
                        $checked = true;
                        $class = 'active';
                    }
                } else {
                    if ($params['default'] == $key) {
                        $class = 'active';
                    }
                }
            }
            
            $html .= '<label class="btn btn-default ' . $class . '">' . "\n";
            $html .= $this->Form->radio($field, array($key => $value), array('label' => false, 'div' => false, 'legend' => false, 'value' => $_value)) . "\n";
            $html .= "</label>\n";
        }
        $html .= "</div>\n</div>\n";
    
        return $html;
    }
    
    public function editable($data, $field, $params = array(), $display = NULL) {
        $controller = isset($params['model']) ? Inflector::tableize($params['model']) : $this->request->params['controller'];
        $model = isset($params['model']) ? $params['model'] : Inflector::classify($controller);
        
        if (isset($params['model'])) {
            unset($params['model']);
        }
        
        if ( ! isset($data[$model])) {
            $data[$model] = $data;
        }
        
        $defaults = array(
            'id' => 'Editable' . Inflector::camelize($field),
            'data-type' => 'text',
            'data-name' => $field,
            'data-pk' => $data[$model]['id'],
            'data-url' => $this->Html->url(array('controller' => $controller, 'action' => 'post')),
            'data-title' => 'Enter ' . ucwords(str_replace('_', ' ', $field)),
            'class' => 'x-editable',
        );
        
        // Merge defaults and passed options
        $params = array_merge($defaults, $params);
        
        // Get the proper value to display
        if ($display === NULL) {
            $display = $data[$model][$field];
        }
        
        // Selects
        if ($params['data-type'] == 'select') {
            if ( ! isset($params['data-value'])) {
                $params['data-value'] = $data[$model][$field];
            }
            
            if ( ! isset($params['data-source'])) {
                $_controller = $controller;
                if (substr($field, -3) == '_id') {
                    $_controller = Inflector::tableize(Inflector::camelize(substr($field, 0, -3)));
                }
                $params['data-source'] = $this->Html->url(array('controller' => $_controller, 'action' => 'index', 'json' => true));
            }
        }
        
        return $this->Html->link($display, '#', $params);
    }
    
    public function editableSelect($data, $field, $display, $params = array()) {
        $params['data-type'] = 'select';
        return $this->editable($data, $field, $params, $display);
    }
    
    public function formGroup($field, $label = NULL, $params = array(), $addon = NULL, $addafter = NULL) {
        if ($params === NULL || ! is_array($params)) {
            $params = array();
        }
        
        $labelcols = isset($params['label-cols']) ? $params['label-cols'] : 3;
        $inputcols = isset($params['input-cols']) ? $params['input-cols'] : ( 12 - $labelcols );
        unset($params['label-cols'], $params['input-cols']);
        
        $defaults = array(
            'div' => 'form-group',
            'class' => 'form-control',
            'label' => array(
                'text' => $label,
                'class' => 'control-label col-md-' . $labelcols,
            ),
            'before' => NULL,
            'between' => '<div class="col-md-' . $inputcols . '">',
            'after' => '</div>',
        );
        
        // Input Group, too?
        if ($addon !== NULL || $addafter !== NULL) {
            $defaults['between'] .= '<div class="input-group">';
            
            if ($addon !== NULL) {
                $defaults['between'] .= '<span class="input-group-addon">' . $addon . '</span>';
            }
        
            if ($addafter !== NULL) {
                $defaults['after'] = '<span class="input-group-addon">' . $addafter . '</span>' . $defaults['after'];
            }
            
            $defaults['after'] .= '</div>';
        }
        
        // Input class
        if (isset($params['class'])) {
            $defaults['class'] .= ' ' . $params['class'];
            unset($params['class']);
        }
        
        // Merge defaults and passed options
        $params = array_merge($defaults, $params);
        
        return $this->Form->input($field, $params);
    }
    
    public function inputGroup($field, $params = array(), $addon = '$', $addafter = NULL) {
        if ($params === NULL || ! is_array($params)) {
            $params = array();
        }
        
        // If no addon or addafter was passed, return a normal element
        if ($addon === NULL && $addafter === NULL) {
            return $this->Form->input($field, $params);
        }
        
        // Should the addon be before the input, after the input, or both
        $after = $before = '';
        if ($addon !== NULL) {
            $before = '<span class="input-group-addon">' . $addon . '</span>';
        }
        
        if ($addafter !== NULL) {
            $after = '<span class="input-group-addon">' . $addafter . '</span>';
        }
        
        $defaults = array(
            'class' => 'form-control',
            'between' => '<div class="input-group">' . $before,
            'after' => $after . '</div>',
        );
        
        // Input class
        if (isset($params['class'])) {
            $defaults['class'] .= ' ' . $params['class'];
            unset($params['class']);
        }
        
        // Merge defaults and passed options
        $params = array_merge($defaults, $params);
        
        return $this->Form->input($field, $params);
    }
    
    private function _getField($field) {
        $field = explode('.', $field);
        return $field[count($field) - 1];
    }
    
}

?>