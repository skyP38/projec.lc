<?php


namespace Models;

class Crypt
{
  private array $register;
  private array $matrix;

  function __construct(array $register, array $matrix)
  {
    $this->register = $register;
    $this->matrix = $matrix;

  }

  public function encode(string $str) : string
  {
    $res = '';

    for ($i=0; $i < strlen($str); $i++) {
      $id = $this->getMatrixId(
        $str[$i]
        . $_SERVER['REQUEST_TIME_FLOAT']
        . rand(10000, 999999)
      );


      $self_matrix = $this->matrix[$id];
      $matrix_bin_id = $this->register[$id]['id'];

      $res .= $matrix_bin_id . $self_matrix[$str[$i]];
    }
    return $res;
  }

  public function decode(string $str) : string
  {
    $s ='';
    $sss ='';

    for ($i=0; $i < strlen($str)-3;) {
      $k = 0;
      $s = $str[$i] . $str[$i+1] . $str[$i+2] . $str[$i+3];
      foreach ($this->register as $key => $val) {
        foreach ($val as $id => $value) {
          if($value === $s){
            $k = $key;
          }
        }
      }
      $n = $this->register[$k]['count'];
      $i+=4;
      $st ='';
      for ($j=0; $j < $n; $j++) {
        $st .= $str[$i+$j];
      }

      foreach ($this->matrix[$k] as $key => $val) {
        if($val === $st){
          $sss .= $key;
        }
      }
      $i += $n;
    }
    return $sss;
  }

 function getMatrixId($str, $status = true)
  {
    if ($status) {
      $hash = sha1(md5($str));
      $hash = str_replace('a', '10', $hash);
      $hash = str_replace('b', '11', $hash);
      $hash = str_replace('c', '12', $hash);
      $hash = str_replace('d', '13', $hash);
      $hash = str_replace('e', '14', $hash);
      $hash = str_replace('f', '15', $hash);
    }else {
      $hash = $str;
    }

    $cs = 0;

    for ($i=0; $i < strlen($hash); $i++) {
      $cs += (int) $hash[$i];
    }

    if ($cs >= 10) {
      $cs = $this->getMatrixId('' . $cs, false);
      return $cs;
    }else {
      return $cs;
    }
  }
}


 ?>
