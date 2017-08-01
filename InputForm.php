<?php 

class InputForm
{
  protected $errors         = array();
  protected $error_template = array('', '');
  protected $params         = array();

  public function __construct()
  {
    if (!empty($_GET)) {
      $this->params = array_merge($this->params, $_GET);
    }

    if (!empty($_POST)) {
      $this->params = array_merge($this->params, $_POST);
    }
  }

  public function validate($column_name, $condition)
  {
    $condition = explode('_', $condition);

    if (count($condition) != 2 && !in_array($this->available_condition)) {
      trigger_error('Validate condition not valid.', E_USER_ERROR);
    } else {
      if (!isset($this->params[$column_name])) {
        trigger_error('Column name not valid.', E_USER_ERROR);
      }

      if ($condition[0] === 'min' && $value < $condition[1]) {
        $this->set_errors('Minimum length of '.ucwords($column_name).' is '.$condition[1].' characters.', $column_name);
      } elseif ($condition[0] === 'max' && $value > $condition[1]) {
        $this->set_errors('Maximum length of '.ucwords($column_name).' is '.$condition[1].' characters.', $column_name);
      }
    }
  }

  public function set_errors($error_message, $key)
  {
    $this->errors[$key] = $error_message;
  }

  public function set_param($key, $value)
  {
    $this->params[$key] = $value;
  }

  public function set_params($data)
  {
    $this->params = array_merge($this->params, $data);
  }

  public function get_param($key)
  {
    return $this->params[$key];
  }

  public function get_params()
  {
    return $this->params;
  }

  public function get_errors()
  {
    return $this->errors;
  }

  public function get_errors_with_template()
  {
    if (!empty($this->errors)) {
      $errors = array();

      foreach ($this->errors as $key => $value) {
        $errors[] = $this->show_error($key);
      }

      return $errors;
    }
  }

  public function has_errors()
  {
    return (!empty($this->errors));
  }

  public function has_error($column_name) {
    return (!empty($this->errors[$column_name]));
  }

  public function show_error($column_name)
  {
    if (isset($this->errors[$column_name])) {
      return $this->error_template[0].' '.trim($this->errors[$column_name]).' '.$this->error_template[1];
    }
  }

  public function set_error_template($first, $second = '')
  {
    $this->error_template[0] = $first;
    $this->error_template[1] = $second;
  }
}