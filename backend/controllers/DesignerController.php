<?php

namespace backend\controllers;

use yii\web\Controller;
use bacend\models;
use yii\web\UploadedFile;
use yii;

class DesignerController extends controller {

    public function actionIndex() {

        $designerlistModel = new \backend\models\DesignerBasic();
        $search_word = '';
        if (Yii::$app->request->post('table_search')) {
            $search_word = Yii::$app->request->post('table_search');
            $designer = $designerlistModel::findBySql("select * from zyj_designer_basic where name like '%" . $search_word . "%'");
        } else {
            $designer = $designerlistModel::find();
        }
        $pagination = new \yii\data\Pagination(['totalCount' => $designer->count(), 'pageSize' => 10]);

        $data = $designer->offset($pagination->offset)->limit($pagination->limit)->all();

        return $this->render("index", ['data' => $data, 'pagination' => $pagination, 'model' => $designerlistModel, 'search_word' => $search_word]);
    }

    public function actionDetail($id) {
        $id = (int) $id;
        $designerbasicModel = new \backend\models\DesignerBasic();
        $designerbasicModel = $designerbasicModel::findOne($id);

        $designerWorkModel = new \backend\models\DesignerWork();
        $designerWorkModel = $designerWorkModel::findOne(['designer_id' => $id]) ? $designerWorkModel::findOne(['designer_id' => $id]) : $designerWorkModel;

        $designerAdditionalModel = new \backend\models\DesignerAdditional();
        $designerAdditionalModel = $designerAdditionalModel::findOne(['designer_id' => $id]) ? $designerAdditionalModel::findOne(['designer_id' => $id]) : $designerAdditionalModel;

        return $this->render('detail', ['model' => $designerbasicModel, 'modeladditional' => $designerAdditionalModel, 'modelwork' => $designerWorkModel, 'did' => $id]);
    }
    
