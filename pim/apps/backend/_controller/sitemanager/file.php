<?php
class Controller_File
{
	public function index()
	{
		if(form::submitted())
		{
			if(input::get("type") == "file")
			{
				$this->addFile();
			}
			else
			{
				$this->addFolder();
			}
		}

		view::render("sitemanager/file/index");
	}

	public function addFolder($folderID = 0)
	{
		if(form::submitted())
		{
			$rules	= Array(
				"fileFolderName,fileFolderPrivacy"=>"required:This field is required."
				);

			if($error = input::validate($rules))
			{
				// return response::json(Array("error"=>"Name and privacy are required"));
				input::repopulate();
				redirect::withFlash(model::load("template/services")->wrap("input-error",$error));
				redirect::to("","Error in your form's field","error");
			}

			$folderID	= input::get("fileFolderID");
			model::load("file/folder")->addFolder(authData("site.siteID"),$folderID,input::get());

			// return response::json(Array("succes"=>"success"));
			redirect::to("","Added new folder.");
		}
	}

	private function addFile()
	{
		// max size
		$maxsize = 5000000;

		$exts = array("xls","xlsx",
				"doc","docx","ppt","pptx",
				"pps","ppsx","odt","pdf",
				"png","jpeg","jpg","bmp",
				"zip","rar","mp3","m4a",
				"ogg","wav","mp4","m4v",
				"mov","wmv","avi","mpg",
				"ogv","3gp","3g2");

		$file	= input::file("fileUpload");
			
		$rules	= Array(
			"filePrivacy"=>"required:Please set a privacy.",
			"fileUpload"=>Array(
				"callback"=>Array(!$file?false:true,"Please input an upload file.")
				)
						);

		if($error = input::validate($rules))
		{
			input::repopulate();
			redirect::withFlash(model::load("template/services")->wrap("input-error",$error));

			redirect::to("","Error in your form's field","error");
		}


		$data	= input::get();
		$data['fileType']	= $file->get("type");

		if(!$file->isExt($exts))
		{
			redirect::to("","List of allowed file extension : ". implode(", ", $exts), "error");
		}

		if($file->get('size') > $maxsize)
		{
			redirect::to("", "File size cannot be bigger than ". ($maxsize/1000) . "kb ", "error");
		}

		## use file name.
		if($data['fileName'] == "")
		{
			$fn	= explode(".",$file->get("name"));
			array_pop($fn);
			$data['fileName']	= implode(".",$fn);
		}

		$data['fileSize']	= $file->get("size");
		$data['fileExt']	= $file->get("ext");

		$fileID	= model::load("file/file")->addFile(authData("site.siteID"),$data['fileFolderID'],$data);
		$path	= path::files("site_files/".authData("site.siteID"));

		if(!is_dir($path))
		{
			$mkdir = mkdir($path,0775,true);

			if(!$mkdir)
			{
				die;
			}
		}

		$file->move($path."/".$fileID);
		redirect::to("","Successfully uploaded new file.");
	}
}
?>