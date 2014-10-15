<?php

class AllocationRatingList extends CWidget {
    const DEFAULT_ALLOCATION_LIMIT = 5;

    /**
     * @var int
     */
    public $count;

    public function init() {
        if (is_null($this->count)) {
            $this->count = self::DEFAULT_ALLOCATION_LIMIT;
        }
    }

    public function run() {
        $command = Yii::app()->getDb()->createCommand("
            SELECT
                t.id,
                t.name,
                coalesce(als.subscriber_count, 0) AS subscriber_count,
                re.name AS resort_name,
                co.name AS country_name,
                alloccat.name AS alloccat_name
            FROM
                dict.dict_allocation t
                LEFT JOIN (
                    SELECT allocation_id, COUNT(*) AS subscriber_count
                    FROM hi.hi_allocation_subscription als
                    WHERE als.trash = false
                    GROUP BY allocation_id
                ) als ON als.allocation_id = t.id
                LEFT JOIN dict.dict_resort re ON (t.resort = re.id)
                LEFT JOIN dict.dict_country co ON (re.country = co.id)
                LEFT JOIN dict.dict_alloccat alloccat ON (t.cat = alloccat.id)
            ORDER BY subscriber_count DESC, t.name ASC
            LIMIT {$this->count}
        ");
        $allocations = $command->queryAll();

        $this->render('allocationRatingList', array(
            'allocations' => $allocations,
        ));
    }
} 