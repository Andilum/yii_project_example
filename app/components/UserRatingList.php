<?php

class UserRatingList extends CWidget {
    const DEFAULT_USER_LIMIT = 5;

    /**
     * @var int
     */
    public $count;

    public function init() {
        if (is_null($this->count)) {
            $this->count = self::DEFAULT_USER_LIMIT;
        }
    }

    public function run() {
        $command = Yii::app()->getDb()->createCommand("
            SELECT
                t.id,
                t.nick,
                coalesce(sub.subscriber_count, 0) AS subscriber_count,
                co.name AS country_name,
                ct.name AS city_name
            FROM
                tp.tp_user t
                LEFT JOIN (
                    SELECT tp_user_id, COUNT(*) AS subscriber_count
                    FROM hi.hi_user_subscription
                    WHERE trash = false
                    GROUP BY tp_user_id
                ) sub ON sub.tp_user_id = t.id
                LEFT OUTER JOIN dict.dict_country co ON (t.country = co.id)
                LEFT OUTER JOIN dict.dict_city ct ON (t.city = ct.id)
            ORDER BY subscriber_count DESC, t.nick ASC
            LIMIT {$this->count}
        ");
        $users = $command->queryAll();

        $this->render('userRatingList', array(
            'users' => $users,
        ));
    }
} 