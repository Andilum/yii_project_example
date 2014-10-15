<?php

class SRAllocation extends SR {
    /**
     * @return array
     */
    public function attributeNames() {
        return array(
            'id',
            'name',
            'alloccat_name',
            'resort_name',
            'country_name',
            'subscriber_count',
            'post_count',
            'photo_count',
            'photo_url',
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
                    al.id,
                    coalesce(sub.subscriber_count, 0) AS subscriber_count,
                    coalesce(p.post_count, 0) AS post_count,
                    coalesce(p.photo_count, 0) AS photo_count
                FROM (
                    SELECT id, cat, resort
                    FROM dict.dict_allocation
                    WHERE trash = false AND active = true {$this->_getNameCondition()}
                ) AS al
                LEFT JOIN (
                    SELECT
                        allocation_id,
                        count(subscriber_id) AS subscriber_count
                    FROM hi.hi_allocation_subscription AS als
                    WHERE als.trash = false
                    GROUP BY allocation_id
                ) AS sub ON sub.allocation_id = al.id
                LEFT JOIN (
                    SELECT
                        pc.allocation_id,
                        count(DISTINCT pc.post_id) AS post_count,
                        count(DISTINCT p.id) AS photo_count
                    FROM (
                        SELECT
                            p.allocation_id,
                            p.id AS post_id,
                            c.id AS comment_id
                        FROM (
                            SELECT allocation_id, id
                            FROM hi.hi_post
                            WHERE trash = false
                        ) AS p
                        LEFT JOIN hi.hi_comment AS c ON c.post_id = p.id AND c.trash = false
                    ) AS pc
                    LEFT JOIN hi.hi_photo AS p
                        ON (
                            p.owner_id = pc.comment_id
                            OR p.owner_id = pc.post_id
                        ) AND p.trash = false
                    GROUP BY pc.allocation_id
                ) AS p ON p.allocation_id = al.id
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
                al.id,
                al.name,
                alloccat.name AS alloccat_name,
                re.name AS resort_name,
                co.name AS country_name,
                coalesce(sub.subscriber_count, 0) AS subscriber_count,
                coalesce(p.post_count, 0) AS post_count,
                coalesce(p.photo_count, 0) AS photo_count
            FROM (
                SELECT id, name, cat, resort
                FROM dict.dict_allocation
                WHERE trash = false AND active = true {$this->_getNameCondition()}
            ) AS al
            LEFT JOIN dict.dict_alloccat alloccat ON (al.cat = alloccat.id)
            LEFT JOIN dict.dict_resort re ON (al.resort = re.id)
            LEFT JOIN dict.dict_country co ON (re.country = co.id)
            LEFT JOIN (
                SELECT
                    allocation_id,
                    count(subscriber_id) AS subscriber_count
                FROM hi.hi_allocation_subscription AS als
                WHERE als.trash = false
                GROUP BY allocation_id
            ) AS sub ON sub.allocation_id = al.id
            LEFT JOIN (
                SELECT
                    pc.allocation_id,
                    count(DISTINCT pc.post_id) AS post_count,
                    count(DISTINCT p.id) AS photo_count
                FROM (
                    SELECT
                        p.allocation_id,
                        p.id AS post_id,
                        c.id AS comment_id
                    FROM (
                        SELECT allocation_id, id
                        FROM hi.hi_post
                        WHERE trash = false
                    ) AS p
                    LEFT JOIN hi.hi_comment AS c ON c.post_id = p.id AND c.trash = false
                ) AS pc
                LEFT JOIN hi.hi_photo AS p
                    ON (
                        p.owner_id = pc.comment_id
                        OR p.owner_id = pc.post_id
                    ) AND p.trash = false
                GROUP BY pc.allocation_id
            ) AS p ON p.allocation_id = al.id
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

        $data = $dataProvider->getData();

        $eids = array();

        foreach ($data as $element) {
            $eids[] = $element['id'];
        }

        $photos = DictPhoto::getListByEids($eids);

        foreach ($data as $key => $element) {
            if (isset($photos[$element['id']])) {
                $photo = new DictPhoto();
                $photo->id = $photos[$element['id']]->id;
                $photo->ext_small = $photos[$element['id']]->ext_small;

                $data[$key]['photo_url'] = $photo->getUrl('s');
            } else {
                $data[$key]['photo_url'] = DictPhoto::HOTEL_PHOTO_NONE_URL;
            }
        }

        $dataProvider->setData($data);

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

        $this->_sort->defaultOrder = 'subscriber_count DESC, post_count DESC, photo_count DESC';
        $this->_sort->attributes = array(
            'tc' => array(
                'asc' => 'subscriber_count, post_count, photo_count',
                'desc' => 'subscriber_count DESC, post_count DESC, photo_count DESC',
                'label' => 'по сводному рейтингу',
            ),
            'pc' => array(
                'asc' => 'post_count',
                'desc' => 'post_count DESC',
                'label' => 'по контенту',
            ),
            'sc' => array(
                'asc' => 'subscriber_count',
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
            return "AND name ILIKE '%{$this->_namePattern}%'";
        }

        return "";
    }

    /**
     * @return string
     */
    protected function _getNullResultsCondition() {
        if (!$this->_nullResults) {
            return "AND (subscriber_count != 0 OR post_count != 0 OR photo_count != 0)";
        }

        return "";
    }
}