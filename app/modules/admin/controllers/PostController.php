<?php

class PostController extends ControllerAdmin {

    public $layout = '//layouts/column1';

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    /* public function actionCreate() {
      $model = new Post('admin');

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Post'])) {
      $model->attributes = $_POST['Post'];
      if ($model->save())
      $this->redirect(array('view', 'id' => $model->id));
      }

      $this->render('create', array(
      'model' => $model,
      ));
      } */

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->setScenario('admin');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->saveAttributes(array('trash'=>1));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Lists all models.
     */
    /* public function actionIndex()
      {
      $dataProvider=new CActiveDataProvider('Post');
      $this->render('index',array(
      'dataProvider'=>$dataProvider,
      ));
      } */

    /**
     * Manages all models.
     */
    public function actionIndex() { 
      
        $model = new Post('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Post']))
            $model->attributes = $_GET['Post'];

        $model->getDbCriteria()->select = 't.*, (substring(t.text,0,30) || \'...\') as text';

        $this->render('index', array(
            'model' => $model,
            'search' => $this->search($model)
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Post::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'post-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($model) {

        $criteria = $model->getDbCriteria();
        $criteria->compare('id', $model->id);
        $criteria->compare('name', $model->name, true);
        $criteria->compare('tp_user_id', $model->tp_user_id);
        $criteria->compare('date', $model->date);
        $criteria->compare('text', $model->text, true);
        $criteria->compare('allocation_id', $model->allocation_id);
        $criteria->compare('trash', $model->trash);
        $criteria->compare('lang', $model->lang);
        return new CActiveDataProvider($model, array(
            'criteria' => $criteria,
        ));
    }

}
