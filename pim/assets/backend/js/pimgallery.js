var pimgallery	= new function()
{
	var context	= this;
	this.callback	= null;
	this.tab		= 1;

	this.setCallback	= function(cback)
	{
		this.callback = cback;
	};

	this.start	= function(obj,callback)
	{
		$(obj).attr("href",pim.base_url+"ajax/gallery/index");
		if(callback)
			this.callback = callback;
	}

	this.select = function(e)
	{
		var result	= {};
		if(this.tab == 1)
		{
			//check if photo uploaded.
			if(!$("#newPhoto").attr("src"))
			{
				alert("Please upload the photo, or choose from album, or existing photos.");
				return e.stopPropagation();
			}

			result.photoUrl	= $("#newPhoto").attr("src");
			result.photoPath = $("#newPhoto").data("photopath");
		}
		else if(this.tab == 2 || this.tab == 3) // check if got active photo-list.
		{
			var photo	= $(".photo-list.active");

			if(!photo.length)
			{
				alert("Please select a photo.");
				return e.stopPropagation();
			}

			result.photoPath = photo.data("photopath");
			result.photoUrl = photo.data("photourl");
		}

		if(this.callback)
		{
			this.callback(result);
		}
	}

	this.showTab	= function(tab)
	{
		this.tab	= tab;
		$(".mb-header > a, .mb-content > div").removeClass("active");
		$(".mb-header > a:nth-child("+tab+"), .mb-content > div:nth-child("+tab+")").addClass("active");

		if(tab == 2)
		{
			this.photopicker.loadPhotoList();
		}

		if(tab == 3)
		{
			this.photopicker.loadAlbumList();
		}
	}

	this.photopicker = new function()
	{
		this.loadPhotoList	= function(said,page)
		{
			var said	= said?said:0;
			var page	= page?page:1;

			var container	= said != 0?".ajxgal-albums":".ajxgal-photos";

			// load photos.
			p1mloader.start(".ajxgal-photos");
			var photoListUrl	= pim.base_url+"ajax/gallery/photoList/"+said+"/"+page;
			$.ajax({type:"GET",url:photoListUrl}).done(function(txt)
			{
				$(container).html(txt);
				context.photopicker.setPaginationClick();
				context.photopicker.setPhotoClick();
			});
		}

		this.loadAlbumList = function(type)
		{
			//load albums;
			p1mloader.start(".ajxgal-albums");
			var albumListUrl = pim.base_url+"ajax/gallery/albumList";
			$.ajax({type:"GET",url:albumListUrl}).done(function(txt)
			{
				$(".ajxgal-albums").html(txt);
				context.photopicker.setAlbumListClick();
			});
		}

		this.setNewPhotoUrl	= function(url,path)
		{
			$("#newPhoto").show();
			$("#newPhoto").attr("src",url);
			$("#newPhoto").data("photopath",path);

			//hide form.
			$("#formUpload").hide();
		}

		this.setPaginationClick	= function()
		{
			$(".ajxgal-pagination a").click(function()
			{
				var p		= $(this).data("page");
				var said	= $(this).data("said");

				// use loadPhotoList
				context.photopicker.loadPhotoList(said,p);

				return false;
			});
		}

		this.setPhotoClick	= function()
		{
			$(".photo-list").click(function()
			{
				$(".photo-list").removeClass("active");
				$(this).addClass("active");
			})
		}

		this.setAlbumListClick	= function()
		{
			$(".ajxgal-albums .album-list").click(function()
			{
				var said	= $(this).data("said");
				context.photopicker.loadPhotoList(said);
			});
		}
	}
}