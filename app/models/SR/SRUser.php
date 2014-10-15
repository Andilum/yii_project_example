<?php

class SRUser extends SR {
    /**
     * @return array
     */
    public function attributeNames() {
        return array(
            'id',
            'nick',
            'name',
            'surname',
            'city_name',
            'country_name',
            'subscriber_count',
            'post_count',
            'photo_count',
            'like_count',
        );
    }

    /**
     * @return mixed
     */
    public function getTotalCount() {
        return Yii::app()->getDb()->createCommand("
            SELECT COUNT(id)
            FROM (
                SELECT
                    t.id,
                    coalesce(sub.subscriber_count, 0) AS subscriber_count,
                    coalesce(p.post_count, 0) AS post_count,
                    coalesce(pt.photo_count, 0) AS photo_count,
                    coalesce(l.like_count, 0) AS like_count
                FROM (
                    SELECT id, city, country
                    FROM tp.tp_user
                    WHERE trash = false AND active = true {$this->_getNameCondition()}
                ) AS t
                LEFT JOIN (
                    SELECT
                        tp_user_id,
                        COUNT(*) AS subscriber_count
                    FROM hi.hi_user_subscription us
                    WHERE us.trash = false
                    GROUP BY tp_user_id
                ) sub ON sub.tp_user_id = t.id
                LEFT JOIN (
                    SELECT
                        tp_user_id,
                        COUNT(id) AS post_count
                    FROM hi.hi_post p
                    WHERE p.trash = false
                    GROUP BY tp_user_id
                ) p ON p.tp_user_id = t.id
                LEFT JOIN (
                    SELECT
                        ut.tp_user_id,
                        COUNT(ut.id) as photo_count
                    FROM hi.hi_photo pt
                    LEFT JOIN (
                        SELECT p.id, p.tp_user_id
                        FROM hi.hi_post p
                        WHERE p.trash = false
                        UNION
                        SELECT c.id, c.tp_user_id
                        FROM hi.hi_comment c
                        LEFT JOIN hi.hi_post p2 ON p2.id = c.post_id
                        WHERE c.trash = false AND p2.trash = false
                    ) ut ON ut.id = pt.owner_id
                    WHERE pt.trash = false
                    GROUP BY ut.tp_user_id
                ) pt ON pt.tp_user_id = t.id
                LEFT JOIN (
                    SELECT
                        tp_user_id,
                        COUNT(id) as like_count
                    FROM hi.hi_like l
                    WHERE l.trash = false
                    GROUP BY tp_user_id
                ) l ON l.tp_user_id = t.id
                WHERE 1 = 1 {$this->_getNullResultsCondition()}
            ) t
        ")->queryScalar();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return \CArrayDataProvider
     */
    public function get($limit = self::DEFAULT_LIMIT, $offset = 0) {
        $sort = $this->_getSort();

        $command = Yii::app()->getDb()->createCommand("
            SELECT
                t.id,
                t.nick,
                t.name,
                t.surname,
                ct.name AS city_name,
                co.name AS country_name,
                coalesce(sub.subscriber_count, 0) AS subscriber_count,
                coalesce(p.post_count, 0) AS post_count,
                coalesce(pt.photo_count, 0) AS photo_count,
                coalesce(l.like_count, 0) AS like_count
            FROM (
                SELECT id, nick, name, surname, city, country
                FROM tp.tp_user
                WHERE trash = false AND active = true {$this->_getNameCondition()}
            ) AS t
            LEFT JOIN dict.dict_city ct ON (t.city = ct.id)
            LEFT JOIN dict.dict_country co ON (t.country = co.id)
            LEFT JOIN (
                SELECT
                    tp_user_id,
                    COUNT(*) AS subscriber_count
                FROM hi.hi_user_subscription us
                WHERE us.trash = false
                GROUP BY tp_user_id
            ) sub ON sub.tp_user_id = t.id
            LEFT JOIN (
                SELECT
                    tp_user_id,
                    COUNT(id) AS post_count
                FROM hi.hi_post p
                WHERE p.trash = false
                GROUP BY tp_user_id
            ) p ON p.tp_user_id = t.id
            LEFT JOIN (
                SELECT
                    ut.tp_user_id,
                    COUNT(ut.id) as photo_count
                FROM hi.hi_photo pt
                LEFT JOIN (
                    SELECT p.id, p.tp_user_id
                    FROM hi.hi_post p
                    WHERE p.trash = false
                    UNION
                    SELECT c.id, c.tp_user_id
                    FROM hi.hi_comment c
                    LEFT JOIN hi.hi_post p2 ON p2.id = c.post_id
                    WHERE c.trash = false AND p2.trash = false
                ) ut ON ut.id = pt.owner_id
                WHERE pt.trash = false
                GROUP BY ut.tp_user_id
            ) pt ON pt.tp_user_id = t.id
            LEFT JOIN (
                SELECT
                    tp_user_id,
                    COUNT(id) as like_count
                FROM hi.hi_like l
                WHERE l.trash = false
                GROUP BY tp_user_id
            ) l ON l.tp_user_id = t.id
            WHERE 1 = 1 {$this->_getNullResultsCondition()}
        ");

        $dataProvider = new CSqlDataProvider($command, array(
            'keyField' =>'id',
            'totalItemCount' => $this->getTotalCount(),
            'pagination' => array(
                'class' => 'Pagination',
                'pageSize' => $limit,
                'pageVar' => Pagination::DEFAULT_PAGE_VAR,
            ),
            'sort' => $sort,
        ));

        return $dataProvider;
    }

    /**
     * @return CSort
     */
    protected function _getSort() {
        if ( $this->_sort ) {
            return $this->_sort;
        }

        $this->_sort = new CSort();

        $this->_sort->defaultOrder = 'subscriber_count DESC, post_count DESC, photo_count DESC, like_count DESC';
        $this->_sort->attributes = array(
            'tc' => array(
                'asc' => 'subscriber_count ASC, post_count ASC, photo_count ASC, like_count ASC',
                'desc' => 'subscriber_count DESC, post_count DESC, photo_count DESC, like_count DESC',
                'label' => 'по сводному рейтингу'
            ),
            'pc' => array(
                'asc' => 'post_count ASC',
                'desc' => 'post_count DESC',
                'label' => 'по контенту',
            ),
            'lc' => array(
                'asc' => 'like_count ASC',
                'desc' => 'like_count DESC',
                'label' => 'по лайкам',
            ),
            'sc' => array(
                'asc' => 'subscriber_count ASC',
                'desc' => 'subscriber_count DESC',
                'label' => 'по подписчикам',
            ),
        );

        return $this->_sort;
    }

    /**
     * @return string 
     */
    protected function _getNameCondition() {
        if ( strlen($this->_namePattern) ) {
            return "AND (name ILIKE '%{$this->_namePattern}%' OR nick ILIKE '%{$this->_namePattern}%')";
        }

        return "";
    }

    /**
     * @return string
     */
    protected function _getNullResultsCondition() {
        if (!$this->_nullResults) {
            return "AND (subscriber_count != 0 OR post_count != 0 OR photo_count != 0 OR like_count != 0)";
        }

        return "";
    }
}