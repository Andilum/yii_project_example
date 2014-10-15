<?php

class Pagination extends CPagination {
    const DEFAULT_PAGE_VAR = 'page';

    /**
     * @var int
     */
    public $offset;

    public function getOffset() {
        if (!is_null($this->offset)) {
            return parent::getOffset() - $this->offset;
        }
        return parent::getOffset();
    }

    public function getPageCount() {
        if (!is_null($this->offset)) {
            return (int) (($this->offset + $this->getItemCount() + $this->getPageSize() - 1) / $this->getPageSize());
        }
        return parent::getPageCount();
    }
} 