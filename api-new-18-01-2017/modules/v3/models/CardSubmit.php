<?php
namespace app\api\modules\v3\models;

use Yii;
use yii\base\Model;
use app\models\PlanCards;

/**
 * LoginForm is the model behind the login form.
 */
class CardSubmit extends Model
{
	public static function dataSubmit($activity)
	{
		return $activity;
	}
	public static function synCards($cards)
	{
		foreach($cards as $card)
		{
			$model=new PlanCards;
			$model->attributes=$card;
			if(!$model->save(false))
				return 'not saved';
		}
		return 'created';
	}
	
}