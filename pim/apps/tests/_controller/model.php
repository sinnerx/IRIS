<?php
class Controller_Model
{
	var $template	= false;
	public function tests($model = null)
	{
		$param			= $this->testdata($model);
		$data['model']	= $model;
		$modelR			= explode("@",$model);	## explode string.

		## freeze any query input.
		db::freeze();

		if(count($modelR) != 2)
		{
			$data['error']	= "Error : Format example <u>helper@state</u> or <u>site/site@lists</u> or <u>site/site@getSite:1</u>";
		}
		else
		{
			$modelObj	= model::load($modelR[0]); ## load model object into variable.

			if($modelObj)
			{
				list($modelname,$model_param)	= explode(":",$modelR[1]);

				if($model_param)
				{
					$model_param	= urldecode($model_param);
					$param	= explode(",",$model_param);
				}

				try
				{
					## create new reflection
					$reflection = new ReflectionMethod($modelObj,$modelname);

					## invoke argument.
					$reflection->setAccessible(true);
					$data['value']		= $reflection->invokeArgs($modelObj, $param);
				}
				catch (Exception $e)
				{
					## set error message.
					$data['error']	= "Error : ".$e->getMessage();
				}

				$data['model']	= ($model_param?$modelR[0]."@".$modelname.":".$model_param:$model);
				$data['param']	= $param;

				if(count(db::getFrozenSQL()) > 0)
				{
					$data['frozen_sql']	= db::getFrozenSQL();
				}		
			}
		}

		## unfreeze here.
		db::unfreeze();

		return view::render("model/tests",$data);
	}

	private function testdata($model = null)
	{
		$siteID	= 10;

		$data	= Array(
				"image/album@addSiteAlbum"=>Array(
									Array(
								"albumName"=>"My album",
								"albumDescription"=>"Just description"
										)
												),
				"activity/activity@addActivity"=>Array(62,1,Array("test"=>123))
						);

		## return empty array if not exists.
		return $model?(isset($data[$model])?(is_array($data[$model])?$data[$model]:Array($data[$model])):Array()):$data;
	}
}


?>