    //设计师编辑
    public function actionEdit($id) {
        error_reporting(E_ALL & ~E_NOTICE);
        $id = (int) $id;
        //if(!$id){$id = Yii::$app->request->post('id');}
        // 判断是否有可编辑数据
        $designerbasicModel = new \backend\models\DesignerBasic();
        if ($id > 0 && ($designerbasicModel = $designerbasicModel::findOne($id))) {

            // 判断是否是ajax提交 以及是否是编辑动作
            if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
                //return $id;
                // 获取表单类型
                $formx = array_keys(Yii::$app->request->post());
                // 判断是那张表的信息
                if ("DesignerBasic" == $formx[1]) {
                    //判断model
                    $designerbasicModel = new \backend\models\DesignerBasic();
                    $designerbasicModel = $designerbasicModel::findOne($id);
                    $designerbasicModel->load(Yii::$app->request->post());
                    if ($designerbasicModel->save()) {
                        return '修改成功!';
                    } else {
                        return '失败!';
                    }
                } else if ("DesignerWork" == $formx[1]) {
                    $designerWorkModel = new \backend\models\DesignerWork();

                    //判断是否有值
                    $dm = $designerWorkModel::findOne(['designer_id' => $id]);
                    if ($dm) {
                        $designerWorkModel = $dm;
                    }

                    //无值添加
                    $post = Yii::$app->request->post();


                    //字段处理
                    //xl modified
                    if (isset($post['DesignerWork']['pay_extra']) && !empty($post['DesignerWork']['pay_extra'])) {
                        $post['DesignerWork']['pay_extra'] = implode(',', $post['DesignerWork']['pay_extra']);
                    } else {
                        $post['DesignerWork']['pay_extra'] = '';
                    }

                    if (isset($post['DesignerWork']['include_project']) && !empty($post['DesignerWork']['include_project'])) {
                        $post['DesignerWork']['include_project'] = implode(',', $post['DesignerWork']['include_project']);
                    } else {
                        $post['DesignerWork']['include_project'] = '';
                    }
                    //$post['DesignerWork']['pay_extra'] = implode(',', $post['DesignerWork']['pay_extra']);
                    //$post['DesignerWork']['include_project'] = implode(',', $post['DesignerWork']['include_project']);
                    $post['DesignerWork']['nowork_time'] = strtotime($post['DesignerWork']['nowork_time']);
                    $post['DesignerWork']['nowork_time2'] = strtotime($post['DesignerWork']['nowork_time2']);

                    $designerWorkModel->load($post);

//                    echo "<pre>";
//                    print_r($post);
//                    exit;
                    if ($designerWorkModel->save()) {
                        return '修改成功!';
                    } else {
                        return '失败!';
                    }
                } else if ('DesignerAdditional' == $formx[1]) {
                    $designerAdditionalModel = new \backend\models\DesignerAdditional();

                    //判断是否有值
                    $dm = $designerAdditionalModel::findOne(['designer_id' => $id]);
                    if ($dm) {
                        $designerAdditionalModel = $dm;
                    }

                    $designerAdditionalModel->load(Yii::$app->request->post());
                    if ($designerAdditionalModel->save()) {
                        return '修改成功!';
                    } else {
                        return '失败!';
                    }
                }
            } else {
                //赋值
                $designerAdditionalModel = new \backend\models\DesignerAdditional();
                $de = $designerAdditionalModel::findOne(['designer_id' => $id]);
                if ($de) {
                    $designerAdditionalModel = $de;
                }

                $designerWorkModel = new \backend\models\DesignerWork();
                $dm = $designerWorkModel::findOne(['designer_id' => $id]);

                if ($dm) {
                    if ($dm->include_project) {
                        $dm->include_project = explode(',', $dm->include_project);
                    };
                    if ($dm->pay_extra) {
                        $dm->pay_extra = explode(',', $dm->pay_extra);
                    };
                    if ($dm->nowork_time && $dm->nowork_time2) {
                        $dm->nowork_time = date('Y-m-d', (int) $dm->nowork_time);
                        $dm->nowork_time2 = date('Y-m-d', (int) $dm->nowork_time2);
                    } else {
                        $dm->nowork_time = '';
                        $dm->nowork_time2 = '';
                    }
                    $designerWorkModel = $dm;
                }
                // 非编辑动作 跳转页面
                return $this->render('edit', ['model' => $designerbasicModel, 'modeladditional' => $designerAdditionalModel, 'modelwork' => $designerWorkModel, 'did' => $id]);
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionImage() {
        if (Yii::$app->request->isPost) {
            $designerbasicModel = new \backend\models\DesignerBasic();
            $designerbasicModel->load(Yii::$app->request->post());
            $designerId = $designerbasicModel->id;

            $upFile = UploadedFile::getInstance($designerbasicModel, "image_id");
            //图片存放位置
            $dir = Yii::getAlias('@frontend') . "/web/uploads/" . date("Ymd");
            // Yii::info($dir, 'pushNotifications');
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }

            $fileName = date("HiiHsHis") . $upFile->baseName . "." . $upFile->extension;
            $dir = $dir . "/" . $fileName;
            $upFile->saveAs($dir);

            //操作zy_images表
            $uploadSuccessPath = "/uploads/" . date("Ymd") . "/" . $fileName;
            $imageModel = new \common\models\ZyImages();
            $imageModel->url = $uploadSuccessPath;
            $imageId = '';
            if ($imageModel->save()) {
                $imageId = $imageModel->image_id;
                $ret = $designerbasicModel::findOne($designerId);
                if (empty($ret)) {
                    return false;
                }
                $ret->image_id = $imageId;
                $ret->save();
            } else {
                return false;
            };


            $ret = array('designer_id' => $designerId, 'upfile' => $upFile);
            $json = json_encode($ret);

            //return $json;
            return $this->render('image', ['model' => $designerbasicModel]);
        } else {
            // 非添加动作 跳转页面
            $designerbasicModel = new \backend\models\DesignerBasic();

            return $this->render('image', ['model' => $designerbasicModel]);
        }
    }

    public function actionAdd() {
        error_reporting(E_ALL & ~E_NOTICE);

        // 返回json格式响应
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // 判断是否是ajax提交 以及是否是添加动作
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {

            // 获取表单类型
            $formx = array_keys(Yii::$app->request->post());
            // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            // return json_encode(Yii::$app->request->post());
            // 判断是那张表的信息
            if ("DesignerBasic" == $formx[1]) {
                //判断model
                $designerbasicModel = new \backend\models\DesignerBasic();

                $designerbasicModel->load(Yii::$app->request->post());

                if ($designerbasicModel->save()) {

                    //return '添加成功!';
                    $res = array('designerID' => $designerbasicModel->id, 'msg' => '添加成功!');
                    $resjson = json_encode($res);
                    return $resjson;
                } else {
                    return '失败!';
                }
            } else if ("DesignerWork" == $formx[1]) { //添加work表
                $designerWorkModel = new \backend\models\DesignerWork();
                $post = Yii::$app->request->post();

                //字段处理
                $post['DesignerWork']['nowork_time'] = strtotime($post['DesignerWork']['nowork_time']);
                $post['DesignerWork']['nowork_time2'] = strtotime($post['DesignerWork']['nowork_time2']);

                if (isset($post['DesignerWork']['pay_extra']) && !empty($post['DesignerWork']['pay_extra'])) {
                    $post['DesignerWork']['pay_extra'] = implode(',', $post['DesignerWork']['pay_extra']);
                } else {
                    $post['DesignerWork']['pay_extra'] = '';
                }

                if (isset($post['DesignerWork']['include_project']) && !empty($post['DesignerWork']['include_project'])) {
                    $post['DesignerWork']['include_project'] = implode(',', $post['DesignerWork']['include_project']);
                } else {
                    $post['DesignerWork']['include_project'] = '';
                }

                $designerWorkModel->load($post);

                if ($designerWorkModel->save()) {
                    return '添加成功!';
                } else {
                    return '失败!';
                }
            } else if ('DesignerAdditional' == $formx[1]) {
                $designerAdditionalModel = new \backend\models\DesignerAdditional();
                $designerAdditionalModel->load(Yii::$app->request->post());
                if ($designerAdditionalModel->save()) {
                    return '添加成功!';
                } else {
                    return '失败!';
                }
            } else {
                
            }
        } else {
            // 非添加动作 跳转页面
            $designerBasicModel = new \backend\models\DesignerBasic();
            $designerWorkModel = new \backend\models\DesignerWork();
            $designerAdditionalModel = new \backend\models\DesignerAdditional();

            return $this->render('add', ['model' => $designerBasicModel, 'modelwork' => $designerWorkModel, 'modeladditional' => $designerAdditionalModel]);
        }
    }

    public function actionDelete($id) {
        $designerlistModel = new \backend\models\DesignerBasic();
        $designerWorkModel = new \backend\models\DesignerWork();
        $designerAdditionalModel = new \backend\models\DesignerAdditional();

        $id = (int) $id;
        if ($id > 0) {
            $designerlistModel::findOne($id)->delete();
            $dm = $designerWorkModel::findOne(['designer_id' => $id]);
            if ($dm) {
                $designerWorkModel::findOne(['designer_id' => $id])->delete();
            }

            $de = $designerAdditionalModel::findOne(['designer_id' => $id]);
            if ($de) {
                $designerAdditionalModel::findOne(['designer_id' => $id])->delete();
            }
        }
        return $this->redirect(['index']);
    }

    public function uploadfile($model) {
        //$model = new Upload();
        $uploadSuccessPath = "";
        if (Yii::$app->request->isPost) {
            $model->image_id = UploadedFile::getInstance($model, "image_id");
            //文件上传存放的目录
            $dir = Yii::getAlias("@webroot") . "/upload/" . date("Ymd");
            if (!is_dir($dir))
                mkdir($dir);
            if ($model->validate()) {
                echo var_dump($model);
                exit;
                echo $model['image_id']->baseName;
                exit;
                //文件名
                $fileName = date("HiiHsHis") . $model->image_id->baseName . "." . $model->image_id->extension;
                $dir = $dir . "/" . $fileName;


                $model->image_id->saveAs($dir);
                $uploadSuccessPath = "/uploads/" . date("Ymd") . "/" . $fileName;
            }
        }
//        return $this->render("upload", [
//            "model" => $model,
//            "uploadSuccessPath" => $uploadSuccessPath,
//        ]);
    }

    public function actionHeadimg() {

        $uploadSuccessPath = "";
        $debasicModel = new \backend\models\DesignerBasic();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $designerId = $post['DesignerBasic']['id'];
            // echo $designerId;exit;
            $upFile = UploadedFile::getInstance($debasicModel, "head_imgid");
            //文件上传存放的目录
            $dir = Yii::getAlias('@frontend') . "/web/uploads/" . date("Ymd");

            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            if ($upFile) {
                $fileName = date("HiiHsHis") . $upFile->baseName . "." . $upFile->extension;
                $dir = $dir . "/" . $fileName;
                $upFile->saveAs($dir);

                //操作zy_images表
                $uploadSuccessPath = "/uploads/" . date("Ymd") . "/" . $fileName;
                $imageModel = new \common\models\ZyImages();
                $imageModel->url = $uploadSuccessPath;

                if ($imageModel->save()) {
                    $imageId = $imageModel->attributes['image_id'];
                    $ret = $debasicModel::findOne($designerId);
                    if (empty($ret)) {
                        return false;
                    }
                    $ret->head_imgid = $imageId;
                    if($ret->save()){
                        Yii::$app->getSession()->setFlash('success', '保存成功');
                        Yii::$app->getSession()->setFlash('imgurl', $uploadSuccessPath);
                    }
                } else {
                    return false;
                };
            }
        }

        return $this->render('headimg', ['model' => $debasicModel]);
    }

}
