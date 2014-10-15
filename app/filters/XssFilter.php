<?php

/**
 *
 * Фильтр предназначен для фильтрации входных данных, c целью предотвратить xss атаки.
 * @example
 *
 *  public function filters()
 *  {
 *         return array(
 *                 array('application.filters.XssFilter',
 *                       'clean' => 'all'
 *                 )
 *         );
 *
 *   }
 *
 *   В качетве параметра 'clean' могут быть:
 *  - 'all' - фильтруются GET,POST,COOKIE,FILES массивы;
 *  - '*'   - аналог ALL;
 *  - так же возможно сочетание любых из параметров, например GET,COOKIE или POST,FILES  
 */
class XssFilter extends CFilter {

    public $clean = 'all';

    protected function preFilter($filterChain) {
        $this->clean = trim(strtoupper($this->clean));
        $data = array(
            'GET' => &$_GET,
            'POST' => &$_POST,
            'COOKIE' => &$_COOKIE,
            'FILES' => &$_FILES
        );

        if ($this->clean === 'ALL' || $this->clean === '*') {
            $this->clean = 'GET,POST,COOKIE,FILES';
        }

        $dataForClean = explode(',', $this->clean);
        if (count($dataForClean)) {
            foreach ($dataForClean as $key => $value) {
                if (isset($data[$value]) && count($data[$value])) {
                    $this->doXssClean($data[$value]);
                }
            }
        }

        return true;
    }

    protected function postFilter($filterChain) {
        // logic being applied after the action is executed
    }

    private function doXssClean(&$data) {
        if (is_array($data) && count($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->doXssClean($v);
            }
            return $data;
        }

        if (trim($data) === '') {
            return $data;
        }

        $p = new CHtmlPurifier;
        $data = $p->purify($data);
        return $data;
    }

}

