<?php
namespace Drupal\mymodule\Controller;

class FirstController {
    public function first(int $num1, int $num2) {
        return array (
            '#markup' => '<h2>'.$num1.' and '.$num2.'</h2>'
        );
    }
}