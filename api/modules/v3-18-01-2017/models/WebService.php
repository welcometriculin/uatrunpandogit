<?php

namespace app\api\modules\v3\models;

use Yii;

/**
 * This is the model class for table "web_service".
 *
 * @property integer $id
 * @property string $params
 * @property string $created_date
 */
class WebService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['params'], 'required'],
            [['params'], 'string'],
            [['created_date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'params' => 'Params',
            'created_date' => 'Created Date',
        ];
    }
    public static function queryExecution($startTime,$endtime)
    {
    	echo  $difference = $endtime - $startTime;
        $queryTime = number_format($difference, 4);
    	echo 'query time   '.$queryTime;
    	exit();
    }
}
