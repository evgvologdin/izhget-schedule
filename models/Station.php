<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "r_station".
 *
 * @property integer $id
 * @property integer $igis_id
 * @property integer $izhget_id
 * @property string $igis_name
 * @property string $lat
 * @property string $lon
 * @property array $routes
 */
class Station extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%station}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['igis_id', 'izhget_id', 'igis_name', 'routes'], 'required'],
            [['igis_id', 'izhget_id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['igis_name'], 'string', 'max' => 255],
            [['igis_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'igis_id' => Yii::t('app', 'Igis ID'),
            'izhget_id' => Yii::t('app', 'Izhget ID'),
            'igis_name' => Yii::t('app', 'Igis Name'),
            'lat' => Yii::t('app', 'Lat'),
            'lon' => Yii::t('app', 'Lon'),
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert) === false) {
            return false;
        }
        
        $this->routes = implode(', ', $this->routes);
        return true;
    }
    
    public function afterFind()
    {
        if(parent::afterFind() === false) {
            return false;
        }
        
        $this->routes = explode(', ', $this->routes);
        return true;
    }
}
