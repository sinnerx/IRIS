<?php

class Controller_Activity
{
	public function training()
	{
		$data['res_training_type']	= model::load("activity/training")->getTrainingType();
		if($data['res_training_type']) foreach($data['res_training_type'] as $row) $id[] = $row['trainingTypeID'];

		if($data['res_training_type'])
			$data['res_training']		= model::load("activity/training")->getTrainingByType($id);

		view::render("root/activity/training",$data);
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
}


?>