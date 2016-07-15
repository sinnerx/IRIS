<?php

class Controller_Activity
{
	public function training()
	{
		$data['res_training_type']	= model::load("activity/training")->getTrainingType();
		if($data['res_training_type']) 
			foreach($data['res_training_type'] as $row) 
				$id[] = $row['trainingTypeID'];

		//var_dump($id);
		//die;

		if($data['res_training_type'])
			$data['res_training']		= model::load("activity/training")->getTrainingByType($id);

		view::render("root/activity/training",$data);
	}

	public function trainingSubType()
	{
		$data['res_training_type']	= model::load("activity/training")->getTrainingSubType();
		if($data['res_training_type']) 
			foreach($data['res_training_type'] as $row) 
				$id[] = $row['trainingSubTypeID'];

		//var_dump($id);
		//die;
		if($data['res_training_type'])
			$data['res_training']		= model::load("activity/training")->getTrainingBySubType($id);

		view::render("root/activity/trainingSubType",$data);
	}	

	public function trainingTypeAdd()
	{
		if(form::submitted())
		{
			$rules['trainingTypeName'] = "required:Ruangan ini diperlukan.";

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("activity/trainingTypeAdd","Terdapat masalah di sini","error");
			}

			model::load("activity/training")->addType(input::get("trainingTypeName"),input::get("trainingTypeDescription"));

			redirect::to("activity/training","New type of training has been added.");
		}

		view::render("root/activity/trainingTypeAdd");
	}

	public function trainingSubTypeAdd()
	{
		$data['trainingTypeR']	= model::load("activity/training")->type();

		if(form::submitted())
		{
			$rules['trainingSubTypeName'] = "required:Ruangan ini diperlukan.";

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("activity/trainingSubTypeAdd","Terdapat masalah di sini","error");
			}

			model::load("activity/training")->addSubType(input::get("trainingSubTypeName"),input::get("trainingSubTypeDescription"),input::get("trainingType"));

			redirect::to("activity/trainingSubType","New sub type of training has been added.");
		}

		view::render("root/activity/trainingSubTypeAdd", $data);
	}

	public function trainingTypeEdit($id)
	{
		$training	= model::load("activity/training");
		$data['row']	= $training->getTrainingType(Array("trainingTypeID"=>$id));
		$data['row']	= $data['row'][0];

		if(form::submitted())
		{
			$rules['trainingTypeName'] = "required:Ruangan ini diperlukan.";

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("activity/trainingTypeEdit/$id","Terdapat masalah di sini","error");
			}

			$training->updateType($id,input::get("trainingTypeName"),input::get("trainingTypeDescription"));

			redirect::to("activity/training","Updated.");
		}

		view::render("root/activity/trainingTypeEdit",$data);
	}

	public function trainingSubTypeEdit($id)
	{
		$training	= model::load("activity/training");
		$data['row']	= $training->getTrainingSubType(Array("trainingSubTypeID"=>$id));
		$data['row']	= $data['row'][0];

		$data['trainingTypeR']	= model::load("activity/training")->type();

		if(form::submitted())
		{
			$rules['trainingSubTypeName'] = "required:Ruangan ini diperlukan.";

			if($error = input::validate($rules))
			{
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("activity/trainingSubTypeEdit/$id","Terdapat masalah di sini","error");
			}

			$training->updateSubType($id,input::get("trainingSubTypeName"),input::get("trainingSubTypeDescription"),input::get("trainingType"));

			redirect::to("activity/trainingSubType","Updated.");
		}

		view::render("root/activity/trainingSubTypeEdit",$data);
	}
	public function trainingTypeDelete($id)
	{
		$trainingType = model::orm('activity/training/type')->find($id);

		// actually just set the status to 0.
		$trainingType->deactivate();

		redirect::to('activity/training', 'This training has been deleted.');
	}
}


?>